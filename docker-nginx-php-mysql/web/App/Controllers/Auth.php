<?php

namespace App\Controllers;

use App\Services\TranslateService;
use \Core\Controller;
use \Core\View;
use App\Services\AuthService;
use \App\Models\User;
/**
 * Auth controller
 */
class Auth extends Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        if (AuthService::Get()->getUser()) {
            header("Location: /", true, 301);
            exit();
        }

        $data = [
            'title' => TranslateService::Get()->getTranslateSecure('Авторизация')
        ];
        if ($this->getArgumentSecure('Submit')) {
            if ($data['messageRegister'] = $this->registerAction()) {
                $data['register'] = true;
            } else {
                Header('Location /', true, 301);
            }

        } elseif ($this->getArgumentSecure('signIn')) {
            if ($this->loginAction()) {
                header("Location: /", true, 301);
                exit();
            } else {
                $data['message'] = TranslateService::Get()->getTranslateSecure('Неправильный логин или пароль');
            }
        }
        View::renderTemplate('auth.html', $data);
    }

    /**
     * Register action
     */
    public function registerAction()
    {
        $errorArray = [];

        $name = trim($this->getArgumentSecure('name'));
        $email = trim($this->getArgumentSecure('email'));
        $password = $this->getArgumentSecure('Password');
        $passwordConfirm = $this->getArgumentSecure('Confirm_Password');


        if (!filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
            $errorArray[] = TranslateService::Get()->getTranslateSecure('Некоректный email');
        };
        if ($password != $passwordConfirm) {
            $errorArray[] = TranslateService::Get()->getTranslateSecure(
                'Пароли не совпадают'
            );
        }
        if (mb_strlen($password) < 8) {
            $errorArray[] = TranslateService::Get()->getTranslateSecure(
                'Минимальная длина пароля 8 символов'
            );
        }
        if (preg_match('/a-zа-я/ius', $name)) {
            $errorArray[] = TranslateService::Get()->getTranslateSecure(
                'Имя не должно содержать спецсимволов или цифр'
            );
        }
        $upload_file = false;
        if ($_FILES['File']['name']) {

            $file_type = $_FILES['File']['type']; //returns the mimetype

            $allowed = array("image/jpeg", "image/gif", "image/png");
            if (!in_array($file_type, $allowed)) {
                $errorArray[] = TranslateService::Get()->getTranslateSecure(
                    'Разрешено загружать только файлы форматов jpg, gif, png'
                );
            } elseif (!$errorArray) {
                $upload_dir = __DIR__ . '/../../public/upload/';
                $upload_file = $upload_dir . basename($_FILES['File']['name']);
                move_uploaded_file($_FILES['File']['tmp_name'], $upload_file);
            }
        }
        if (!$errorArray) {
            try {
                //@TODO move register to UserService
                $user = new User();


                $user->name = $name;
                $user->password = AuthService::Get()->createPasswordHash($password);
                $user->email = $email;
                $user->image = $upload_file;
                $user->save();
                AuthService::Get()->login($email, $password);
            } catch (\Exception $e) {
                $errorArray[] = TranslateService::Get()->getTranslateSecure(
                    'Пользователь с таким email уже сущетсвует'
                );
            }
        }

        $message = false;
        if ($errorArray) {
            $message = implode('<br>', $errorArray);
        }
        return $message;
    }


    /**
     * log in user
     *
     * @return bool
     */
    public function loginAction()
    {
        if ($this->getArgumentSecure('signIn')) {
            $login = $this->getArgumentSecure('login');
            $password = $this->getArgumentSecure('password');
            $user = AuthService::Get()->login($login, $password);
            return boolval($user);
        }
    }

    /**
     * Logout user
     */
    public function logoutAction()
    {
        AuthService::Get()->logout();
        Header('Location: /', true, 301);
    }
}
