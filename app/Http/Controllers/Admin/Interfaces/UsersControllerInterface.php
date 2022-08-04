<?php

namespace App\Http\Controllers\Admin\Interfaces;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;

interface UsersControllerInterface
{
    /**
     * @OA\Get(
     * path="/admin/users/list",
     * summary="List of admin users",
     * description="List of admin users",
     * operationId="admin-users",
     * tags={"Admin User"},
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
     * @OA\Post(
     * path="/admin/users/store",
     * summary="Register admin user",
     * description="Register admin user",
     * operationId="registerAdmin",
     * tags={"Admin User"},
     * @OA\Parameter(
     *      name="first_name",
     *      description="First name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="last_name",
     *      description="Last name",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="email",
     *      description="Email address",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="password",
     *      description="Password",
     *      required=true,
     *      in="query",
     *      @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *      name="password_confirmation",
     *      description="Confirmation password",
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
    public function store(RegistrationRequest $request);


}
