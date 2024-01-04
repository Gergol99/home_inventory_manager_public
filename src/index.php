<?php

require_once 'App/_utils/autoload.php';

use App\_utils\PermissionManager;
use App\Controller\IndexController;
use App\Controller\UserController;
use App\Controller\HouseholdController;
use App\Controller\InventoryController;
use App\Controller\ForgottenPasswordTokenController;
use App\Controller\InviteTokenController;
use App\Controller\UsersInHouseholdController;

@session_start();
isset($_SESSION['usr_level']) ? '' : $_SESSION['usr_level'] = "guest";

$currentController = isset($_GET['controller']) ? $_GET['controller'] : "index";
$currentAction = isset($_GET['action']) ? $_GET['action'] : "login";

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me_token'])) {
    $indexController = new IndexController();
    $indexController->loginWithCookie();
}

switch ($currentController) {
    case 'index':
        $controller = new IndexController();
        switch ($currentAction) {
            case 'login':
                if (PermissionManager::isPermitted('guest')) {
                    $controller->login();
                }
                break;
            case 'register':
                if (PermissionManager::isPermitted('guest')) {
                    $controller->register();
                }
                break;
            case 'logout':
                if (PermissionManager::IsPermitted('user')) {
                    $controller->logout();
                }
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'user':
        $controller = new UserController();
        switch ($currentAction) {
            case 'settings':
                $controller->list();
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'household':
        $controller = new HouseholdController();
        switch ($currentAction) {
            case 'add':
                if (PermissionManager::isPermitted('user')) {
                    $controller->add();
                }
                break;
            case 'list':
                if (PermissionManager::isPermitted('user')) {
                    if ($controller->isHouseholdAccessible()) {
                        $controller->list();
                    } else {
                        IndexController::loadNotFoundPage();
                    }
                }
                break;
            case 'invite':
                if (PermissionManager::isPermitted('user')) {
                    if ($controller->isOwnerOfHousehold()) {
                        $controller->inviteUserToHousehold();
                    } else {
                        IndexController::loadUnauthorizedWarningPage();
                    }
                }
                break;
            case 'delete':
                if (PermissionManager::isPermitted('user')) {
                    if ($controller->isOwnerOfHousehold()) {
                        $controller->delete();
                    } else {
                        IndexController::loadUnauthorizedWarningPage();
                    }
                }
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'inventory':
        $controller = new InventoryController();
        switch ($currentAction) {
            case 'add':
                if (PermissionManager::isPermitted('user')) {
                    $controller->add();
                }
                break;
            case 'add_via_shopping_list':
                if (PermissionManager::isPermitted('user')) {
                    $controller->addViaShoppingList();
                }
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'forgotten_password':
        $controller = new ForgottenPasswordTokenController();
        switch ($currentAction) {
            case 'request_reset':
                if (PermissionManager::isPermitted('guest')) {
                    $controller->loadRequestPage();
                }
                break;
            case 'reset':
                if (PermissionManager::isPermitted('guest')) {
                    $controller->passwordReset();
                }
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'invite':
        $controller = new InviteTokenController();
        switch ($currentAction) {
            case 'accept':
                $controller->handleJoin();
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    case 'users_in_household':
        $controller = new UsersInHouseholdController();
        switch ($currentAction) {
            case 'settings':
                if (PermissionManager::isPermitted('user')) {
                    $householdController = new HouseholdController();
                    if ($householdController->isHouseholdAccessible()) {
                        $controller->list();
                    } else {
                        IndexController::loadNotFoundPage();
                    }
                }
                break;
            default:
                IndexController::loadNotFoundPage();
                break;
        }
        break;
    default:
        IndexController::loadNotFoundPage();
        break;
}
