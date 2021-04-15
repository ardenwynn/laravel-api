<?php

namespace App\Http\Controllers\ApiResources;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentStoreUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentResourceCollection;
use App\Models\Comment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('cache')->only('index', 'show');
        $this->middleware('role:admin')->only('store', 'update', 'destroy');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $comments = QueryBuilder::for(Comment::class)
            ->allowedFilters(['content', 'id'])
            ->allowedSorts(['content', 'id'])
            ->allowedFields('content', 'id')
            ->allowedIncludes(['user', 'article'])
            ->jsonPaginate($request->get('paginate'));

        return (new CommentResourceCollection($comments))->response()->setStatusCode(200);
    }

    /**
     * @param CommentStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function store(CommentStoreUpdateRequest $request): JsonResponse
    {
        $comment = Comment::create($request->validated());
        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function show(Comment $comment): JsonResponse
    {
        $commentResult = QueryBuilder::for(Comment::class)
            ->allowedFields('content', 'id')
            ->allowedIncludes(['user', 'comments'])
            ->findOrFail($comment->id);

        return (new CommentResource($commentResult))->response()->setStatusCode(200);
    }

    /**
     * @param Comment $comment
     * @param CommentStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function update(Comment $comment, CommentStoreUpdateRequest $request): JsonResponse
    {
        $comment->update($request->validated());
        return (new CommentResource($comment))->response()->setStatusCode(200);
    }

    /**
     * @param Comment $comment
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();
        return response()->json(null, 204);
    }
}
