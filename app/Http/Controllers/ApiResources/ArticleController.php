<?php

namespace App\Http\Controllers\ApiResources;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleStoreUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleResourceCollection;
use App\Models\Article;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleController extends Controller
{
    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        $this->middleware('cache')->only('index', 'show');
        $this->middleware('role:admin')->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $articles = QueryBuilder::for(Article::class)
            ->allowedFilters(['title', 'content', 'id'])
            ->allowedSorts(['title', 'content', 'id'])
            ->allowedFields('title', 'content', 'id')
            ->allowedIncludes(['user', 'comments'])
            ->jsonPaginate($request->get('paginate'));

        return (new ArticleResourceCollection($articles))->response()->setStatusCode(200);
    }

    /**
     * @param ArticleStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function store(ArticleStoreUpdateRequest $request): JsonResponse
    {
        $article = Article::create($request->validated());
        return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    /**
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $articleResult = QueryBuilder::for(Article::class)
            ->allowedFields('title', 'content', 'id')
            ->allowedIncludes(['user', 'comments'])
            ->findOrFail($article->id);

        return (new ArticleResource($articleResult))->response()->setStatusCode(200);
    }

    /**
     * @param Article $article
     * @param ArticleStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function update(Article $article, ArticleStoreUpdateRequest $request): JsonResponse
    {
        $article->update($request->validated());
        return (new ArticleResource($article))->response()->setStatusCode(200);
    }

    /**
     * @param Article $article
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return response()->json(null, 204);
    }
}
