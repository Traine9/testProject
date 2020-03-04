<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\TranslateService;
use \Core\View;

/**
 * Home controller
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $cuser = AuthService::Get()->getUser();
        $userDataArray = [];
        if ($cuser) {
            $userDataArray = [
                'image' => $cuser->image,
                'name' => $cuser->name,
                'email' => $cuser->email
            ];
            $message = TranslateService::Get()->getTranslateSecure('Добро пожаловать') . ' ' . $cuser->name;
        } else {
            $message = TranslateService::Get()->getTranslateSecure('Вы не авторизованы');
        }
        $data = [
            'title' => TranslateService::Get()->getTranslateSecure('Домашняя Страница'),
            'message' => $message,
            'language' => @$_SESSION['Language'],
            'auth' => boolval($cuser),
        ];
        $data = array_merge($data, $userDataArray);
        View::renderTemplate('home.html', $data);
    }
}
