<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AppBaseController;
use App\Models\Social;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class CompaniesController
 * @package Modules\Company\Http\Controllers\API
 */
class AuthAPIController extends AppBaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.jwt', [
                'except' => [
                    'login',
                    'redirectToProvider',
                    'handleProviderCallback',
                    'loginWithSocial'
                ]
            ]
        );
    }

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      summary="Авторизация в системе",
     *      tags={"Auth"},
     *      description="Авторизация",
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="Email",
     *         style="form"
     *      ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         description="Password",
     *         style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->sendError('Unauthorized', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *      path="/auth/me",
     *      summary="Get user info via JWT token",
     *      tags={"Auth"},
     *      description="Store Company",
     *      @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=true,
     *         description="Auth token",
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
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        /** @var User $user */
        $user = Auth::user();

        $permissions = array_map(function ($item) {
            return $item['name'];
        }, $user->getAllPermissions()->toArray());

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse(
            [
                'roles' => $user->getRoleNames(),
                'permissions' => $permissions,
            ] + $user->toArray(),
            'User retrieved'
        );
    }

    /**
     * @OA\Get(
     *      path="/auth/social/{provider_name}",
     *      summary="Redirects user to auth form of social",
     *      tags={"Auth"},
     *      description="odnoklassniki or vkontakte",
     *      @OA\Response(
     *          response=200,
     *          description="Must return "
     *      )
     * )
     *
     * Redirect the user to the GitHub authentication page.
     *
     * @param string $providerName
     * @return RedirectResponse
     */
    public function redirectToProvider(string $providerName)
    {
        return Socialite::with($providerName)
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleProviderCallback(Request $request)
    {
        return $this->sendSuccess(['code' => $request->get('code')]);
    }

    /**
     * @OA\Post(
     *      path="/auth/social/{provider_name}",
     *      summary="Retrieve JWT token for site",
     *      tags={"Auth"},
     *      description="Retrieve JWT token for site via social",
     *      @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Have to get it from GET /auth/social/{provider_name}",
     *         style="form"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     *
     * @param Request $request
     * @param string $providerName
     * @return JsonResponse
     */
    public function loginWithSocial(Request $request, string $providerName)
    {
        $social = mb_strtoupper($providerName);
        $uri = [
            'VKONTAKTE' => 'https://oauth.vk.com/access_token',
            'ODNOKLASSNIKI' => 'http://api.odnoklassniki.ru/oauth/token.do',
        ];
        $query = [
            'client_id' => env($social . '_CLIENT_ID'),
            'client_secret' => env($social . '_CLIENT_SECRET'),
            'redirect_uri' => env($social . '_REDIRECT_URI'),
            'code' => $request->code,
        ];
        if ($providerName == 'odnoklassniki') {
            $query = [
                    'grant_type' => 'authorization_code',
                ] + $query;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri[$social]); // url, куда будет отправлен запрос
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($query))); // передаём параметры
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        $tokenInfo = json_decode($result, true);
        $user = Socialite::driver($providerName)->userFromToken($tokenInfo['access_token']);

        $userName = explode(' ', $user->getName());

        $userData['name'] = $userName[0];
        $userData['last_name'] = $userName[1];

//        dd($tokenInfo, Socialite::driver($providerName)->userFromToken($tokenInfo['access_token']));
        if (array_key_exists('email', $tokenInfo)) {
            $userData['email'] = $tokenInfo['email'];
        } else {
            $userData['email'] = $user->getEmail() ?? 'default@email.ru';
        }

        $providerId = $user->getId();

        // Привязка пользователя соц. сети к id пользователя если такой email уже есть
        // или создание нового юзера с емейлом и привязка соц. сети к этому емейлу
        if (!$user = User::exists($userData['email'])->first()) {
            $user = User::create($userData);
            $user->save();
        }

        $socialData = [
            'provider' => $providerName,
            'provider_id' => $providerId,
            'user_id' => $user->id
        ];
        Social::firstOrCreate(['provider_id' => $providerId], $socialData);

        if (!$token = JWTAuth::fromUser($user)) {
            return $this->sendError('Unauthorized', 401);
        }

        return $this->respondWithToken($token);
    }
}
