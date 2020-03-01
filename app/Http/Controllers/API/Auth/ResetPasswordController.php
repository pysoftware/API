<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Auth\Notifications\ResetPasswordNotification;
use Modules\Auth\Http\Controllers\API\Requests\ResetPasswordRequest;
use Modules\Auth\Models\PasswordReset;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends AppBaseController
{

    /**
     * @OA\Post(
     *      path="/auth/reset_password",
     *      summary="Send email to user about password reset",
     *      tags={"Auth"},
     *      description="Send email with link",
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="User email",
     *         style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function sendEmailWithUri(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            return $this->sendError('User not found');
        }
        $token = Str::random(60);
        PasswordReset::updateOrCreate([
            'email' => $user->email,
            'token' => $token,
        ]);
        // SEND EMAIL NOTIFICATION
        $user->notify(new ResetPasswordNotification($user, $token));
        return $this->sendSuccess('Message sent successfully.');
    }

    /**
     * @OA\Put(
     *      path="/auth/reset_password",
     *      summary="Reset password",
     *      tags={"Auth"},
     *      description="Reset users password",
     *      @OA\Parameter(
     *         name="token",
     *         required=true,
     *         in="query",
     *         description="Token",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         description="New password",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="passwor_confirmation",
     *         in="query",
     *         required=true,
     *         description="New password confirmation",
     *         style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetPassword = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        if (empty($resetPassword)) {
            return $this->sendError('Something went wrong.');
        }
        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => bcrypt($request->get('password')),
        ]);

        return $this->sendSuccess('Password successfully changed');
    }
}
