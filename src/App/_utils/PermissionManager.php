<?php

namespace App\_utils;

class PermissionManager {
    public static function IsPermitted($requiredLevel) {
        @session_start();

        if (!isset($_SESSION['usr_level'])) {
            return false;
        }

        if ($requiredLevel === "guest") {
            if ($_SESSION['usr_level'] !== "guest") {
                header('Location: index.php?controller=household&action=list');
                return false;
            }
            return true;
        } else if ($requiredLevel === "user") {
            if ($_SESSION['usr_level'] !== "user") {
                header('Location: index.php?controller=index&action=login');
                return false;
            }
            return true;
        } else if ($requiredLevel === "admin") {
            if ($_SESSION['usr_level'] !== "admin") {
                return false;
            }
            return true;
        }
        return false;
    }
}