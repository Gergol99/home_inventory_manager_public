<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class InventoryDao implements IcrudDao {
    public function getAll() {
    }

    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `inventory` WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':id' => $id
        ]);
        return $statement->fetch();
    }

    public function save() {
        @session_start();
        $householdId = $_GET['hid'];
        $itemId = $_SESSION['last_item_id'];
        $quantity = $_POST['quantity'];
        if (isset($_POST['important'])) {
            $important = 1;
            $minQuantity = $_POST['min_quantity'];
        } else {
            $important = 0;
            $minQuantity = null;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `inventory` (`household_id`, `item_id`, `quantity`, `important`, `min_quantity`, `created_at`) 
        VALUES (:household_id, :item_id, :quantity, :important, :min_quantity, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':household_id' => $householdId,
            ':item_id' => $itemId,
            ':quantity' => $quantity,
            ':important' => $important,
            ':min_quantity' => $minQuantity
        ]);
    }

    public function shoppingListSave() {
        @session_start();
        $householdId = $_GET['hid'];
        $itemId = $_SESSION['last_item_id'];
        $reqQuantity = $_POST['req_quantity'];
        if (isset($_POST['important'])) {
            $important = 1;
            $minQuantity = $_POST['min_quantity'];
        } else {
            $important = 0;
            $minQuantity = null;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `inventory` (`household_id`, `item_id`, `quantity`, `req_quantity`, `important`, `in_shopping_list`, `min_quantity`, `created_at`) 
        VALUES (:household_id, :item_id, 0, :req_quantity, :important, 1, :min_quantity, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':household_id' => $householdId,
            ':item_id' => $itemId,
            ':req_quantity' => $reqQuantity,
            ':important' => $important,
            ':min_quantity' => $minQuantity
        ]);
    }

    public function update() {
    }

    public function delete() {
        $id = $_POST['item_delete_id'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `deleted` = 1, `deleted_at` = NOW(), `deleted_by` = :user_id 
        WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
    }

    public function getAllByHouseholdId($householdId) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `i`.`id`, `i`.`quantity`, `i`.`important`, `i`.`min_quantity`, `item`.`name`, 
        `item_c`.`name` AS `category_name`, `m`.`name` AS `measurement_name`, `m`.`id` AS `measurement_id` 
        FROM `inventory` AS `i`
        INNER JOIN `item` ON `i`.`item_id` = `item`.`id` 
        INNER JOIN `item_category` AS `item_c` ON `item`.`category_id` = `item_c`.`id` 
        INNER JOIN `measurement` AS `m` ON `item`.`measurement_id` = `m`.`id` 
        WHERE `household_id` = :household_id AND `i`.`deleted` = 0;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':household_id' => $householdId
        ]);
        return $statement->fetchAll();
    }

    public function updateItemName() {
        $id = $_POST['item_name_id'];
        $name = $_POST['item_name'];
        $itemId = $this->getById($id)->item_id;
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `item` SET `name` = :name, `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :item_id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':name' => $name,
            'item_id' => $itemId,
            ':user_id' => $userId
        ]);
    }

    public function updateQuantityAndMeasurement() {
        $id = $_POST['item_quantity_id'];
        $measurementId = $_POST['measurement_name'];
        $quantity = $_POST['item_quantity'];
        $itemId = $this->getById($id)->item_id;
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `quantity` = :quantity, `updated_at` = now(), `updated_by` = :user_id 
        WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':quantity' => $quantity,
            ':user_id' => $userId,
            ':id' => $id
        ]);

        $sql = "UPDATE `item` SET `measurement_id` = :measurement_id, `updated_at` = now(), `updated_by` = :user_id 
        WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':measurement_id' => $measurementId,
            ':user_id' => $userId,
            ':id' => $itemId
        ]);
    }

    public function disableImportant() {
        $id = $_POST['inventory_id'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `important` = 0, `updated_at` = now(), `updated_by` = :user_id 
        WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id
        ]);
    }

    public function enableImportent() {
        $id = $_POST['item_important_id'];
        $minQuantity = $_POST['item_min_quantity'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `important` = 1, `min_quantity` = :min_quantity, 
        `req_quantity` = :min_quantity, `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id,
            ':min_quantity' => $minQuantity,
        ]);
    }

    public function addToShoppingList() {
        $id = $_POST['item_shopping_list_id'];
        $reqQuantity = $_POST['item_req_quantity'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `in_shopping_list` = 1, `req_quantity` = :req_quantity, 
        `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id,
            ':req_quantity' => $reqQuantity
        ]);
    }

    public function removeFromShoppingList() {
        $id = $_POST['inventory_id'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `in_shopping_list` = 0, `req_quantity` = 0, `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id
        ]);
    }

    public function removeImportantFromShoppingList() {
        $id = $_POST['shopping_list_id'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `in_shopping_list` = 0, `important` = 0, `req_quantity` = 0, 
        `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id
        ]);
    }

    public function completeShoppingListItem() {
        $id = $_POST['inventory_id'];
        $item = $this->getById($id);
        $newQuantity = $item->req_quantity + $item->quantity;
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `in_shopping_list` = 0, `quantity` = :new_quantity, `req_quantity` = 0, 
        `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id,
            ':new_quantity' => $newQuantity,
        ]);
    }

    public function deleteByHousehold($hid) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `inventory` WHERE `household_id` = :household_id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':household_id' => $hid,
        ]);
    }

    public function updateReqQuantity() {
        $id = $_POST['shopping_list_req_quantity_id'];
        $reqQuantity = $_POST['shopping_list_req_quantity'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `inventory` SET `req_quantity` = :req_quantity, `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':id' => $id,
            ':req_quantity' => $reqQuantity,
        ]);
    }
}
