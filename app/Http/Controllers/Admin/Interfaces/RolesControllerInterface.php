<?php

namespace App\Http\Controllers\Admin\Interfaces;
use Illuminate\Http\Request;

interface RolesControllerInterface
{
    /**
     * @OA\Get(
     * path="/admin/roles/list",
     * summary="List of roles",
     * description="List of roles",
     * operationId="admin-roles-list",
     * tags={"Admin Role"},
     * @OA\Parameter(
     *      name="search",
     *      description="Search",
     *      required=false,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(response=422, description="Unauthorized",  @OA\JsonContent()),
     * )
     */
    public function list(Request $request);

    /**
     * @OA\Get(
     * path="/admin/roles/{id}",
     * summary="Role details",
     * description="Role details",
     * operationId="admin-roles-details",
     * tags={"Admin Role"},
     * @OA\Parameter(
     *      name="id",
     *      description="Role ID",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(response=422, description="Unauthorized",  @OA\JsonContent()),
     * )
     */
    public function details($id);

    /**
     * @OA\Post(
     * path="/admin/roles/store",
     * summary="Store role details",
     * description="Store role details",
     * operationId="register-admin-role",
     * tags={"Admin Role"},
     * @OA\Parameter(
     *      name="name",
     *      description="Role name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="description",
     *      description="Role description",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, invalid email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function store(Request $request);

    /**
     * @OA\Put(
     * path="/admin/roles/update",
     * summary="Update admin role",
     * description="Update admin role",
     * operationId="admin-roles-update",
     * tags={"Admin Role"},
     * @OA\Parameter(
     *      name="name",
     *      description="Role name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="description",
     *      description="Role description",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="isActive",
     *      description="Is Active",
     *      required=false,
     *      in="query",
     *      @OA\Schema(type="boolean")
     * ), 
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, invalid email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function update(Request $request);

    /**
     * @OA\Get(
     * path="/admin/roles/delete/{id}",
     * summary="Delete admin role",
     * description="Delete admin role",
     * operationId="admin-roles-delete",
     * tags={"Admin Role"},
     * @OA\Parameter(
     *      name="id",
     *      description="Role ID",
     *      required=false,
     *      in="path",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(response=422, description="Unauthorized",  @OA\JsonContent()),
     * )
     */
    public function delete($id);
}
