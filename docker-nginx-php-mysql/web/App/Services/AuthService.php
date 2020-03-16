<?php


namespace App\Services;

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
            $user = UserService::Get()->getUserByWhere(['email' => $email]);
            if (!$user || !password_verify($password, $user->password)) {
                $user = false;
            }
        } else {
            $user = UserService::Get()->getUserByWhere(['email' => $email, 'password' => $password]);
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
     * @return User|false
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