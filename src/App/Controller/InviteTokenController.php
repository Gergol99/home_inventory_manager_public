<?php

namespace App\Controller;

use App\_utils\Database;
use App\Controller\ICrudController;
use App\Model\InviteTokenDao;
use App\Model\UsersInHouseholdDao;

class InviteTokenController implements ICrudController {
    public function list() {
    }

    public function add() {
    }

    public function updateById($id) {
    }

    public function deleteById($id) {
    }

    public function handleJoin() {
        @session_start();
        if ($_SESSION['usr_level'] === 'user') {
            $inviteTokenDao =new InviteTokenDao();
            $usersInHouseholdDao =new UsersInHouseholdDao();

            $token = '';
            if (isset($_GET['token'])) {
                $token = $_GET['token'];
            } else {
                $token = $_SESSION['join_token'];
            }

            $householdId = $inviteTokenDao->getHouseholdIdByToken($token);
            $inviteTokenDao->deleteByToken($token);
            unset($_SESSION['join_token']);

            if ($householdId !== null) {
                $_SESSION['household_id'] = $householdId;
                if (!$usersInHouseholdDao->isUserInHousehold($householdId)) {
                    $usersInHouseholdDao->save();
                }
                header("Location: ?controller=household&action=list&hid=$householdId");
            } else {
                IndexController::loadNotFoundPage();
            }
        } else if ($_SESSION['usr_level'] === 'guest') {
            $_SESSION['join_token'] = $_GET['token'];
            header('Location: ?controller=index&action=login');
        }
    }
}
