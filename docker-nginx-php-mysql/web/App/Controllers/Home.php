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
        if ($cuser) {
            $message = TranslateService::Get()->getTranslateSecure('Welcome') . ' ' . $cuser->name;
        } else {
            $message = TranslateService::Get()->getTranslateSecure('Вы не авторизованы');
        }
        $data = [
            'title' => TranslateService::Get()->getTranslateSecure('Домашняя Страница'),
            'message' => $message,
            'language' => @$_SESSION['Language'],
            'auth' => boolval($cuser)
        ];
        View::renderTemplate('home.html', $data);
    }
}
