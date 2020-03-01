<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends AppBaseController
{
    /**
     * @OA\Post(
     *      path="/auth/register",
     *      summary="Create new user",
     *      tags={"Auth"},
     *      description="Register new user",
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="User email",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         description="User password",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         required=true,
     *         description="Password confirmation",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User name",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="User last name",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="patronymic",
     *         in="query",
     *         description="User patronymic",
     *         style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation exception"
     *      )
     * )
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function register(RegistrationRequest $request)
    {
        $user = User::create($request->all());
        if (!$token = JWTAuth::fromUser($user)) {
            return $this->sendError('Unauthorized', 401);
        }
        return $this->respondWithToken($token);
    }
}
