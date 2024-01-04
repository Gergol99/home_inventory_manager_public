<?php

namespace App\Model;

use App\_utils\Database;
use App\Model\ICrudDao;

class InviteTokenDao implements IcrudDao {
    public function getAll() {
    }

    public function getById($id) {
    }

    public function save() {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = password_hash($validator, PASSWORD_BCRYPT);
        $hid = $_GET['hid'];
        @session_start();
        $userId = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `invite_token` (`household_id`, `selector`, `hashed_validator`, `created_at`, `created_by`) VALUES 
        (:household_id, :selector, :hashed_validator, now(), :created_by);";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':selector' => $selector,
            ':hashed_validator' => $hashedValidator,
            ':household_id' => $hid,
            ':created_by' => $userId
        ]);

        return $selector . ':' . $validator;
    }

    public function update() {
    }

    public function delete() {
    }

    public function getHouseholdIdByToken($token) {
        $parts = explode(':', $token);
        $selector = $parts[0];
        $validator = $parts[1];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `invite_token` WHERE `selector` = :selector;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':selector' => $selector,
        ]);
        $data = $statement->fetch();
        if ($data) {
            if (password_verify($validator, $data->hashed_validator)) {
                return $data->household_id;
            }
            return null;
        }
        return null;
    }

    public function deleteByToken($token) {
        $parts = explode(':', $token);
        $selector = $parts[0];
        $validator = $parts[1];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `invite_token` WHERE `selector` = :selector;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':selector' => $selector,
        ]);
    }
}
