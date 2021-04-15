<?php

namespace App\Http\Controllers\ApiResources;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleResourceCollection;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    /**
     * RoleController constructor.
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
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters(['name', 'id'])
            ->allowedSorts(['name', 'id'])
            ->allowedFields('name', 'id')
            ->allowedIncludes(['users'])
            ->jsonPaginate($request->get('paginate'));

        return (new RoleResourceCollection($roles))->response()->setStatusCode(200);
    }

    /**
     * @param RoleStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function store(RoleStoreUpdateRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());
        return (new RoleResource($role))->response()->setStatusCode(201);
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        $roleResult = QueryBuilder::for(Role::class)
            ->allowedFields('name', 'id')
            ->allowedIncludes(['users'])
            ->findOrFail($role->id);

        return (new RoleResource($roleResult))->response()->setStatusCode(200);
    }

    /**
     * @param Role $role
     * @param RoleStoreUpdateRequest $request
     * @return JsonResponse
     */
    public function update(Role $role, RoleStoreUpdateRequest $request): JsonResponse
    {
        $role->update($request->validated());
        return (new RoleResource($role))->response()->setStatusCode(200);
    }

    /**
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return response()->json(null, 204);
    }
}
