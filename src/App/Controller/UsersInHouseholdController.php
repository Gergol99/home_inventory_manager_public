<?php

namespace App\Controller;

use App\Controller\ICrudController;
use App\Model\UsersInHouseholdDao;
use App\Model\HouseholdDao;

class UsersInHouseholdController implements ICrudController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/users_in_household/{$view}.php";
        return $data;
    }

    public function list() {
        if (isset($_GET['hid'])) {
            $usersInHouseholdDao = new UsersInHouseholdDao();
            $householdDao = new HouseholdDao();

            $hid = $_GET['hid'];

            $users = $usersInHouseholdDao->getByHouseholdId($hid);
            $household = $householdDao->getById($hid);

            if (isset($_POST['remove_user'])) {
                $usersInHouseholdDao->removeUser();
                header('Location: ?controller=users_in_household&action=settings&hid='.$hid);
            }

            $this->load('settings', [
                'users' => $users,
                'household' => $household
            ]);
        } else {
            IndexController::loadNotFoundPage();
        }
    }

    public function add() {

    }

    public function updateById($id) {

    }

    public function deleteById($id) {

    }
}