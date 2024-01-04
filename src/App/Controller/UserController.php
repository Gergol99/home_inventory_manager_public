<?php

namespace App\Controller;

use App\Controller\ICrudController;
use App\Model\UserDao;
use App\Model\HouseholdDao;
use App\Model\UsersInHouseholdDao;
use App\Model\InventoryDao;

class UserController implements ICrudController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/user/{$view}.php";
        return $data;
    }

    public function list() {
        $userDao = new UserDao();
        @session_start();
        $user = $userDao->getById($_SESSION['user_id']);

        if (isset($_POST['update_user_data'])) {
            $userDao->updateUserData();
            header('Location: ?controller=user&action=settings');
        }

        if (isset($_POST['update_password'])) {
            $userDao->updatePassword();
            header('Location: ?controller=user&action=settings');
        }

        if (isset($_POST['delete_profile'])) {
            @session_start();
            $userId = $_SESSION['user_id'];
            $householdDao = new HouseholdDao();
            $usersInHouseholdDao = new UsersInHouseholdDao();
            $inventoryDao = new InventoryDao();

            $userDao->delete();
            $ownedHouseholds = $householdDao->getByOwner($userId);
            $householdDao->deleteByOwner($userId);
            $usersInHouseholdDao->deleteByUserId($userId);
            foreach ($ownedHouseholds as $ownedHousehold) {
                $inventoryDao->deleteByHousehold($ownedHousehold->id);
            }

            @session_start();
            $_SESSION = array();
            @session_destroy();
            @session_start();
            $_SESSION['usr_level'] = "guest";

            $url = 'index.php?controller=index&action=login';
            header('Location: ' . $url);
        }

        $this->load('settings', [
            'user' => $user
        ]);
    }

    public function add() {
    }

    public function updateById($id) {
    }

    public function deleteById($id) {
    }
}
