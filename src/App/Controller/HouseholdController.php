<?php

namespace App\Controller;

use App\Controller\ICrudController;
use App\Model\HouseholdDao;
use App\Model\UsersInHouseholdDao;
use App\Model\InventoryDao;
use App\Model\MeasurementDao;
use App\Model\ShoppingListDao;
use App\Model\InviteTokenDao;
use App\_utils\Mail;
use App\Model\UserDao;

class HouseholdController implements ICrudController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/household/{$view}.php";
        return $data;
    }

    public function list() {
        $householdDao = new HouseholdDao();
        $inventoryDao = new InventoryDao();
        $measurementDao = new MeasurementDao();
        $shoppingListDao = new ShoppingListDao();

        $households = $householdDao->getByCurrentUser();
        $measurements = $measurementDao->getAll();
        $currentHousehold = null;
        $inventories = null;
        $householdId = null;
        $shoppingList = null;

        if (isset($_GET['hid'])) {
            $householdId = $_GET['hid'];
            $currentHousehold = $householdDao->getById($householdId);
            $inventories = $inventoryDao->getAllByHouseholdId($householdId);
            $shoppingList = $shoppingListDao->getAllByHouseholdId($householdId);
        } else if (isset($households) && count($households) > 0) {
            $householdId = $households[0]->household_id;
            $currentHousehold = $householdDao->getById($households[0]->household_id);
            $inventories = $inventoryDao->getAllByHouseholdId($householdId);
            $shoppingList = $shoppingListDao->getAllByHouseholdId($householdId);
        }

        if (isset($_POST['update_household_name'])) {
            $householdDao->updateName($householdId);
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['update_item_name'])) {
            $inventoryDao->updateItemName();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['update_item_quantity'])) {
            $inventoryDao->updateQuantityAndMeasurement();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['disable_important'])) {
            $inventoryDao->disableImportant();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['update_item_important'])) {
            $inventoryDao->enableImportent();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['delete_item'])) {
            $inventoryDao->delete();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['add_to_shopping_list'])) {
            $inventoryDao->addToShoppingList();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['remove_from_shopping_list'])) {
            $inventoryDao->removeFromShoppingList();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['remove_important_from_shopping_list'])) {
            $inventoryDao->removeImportantFromShoppingList();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['complete_shopping_list_item'])) {
            $inventoryDao->completeShoppingListItem();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        if (isset($_POST['update_shopping_list_req_quantity'])) {
            $inventoryDao->updateReqQuantity();
            header("Location: index.php?controller=household&action=list&hid=$householdId");
        }

        $this->load('list', [
            'households' => $households,
            'currentHousehold' => $currentHousehold,
            'inventories' => $inventories,
            'measurements' => $measurements,
            'shoppingList' => $shoppingList
        ]);
    }

    public function add() {
        if (isset($_POST['save'])) {
            $householdDao = new HouseholdDao();
            $householdDao->save();

            $usersInHouseholdDao = new UsersInHouseholdDao();
            $usersInHouseholdDao->save();

            header("Location: index.php?controller=household&action=list&hid=" . $_SESSION['household_id']);
        }
        $this->load("newHousehold", []);
    }

    public function updateById($id) {
    }

    public function deleteById($id) {
    }

    // Only responsible for the permission not the actual existence of the hid
    public function isHouseholdAccessible() {
        if (isset($_GET['hid'])) {
            $hid = $_GET['hid'];
            $usersInHouseholdDao = new UsersInHouseholdDao();
            return $usersInHouseholdDao->isUserInHousehold($hid);
        }
        return true;
    }

    public function inviteUserToHousehold() {
        if (isset($_POST['share_invite'])) {
            @session_start();
            $inviteTokenDao = new InviteTokenDao();
            $mail = new Mail();
            $userDao = new UserDao();
            $token = $inviteTokenDao->save();

            $to = $_POST['email'];
            $from = "noreply.haztartasileltarprogram@example.com";
            $subject = "Meghívó";

            $targetUser = $userDao->getUserData();
            $targetUsername = '';
            if ($targetUser) {
                $targetUsername = $targetUser->username;
            } else {
                $targetUsername = "új felhasználó";
            }
            $message = "Kedves " . $targetUsername . "!\r\n\r\n" .
                "Azért kapta ezt az üzenetet mert a(z) " . $_SESSION['username'] . " nevü felhasználó meghívta, hogy csatlakozzon a 'háztartás'-ához. " .
                "A következő linkre kattintva ezt meg is teheti: http://127.0.0.1?controller=invite&action=accept&token=" . $token . "\r\n\r\n" .
                "Amennyiben nem szeretné elfogadni nyugodtan hagyja figyelmen kívűl ezt az üzenetet.";

            $result = $mail->send($to, $from, $subject, $message);
            if ($result) {
                header('Location: ?controller=household&action=invite&status=success');
            } else {
                header('Location: ?controller=household&action=invite&status=failure');
            }
        }

        $this->load("inviteUser", []);
    }

    public function isOwnerOfHousehold() {
        if (isset($_GET['hid'])) {
            $hid = $_GET['hid'];
            $householdDao = new HouseholdDao();
            return $householdDao->isOwner($hid);
        }
        return true;
    }

    public function delete() {
        if (isset($_POST['delete_household'])) {
            $householdDao = new householdDao();
            $householdDao->delete();
        }
        header('Location: ?controller=household&action=list');
    }
}
