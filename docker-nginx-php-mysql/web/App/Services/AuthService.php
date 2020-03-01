<?php


namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use \App\Models\User;

class AuthService
{

    /**
     * Log in user
     *
     * @param $email
     * @param $password
     * @param bool $isHash
     *
     * @return Builder|Eloquent|object|null*
     */
    public function login($email, $password, $isHash = false) {
        //@TODO brutforce defence
        if (!$isHash) {
            $user = User::query()->where(['email' => $email])->first();
            if (!password_verify($password, $user->password)) {
                $user = false;
            }
        } else {
            $user = User::query()->where(['email' => $email, 'password' => $password])->first();
        }


        if ($user) {
            $_SESSION['login'] = $user->email;
            $_SESSION['password'] = $user->password;
        }
        $this->_user = $user;

        return $user;
    }

    /**
     * Get current user
     *
     * @return Builder|Eloquent|object|null*
     */
    public function getUser() {
        return $this->_user;
    }

    public function auth() {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        if ($login && $password) {
            $this->login($login, $password, true);
        }
    }

    public function createPasswordHash($value) {
        return password_hash($value, PASSWORD_BCRYPT);
    }


    /**
     * Log out current user
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        $this->_user = false;
    }

    private $_user;

    /**
     * Get the Auth
     *
     * @return AuthService
     */
    public static function Get() {
        if (!self::$_Instance) {
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    private static $_Instance;
}