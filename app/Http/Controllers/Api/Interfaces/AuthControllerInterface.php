<?php

namespace App\Http\Controllers\Api\Interfaces;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;

interface AuthControllerInterface
{
    /**
     * @OA\Post(
     * path="/api/v1/register",
     * summary="Register admin user",
     * description="Register admin user",
     * operationId="register",
     * tags={"Unauthenticated"},
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
    public function register(RegistrationRequest $request);

    /**
     * @OA\Post(
     * path="/api/v1/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="login",
     * tags={"Unauthenticated"},
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
     * @OA\Response(response=200, description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=400, description="Bad request", @OA\JsonContent()),
     * @OA\Response(response=401, description="Unauthorized",  @OA\JsonContent()),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function login(LoginRequest $request);

}
