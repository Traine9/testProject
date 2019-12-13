<?php

namespace App\Controllers;

use \Core\View;
use \App\Controllers\Auth;
use \App\Models\Users;

/**
 * Home controller
 *
 * PHP version 7.0
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
        if ( Auth::loginAction() ){
            if (isset($_SESSION['Language'])) {
                if ($_SESSION['Language'] == 'en') {
                    $data = [
                        'language' => 'en',
                        'title' => 'Home',
                        'message' => 'Welcome ',
                        'auth' => Users::getFirstName_Where('eMail', $_SESSION['Login_ID'])[0]['First_Name'],
                    ];

                    View::renderTemplate('home.html', $data);
                }
                elseif ($_SESSION['Language'] == 'ru') {
                    $data = [
                        'language' => 'ru',
                        'title' => 'Домашняя Страница',
                        'message' => 'Добро Пожаловать ',
                        'auth' => Users::getFirstName_Where('eMail', $_SESSION['Login_ID'])[0]['First_Name'],
                    ];

                    View::renderTemplate('home.html', $data);
                }
            }
        }
        elseif ( isset($_SESSION['Waiting_to_Activation']) and $_SESSION['Waiting_to_Activation'] == 'not activated'){
            if (isset($_SESSION['Language'])) {
                if ($_SESSION['Language'] == 'ru') {
                    $data = [
                        'language' => 'ru',
                        'title' => 'Домашняя Страница',
                        'message' => 'Пожалуйста, перейдите на вашу электронную почту и активируйте аккаунт.',
                    ];

                    View::renderTemplate('home.html', $data);
                }
                elseif ( $_SESSION['Language'] == 'en' ){
                    $data = [
                        'language' => 'en',
                        'title' => 'Home',
                        'message' => 'Please go to your eMail and activate the account',
                    ];

                    View::renderTemplate('home.html', $data);
                }
            }
            else{
                $data = [
                    'language' => 'en',
                    'title' => 'Home',
                    'message' => 'Please go to your eMail and activate the account',
                ];

                View::renderTemplate('home.html', $data);
            }
        }
        else{
         if (isset($_SESSION['Language'])){
             if ( $_SESSION['Language'] == 'ru') {
                 $data =[
                     'language' => 'ru',
                     'title'    => 'Домашняя Страница',
                     'message'  => 'Вы не авторизованы',
                 ];

                 View::renderTemplate('home.html', $data);
             }
             elseif ( $_SESSION['Language'] == 'en' ){
                 $data = [
                     'language'=> 'en',
                     'title'   => 'Home',
                     'message' => "You aren't auth",
                 ];

                 View::renderTemplate('home.html', $data);
             }
         }
         else{
             $data = [
                 'language'=> 'en',
                 'title'   => 'Home',
                 'message' => "You aren't auth",
             ];

             View::renderTemplate('home.html', $data);
         }
        }
    }
}
