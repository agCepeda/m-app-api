<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\AuthRepository;

use App\User;
use App\Session;

class AuthController extends ApiController
{
    const MSG_INCORRECT_PASSWORD = 'Password incorrect';
    const MSG_USER_NOT_CONFIRMED = 'The user has not been confirmed';
    const MSG_USER_NOT_FOUND     = 'Unregistered user';

    const MSG_CONFIRMATION_EMAIL_SENDED   = 'Se ha enviado un correo de confirmación';
    const MSG_OTHER_USER_USE_SAME_EMAIL   = 'Another user use the same email account';
    const MSG_INCORRECT_CONFIRMATION_DATA = 'Los datos de confirmación con incorrectos';

    /**
    * Crea una nueva instancia del controllador
    *
    * @return $this
    */
    public function __construct()
    {
        $this->middleware('auth.user', [
            'only' => [
                'checkSession',
                'logout',
                'updateDeviceToken',
                'updateLocation'
            ]
        ]);
    }

    public function login(
        Request $request,
        AuthRepository $authRepo
    ) {
        $username = $request->get('username');
        $password = $request->get('password');

        $session = $authRepo
                        ->loginWithCredentials($username, $password);

        return $this->responseSuccessful($session);
    }

    public function facebookLogin(
        Request $request,
        AuthRepository $authRepo
    ) {
        $this->validate($request, [
            'email'     => 'required|email',
            'name'      => 'required',
            'last_name' => 'required'
        ]);

        return $this->responseSuccessful(
            $authRepo->loginWithFacebook(
                $request->get('email'),
                $request->get('name'),
                $request->get('last_name')
            )
        );
    }

    public function logout(Request $request)
    {
        return env('APP_KEY');
    }

    public function signUp(
        Request $request,
        AuthRepository $authRepo
    ) {
        $this->validate($request, [
            'email'     => 'required|email',
            'name'      => 'required',
            'last_name' => 'required',
            'password'  => 'required'
        ]);

        return $this->responseSuccessful(
            $authRepo->registerUser(
                $request->get('email'),
                $request->get('name'),
                $request->get('last_name'),
                $request->get('password')
            )
        );
    }

    public function confirmUser(Request $request)
    {
        $this->validate($request, [
            'confirmation_token' => 'required',
            'user_id'            => 'required'
        ]);

        $token  = $request->get('confirmation_token');
        $userId = $request->get('user_id');

        $user = User::findOrFail($userId);

        if ($user->confirmation_token == $token) {
            $user->confirmed = true;
            $user->save();

            return view('confirmed_account')
                        ->with(['user' => $user]);
        } else {
            return response()->json([
                'message' => self::MSG_INCORRECT_CONFIRMATION_DATA
            ], 401);
        }
    }

    public function checkSession(User $user)
    {
        return response()->json(
            $user->load(['profession', 'card'])
        );
    }

    public function updateDeviceToken(
        Session $session,
        Request $request,
        AuthRepository $authRepo
    ) {
        $authRepo->setDeviceToken(
            $session->token,
            $request->get('device_token', null)
        );
    }

    public function updateLocation(
        Request $request,
        AuthRepository $authRepo
    ) {
        $latitude  = $request->get('latitude', null);
        $longitude = $request->get('longitude', null);

        $authRepo->setLocation($latitude, $longitude);
    }
}
