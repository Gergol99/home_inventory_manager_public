<?php

namespace App\Controller;

use App\Controller\ICrudController;
use App\Model\ForgottenPasswordTokenDao;
use App\Model\UserDao;
use App\_utils\Mail;

class ForgottenPasswordTokenController implements ICrudController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/forgotten_password/{$view}.php";
        return $data;
    }

    public function list() {
    }

    public function add() {
    }

    public function updateById($id) {
    }

    public function deleteById($id) {
    }

    public function loadRequestPage() {
        $forgottenPasswordTokenDao = new forgottenPasswordTokenDao();

        if (isset($_POST['request_reset'])) {
            $saveResult = $forgottenPasswordTokenDao->save();
            if ($saveResult !== null) {
                $message = "Kedves ". $saveResult['username'] . "!\r\n\r\n" .
                "Azért kapta ezt az email-t mert jelszó visszaállítást kért. A következő linkre kattintva megadhatja új jelszavát:\r\n" .
                "http://127.0.0.1/index.php?controller=forgotten_password&action=reset&token=" . $saveResult['token'] . "\r\n\r\n" .
                "Amennyiben nem ön igényelte a jelszóvisszaállítást nyugodtan hagyja figyelmen kívül ezt az email-t.\r\n\r\n" .
                "Kérem ne válaszóljon erre az email-re.";

                $subject = "Jelszó visszaálítás";

                $mail = new Mail();
                $mail->send($saveResult['email'], 'noreply.haztartasileltarprogram@example.com', $subject, $message);

                header("Location: ?controller=forgotten_password&action=request_reset&status=success");
            } else {
                header("Location: ?controller=forgotten_password&action=request_reset&status=failure");
            }
        }

        $this->load('emailRequest', []);
    }

    public function passwordReset() {
        $forgottenPasswordTokenDao = new forgottenPasswordTokenDao();
        $token = null;
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = $forgottenPasswordTokenDao->getByToken($token);
            if ($user) {
                if (isset($_POST['reset_password'])) {
                    $userDao = new UserDao();
                    $userDao->resetPassword($user->user_id);
                    $forgottenPasswordTokenDao->delete();
                    header("Location: ?controller=index&action=login");
                }

                $this->load('passwordReset', []);
            } else {
                IndexController::loadNotFoundPage();
            }
        } else {
            IndexController::loadNotFoundPage();
        }
    }
}
