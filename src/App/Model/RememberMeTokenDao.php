<?php

namespace App\Model;

use App\_utils\Database;
use App\Model\ICrudDao;

class RememberMeTokenDao implements IcrudDao {
    public function getAll() {

    }

    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `remember_me_token` WHERE `user_id` = :user_id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':user_id' => $id,
        ]);
        return $statement->fetch();
    }

    public function save() {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = password_hash($validator, PASSWORD_BCRYPT);
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `remember_me_token` (`user_id`, `selector`, `hashed_validator`, `created_at`) VALUES 
        (:user_id, :selector, :hashed_validator, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $userId,
            ':selector' => $selector,
            ':hashed_validator' => $hashedValidator
        ]);

        $token = $selector . ':' . $validator;
        setcookie('remember_me_token', $token, time() + (86400 * 7), "/");
    }

    public function update() {
    }

    public function delete() {
        $parts = explode(':', $_COOKIE['remember_me_token']);
        $selector = $parts[0];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `remember_me_token` WHERE `selector` LIKE :selector;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':selector' => $selector,
        ]);

        setcookie("remember_me_token", "", time() - 3600);
    }

    public function getUserIdByToken($token) {
        $parts = explode(':', $token);
        $selector = $parts[0];
        $validator = $parts[1];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `remember_me_token` WHERE `selector` LIKE :selector;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':selector' => $selector,
        ]);
        $result = $statement->fetch();

        if (password_verify($validator, $result->hashed_validator)) {
            return $result->user_id;
        }
        return null;
    }
}