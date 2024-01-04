<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class UsersInHouseholdDao implements IcrudDao {
    public function getAll() {

    }
    
    public function getById($id) {

    }

    public function save() {
        @session_start();
        $userId = $_SESSION['user_id'];
        $householdId = $_SESSION['household_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `users_in_household` (`user_id`, `household_id`, `created_at`) VALUES 
        (:user_id, :household_id, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':household_id' => $householdId
        ]);
    }

    public function update() {

    }

    public function delete() {

    }

    public function isUserInHousehold($hid) {
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `users_in_household` WHERE `user_id` = :user_id AND `household_id` = :household_id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':user_id' => $userId,
            ':household_id' => $hid
        ]);
        return isset($statement->fetch()->id);
    }

    public function deleteByUserId($id) {
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `users_in_household` WHERE `user_id` = :user_id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $id,
        ]);
    }

    public function getByHouseholdId($hid) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `user`.`username`, `u_i_h`.`household_id`, `u_i_h`.`user_id` 
        FROM `users_in_household` AS `u_i_h` INNER JOIN `user` ON `u_i_h`.`user_id` = `user`.`id` 
        WHERE `household_id` = :household_id AND `deleted` = 0;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':household_id' => $hid,
        ]);
        return $statement->fetchAll();
    }

    public function removeUser() {
        $targetId = $_POST['target_user_id'];
        $hid = $_GET['hid'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `users_in_household` WHERE `user_id` = :target_id AND `household_id` = :household_id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':target_id' => $targetId,
            ':household_id' => $hid
        ]);
    }
}