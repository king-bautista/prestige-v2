<?php

namespace App\Http\Controllers\Portal\Interfaces;
use Illuminate\Http\Request;

interface RolesControllerInterface
{
    /**
     * @OA\Get(
     * path="/portal/roles/list",
     * summary="List of roles",
     * description="List of roles",
     * operationId="portal-roles-list",
     * tags={"Portal Role"},
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
     * path="/portal/roles/{id}",
     * summary="Role details",
     * description="Role details",
     * operationId="portal-roles-details",
     * tags={"Portal Role"},
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
     * path="/portal/roles/store",
     * summary="Store role details",
     * description="Store role details",
     * operationId="register-portal-role",
     * tags={"Portal Role"},
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
     * path="/portal/roles/update",
     * summary="Update portal role",
     * description="Update portal role",
     * operationId="portal-roles-update",
     * tags={"Portal Role"},
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
     * path="/portal/roles/delete/{id}",
     * summary="Delete portal role",
     * description="Delete portal role",
     * operationId="portal-roles-delete",
     * tags={"Portal Role"},
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
