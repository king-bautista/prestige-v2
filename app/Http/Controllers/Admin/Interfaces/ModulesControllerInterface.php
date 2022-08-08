<?php

namespace App\Http\Controllers\Admin\Interfaces;
use Illuminate\Http\Request;

interface ModulesControllerInterface
{
    /**
     * @OA\Get(
     * path="/admin/modules/list",
     * summary="List of modules",
     * description="List of modules",
     * operationId="admin-modules-list",
     * tags={"Admin Module"},
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
     * path="/admin/modules/{id}",
     * summary="Module details",
     * description="Module details",
     * operationId="admin-modules-details",
     * tags={"Admin Module"},
     * @OA\Parameter(
     *      name="id",
     *      description="Module ID",
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
     * path="/admin/modules/store",
     * summary="Store module details",
     * description="Store module details",
     * operationId="register-admin-module",
     * tags={"Admin Module"},
     * @OA\Parameter(
     *      name="name",
     *      description="Module name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="link",
     *      description="Module link",
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
     * path="/admin/modules/update",
     * summary="Update admin module",
     * description="Update admin module",
     * operationId="admin-modules-update",
     * tags={"Admin Module"},
     * @OA\Parameter(
     *      name="name",
     *      description="Module name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="link",
     *      description="Module link",
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
     * path="/admin/modules/delete/{id}",
     * summary="Delete admin module",
     * description="Delete admin module",
     * operationId="admin-modules-delete",
     * tags={"Admin Module"},
     * @OA\Parameter(
     *      name="id",
     *      description="Module ID",
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
