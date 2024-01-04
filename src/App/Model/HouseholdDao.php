<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class HouseholdDao implements IcrudDao {
    public function getAll() {

    }
    
    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `household` WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':id' => $id,
        ]);
        return $statement->fetch();
    }

    public function save() {
        @session_start();
        $name = $_POST['name'];
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `household` (`name`, `admin_user_id`, `created_at`, `created_by`) VALUES 
        (:name, :user_id, now(), :user_id);";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':name' => $name,
            ':user_id' => $userId
        ]);

        $sql = "SELECT LAST_INSERT_ID() AS 'last_id';";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        $_SESSION['household_id'] = $statement->fetch()->last_id;
    }

    public function update() {

    }

    public function delete() {
        $hid = $_GET['hid'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `household` SET `deleted` = 1, `deleted_at` = now(), `deleted_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $hid,
            ':user_id' => $userId
        ]);
    }

    public function getByCurrentUser() {
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `u_i_h`.`household_id`, `h`.`name` AS 'household_name' FROM `household` AS `h` 
        INNER JOIN `users_in_household` AS `u_i_h` ON `h`.`id` = `u_i_h`.`household_id` 
        WHERE `u_i_h`.`deleted` = 0 AND `u_i_h`.`user_id` = :user_id AND `h`.`deleted` = 0;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':user_id' => $userId,
        ]);
        return $statement->fetchAll();
    }

    public function updateName($id) {
        $householdName = $_POST['household_name_input'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `household` SET `name` = :name, `updated_at` = now(), `updated_by` = :user_id WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
            ':name' => $householdName,
            'user_id' => $userId
        ]);
    }

    public function deleteByOwner($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `household` WHERE `admin_user_id` = :admin_user_id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':admin_user_id' => $id,
        ]);
    }

    public function getByOwner($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `household` WHERE `admin_user_id` = :admin_user_id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':admin_user_id' => $id,
        ]);
        return $statement->fetchAll();
    }

    public function isOwner($hid) {
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `household` WHERE `admin_user_id` = :user_id AND `id` = :hid;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':user_id' => $userId,
            ':hid' => $hid
        ]);
        if ($statement->fetch()) {
            return true;
        }
        return false;
    }
}