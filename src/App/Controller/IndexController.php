<?php

namespace App\Controller;

use App\Model\UserDao;
use App\Model\RememberMeTokenDao;
use App\Controller\InviteTokenController;

class IndexController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/index/{$view}.php";
        return $data;
    }

    public function register() {
        $usernameError = '';
        $emailError = '';

        if (isset($_POST['register'])) {
            $userDao = new UserDao();

            if ($userDao->usernameExists()) {
                $usernameError = "A megadott felhasználónév már létezik!";
            }

            if ($userDao->emailExists()) {
                $emailError = "A megadott email cím már létezik!";
            }

            if ($usernameError == '' && $emailError == '') {
                $userDao->save();
                @session_start();
                $_SESSION['usr_level'] = "user";
                $this->loadUserDataToSession();

                if (isset($_SESSION['join_token'])) {
                    $inviteJoinController = new InviteTokenController();
                    $inviteJoinController->handleJoin();
                }

                $url = 'index.php?controller=household&action=list';
                header('Location: ' . $url);
            }
        }
        $this->load("register", [
            'usernameError' => $usernameError,
            'emailError' => $emailError
        ]);
    }

    public function login() {
        $loginError = '';
        if (isset($_POST['login'])) {
            $userDao = new UserDao();
            if ($userDao->isLoginDataValid()) {
                @session_start();
                $_SESSION['usr_level'] = "user";
                $this->loadUserDataToSession();

                if (isset($_POST['remember_me'])) {
                    $rememberMeTokenDao = new RememberMeTokenDao();
                    $rememberMeTokenDao->save();
                }

                if (isset($_SESSION['join_token'])) {
                    $inviteJoinController = new InviteTokenController();
                    $inviteJoinController->handleJoin();
                }

                $url = 'index.php?controller=household&action=list';
                header('Location: ' . $url);
            } else {
                $loginError = "A megadott email cím vagy jelszó nem megfelelő!";
            }
        }

        $this->load("login", [
            'loginError' => $loginError,
        ]);
    }

    public function logout() {
        @session_start();
        $_SESSION = array();
        @session_destroy();
        @session_start();
        $_SESSION['usr_level'] = "guest";

        if (isset($_COOKIE['remember_me_token'])) {
            $rememberMeTokenDao = new RememberMeTokenDao();
            $rememberMeTokenDao->delete();
        }

        $url = 'index.php?controller=index&action=login';
        header('Location: ' . $url);
    }

    public static function loadNotFoundPage() {
        (new IndexController)->load("notFound", []);
    }

    public function loadUserDataToSession() {
        @session_start();
        $userDao = new UserDao();
        $userData = $userDao->getUserData();
        $_SESSION['user_id'] = $userData->id;
        $_SESSION['username'] = $userData->username;
    }

    public function loginWithCookie() {
        $userDao = new UserDao();
        $rememberMeTokenDao = new RememberMeTokenDao();
        @session_start();
        $_SESSION['usr_level'] = "user";

        $userId = $rememberMeTokenDao->getUserIdByToken($_COOKIE['remember_me_token']);
        if ($userId !== null) {
            $userData = $userDao->getById($userId);
            if ($userData) {
                $_SESSION['user_id'] = $userData->id;
                $_SESSION['username'] = $userData->username;
    
                $url = 'index.php?controller=household&action=list';
                header('Location: ' . $url);
            }
        }
    }

    public static function loadUnauthorizedWarningPage() {
        (new IndexController)->load("unauthorized", []);
    }
}
