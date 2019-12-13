<?php

namespace App\Controllers;
session_start();

use \Core\Controller;
use \Core\View;
use \App\Models\Users;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
     * Auth controller
     *
     * PHP version 7.0
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
        if (isset( $_POST['Submit'] ) and $_POST['Password'] === $_POST['Confirm_Password']){
            return $this->registerAction();
        }
        elseif ( isset($_SESSION['Login_ID']) ){
            header("Location: /");
        }
        elseif ( isset($_POST['Login']) ){
            if (  Auth::loginAction() ){
                header("Location: /");
            }
            elseif ( !Auth::loginAction() ){
                if ( isset($_SESSION['Language']) ){
                    if ($_SESSION['Language'] == 'en'){
                        $data = [
                            'language' => 'en',
                            'title'   => 'Auth',
                            'message' => 'Please activate your account',
                        ];

                        View::renderTemplate('auth.html', $data);
                    }
                    elseif ($_SESSION['Language'] == 'ru'){
                        $data = [
                            'language' => 'ru',
                            'title'   => 'Авторизация',
                            'message' => 'Пожалуйста, активируйте свой аккаунт',
                        ];

                        View::renderTemplate('auth.html', $data);
                    }
                }
                else{
                    $data = [
                        'language' => 'en',
                        'title'    => 'Auth',
                        'message'  => 'Please activate your account',
                    ];

                    View::renderTemplate('auth.html', $data);
                }
            }
            else{
                header("Location: /auth");
            }
        }
        else{
            if (isset($_SESSION['Language'])){
                if ( $_SESSION['Language'] == 'ru') {
                    $data =[
                        'language' => 'ru',
                        'title'    => 'Авторизация',
                    ];

                    View::renderTemplate('auth.html', $data);
                }
                elseif ( $_SESSION['Language'] == 'en' ){
                    $data = [
                        'language'=> 'en',
                        'title'   => 'Auth',
                    ];

                    View::renderTemplate('auth.html', $data);
                }
            }
            else{
                $data = [
                    'language'=> 'en',
                    'title'   => 'Auth',
                ];

                View::renderTemplate('auth.html', $data);
            }
        }
    }


    /**
     * Activation after register user
     *
     * @return void
     */
    public function activateAction(){

        if ( isset($_GET['activation_key']) and $_GET['activation_key'] == Users::getActivationKey($_GET['activation_key'])[0]['Activation_Key']  ){
            if (isset($_SESSION['Language'])){
                if ($_SESSION['Language'] == 'en') {
                    $data = [
                        'language' => 'en',
                        'auth' => "You is an auth",
                        'message' => "Your account is activated",
                        'home' => "Go to home",
                    ];

                    Users::UpdateStatus($_GET['activation_key']);

                    $_SESSION['Login_ID'] = Users::getUser_eMail_Where('Activation_Key', $_GET['activation_key'])[0]['eMail'];


                    View::renderTemplate('activation.html', $data);
                }
                if ($_SESSION['Language'] == 'ru') {
                    $data = [
                        'language' => 'ru',
                        'auth' => "Поздравляем, вы авторизованы",
                        'message' => "Ваша аккаунт активирован.",
                        'home' => "Перейти на главную",
                    ];

                    Users::UpdateStatus($_GET['activation_key']);

                    $_SESSION['Login_ID'] = Users::getUser_eMail_Where('Activation_Key', $_GET['activation_key'])[0]['eMail'];


                    View::renderTemplate('activation.html', $data);
                }
            }
        }
        else{
            $data = [
                'language' => 'en',
                'auth' => "You aren't auth",
                'message' => "Your account isn't activated",
            ];

            View::renderTemplate('activation.html', $data);
        }
    }


    public function logoutAction(){
        \session_destroy();
        unset($_SESSION['Login_ID']);
        \header("Location: /");
    }


    /**
     * Register action
     *
     * @return void
     */
    public function registerAction()
    {
        if ($_POST['Password'] === $_POST['Confirm_Password']) {
            $data = [
                'First_Name' => $_POST['First_Name'],
                'Last_Name' => $_POST['Last_Name'],
                'eMail' => $_POST['eMail'],
                'File' => $_FILES['File']['name'],
                'Password' => md5($_POST['Password']),
                'Activation_key' => md5($_POST['eMail']),
            ];

            if ( $_FILES['File'] ) {

                $file_type = $_FILES['File']['type']; //returns the mimetype

                $allowed = array("image/jpeg", "image/gif", "image/png");
                if (!in_array($file_type, $allowed)) {
                    $error_message = 'Only jpg, gif, and png files are allowed.';

                    echo $error_message;

                    exit();

                } else {
                    $upload_dir = __DIR__ . '/../../public/upload/';
                    $upload_file = $upload_dir . basename($_FILES['File']['name']);
                    move_uploaded_file($_FILES['File']['tmp_name'], $upload_file);
                }


                $Activation_URL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/activate?" . "activation_key=" . md5($_POST['eMail']);
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->SMTPSecure = 'ssl';                            // secure transfer enabled REQUIRED for GMail
                    $mail->Port = 465;                                    // TCP port to connect to
                    $mail->Username = 'User_Name@gmail.com';              // SMTP username
                    $mail->Password = 'Password';                         // SMTP password

                    //Recipients
                    $mail->setFrom('User_Name@gmail.com', 'Admin');
                    $mail->addAddress($_POST['eMail']);                   // Name is optional

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Activation Key';
                    $mail->Body = "For activate an account please press the button <b><a href='$Activation_URL'> Activate an Account </a></b>";

                    $mail->send();

                } catch (Exception $e) {
                    return $mail->ErrorInfo;
                }


                if (Users::setValues($data)) {
                    $Status = Users::getStatus($_POST['eMail'])[0]['Status'];
                    $_SESSION['Waiting_to_Activation'] = $Status;
//
                    return header("Location: /");
                } else {
                    return Auth::indexAction();
                }


            } else {
                return Auth::indexAction();
            }
        }
    }


    /**
     * check login user
     *
     * @return void
     */
    static public function loginAction()
    {
        if ( isset($_SESSION['Login_ID'])){
            return true;
        }
        elseif ( isset($_POST['login']) and isset($_POST['password']) ){
            if ( sizeof(Users::getStatus($_POST['login'])) != 0)
            {
                $Status =  Users::getStatus( $_POST['login'] )[0]['Status'];
            }
            else{
                $Status = empty($Status);
            }
//
//            var_dump( Users::getStatus( $_POST['login'] ) );
//            exit;

            if ( !empty($Status) and $Status === 'activate' ){
                $Login = Users::getUser_eMail_Where('eMail', $_POST['login']);
                $Password = Users::getUser_Password( $Login[0]['eMail'] );

                if ($Login[0]['eMail'] == $_POST['login'] and $Password[0]['Password'] == md5($_POST['password'])){

                    $_SESSION['Login_ID'] = $Login[0]['eMail'];

                    return true;
                }
                else{
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
}
