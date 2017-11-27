<?php

namespace App\Repositories;

use App\Exceptions\AuthException;

use App\User;
use App\Session;

class AuthRepository extends Repository
{
    const MSG_INCORRECT_PASSWORD = 'Password incorrect';
    const MSG_USER_NOT_CONFIRMED = 'The user has not been confirmed';
    const MSG_USER_NOT_FOUND     = 'Unregistered user';

    const MSG_CONFIRMATION_EMAIL_SENDED   = 'Se ha enviado un correo de confirmación';
    const MSG_OTHER_USER_USE_SAME_EMAIL   = 'Another user use the same email account';
    const MSG_INCORRECT_CONFIRMATION_DATA = 'Los datos de confirmación con incorrectos';

    public function loginWithCredentials($username, $password)
    {
        $user = User::where('email', $username)->first();

        if (isset($user) && $user != null) {
            if ($user->confirmed == true) {
                if (app('hash')->check($password, $user->password)) {
                    $session = new Session([
                        'token'   => generateRandomString(50),
                        'user_id' => $user->id
                    ]);

                    if ($session->save()) {
                        $session->load([
                            'user.card'
                        ]);
                    }

                    return $session;
                }
                throw new AuthException(self::MSG_INCORRECT_PASSWORD);
            }
            throw new AuthException(self::MSG_USER_NOT_CONFIRMED);
        }
        throw new AuthException(self::MSG_USER_NOT_FOUND);
    }

    public function loginWithFacebook($email, $name, $lastName)
    {
        $user = User::where('email', $email)->first();

        if ($user == null) {
            // Crear nuevo usuario de facebook y sesion
            $user = new User([
                'email'     => $email,
                'name'      => $name,
                'last_name' => $lastName
            ]);

            $user->save();
        }

        $session = new Session([
            'token'   => generateRandomString(50),
            'user_id' => $user->id
        ]);

        if ($session->save()) {
            $session->load([
                'user.card'
            ]);
        }

        return $session;
    }

    public function registerUser($email, $name, $lastName, $password)
    {
        $user = User::where('email', strtolower($email))->first();

        if ($user == null) {
            $user = new User([
                'email'     => strtoupper($email),
                'name'      => $name,
                'last_name' => $lastName,
                'password'  => app('hash')->make($password),
                'confirmed' => true
            ]);

            try {
                $user->save();
                $session = new Session([
                    'token'   => generateRandomString(50),
                    'user_id' => $user->id
                ]);

                if ($session->save()) {
                    $session->load([
                        'user.card'
                    ]);
                }

                return $session;
            } catch (\Exception $e) {
                app('log')->debug($e);
                throw new AuthException(self::MSG_OTHER_USER_USE_SAME_EMAIL);
            }
        }
    }

    public function setDeviceToken($sessionToken, $deviceToken)
    {
        Session::where('token', $sessionToken)
            ->update(['device_token' => $deviceToken]);
    }

    public function setLocation($latitude, $longitude)
    {
        $user = app(User::class);

        $user->latitude = $latitude;
        $user->longitude = $longitude;

        $user->save();
    }
}
