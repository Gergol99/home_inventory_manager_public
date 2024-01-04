<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;
use App\Model\UserDao;

class ForgottenPasswordTokenDao implements ICrudDao {
    public function getAll() {
    }

    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `forgotten_password_token` WHERE `user_id` = :user_id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':user_id' => $id,
        ]);
        return $statement->fetch();
    }

    public function save() {
        $userDao = new UserDao();
        $user = $userDao->getUserData();
        if ($user) {
            $token = bin2hex(random_bytes(32));

            $db = new Database();
            $conn = $db->getConnection();

            if ($this->getById($user->id)) {
                $sql = "UPDATE `forgotten_password_token` SET `token` = :token, `created_at` = now() WHERE `user_id` = :user_id; ";
            } else {
                $sql = "INSERT INTO `forgotten_password_token` (`user_id`, `token`, `created_at`) VALUES (:user_id, :token, now());";
            }

            $statement = $conn->prepare($sql);
            $statement->execute([
                ':user_id' => $user->id,
                ':token' => $token
            ]);
            $data = [
                'username' => $user->username,
                'email' => $user->email,
                'token' => $token
            ];
            return $data;
        }
        return null;
    }

    public function update() {
    }

    public function delete() {
        $token = $_GET['token'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `forgotten_password_token` WHERE `token` LIKE :token;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':token' => $token,
        ]);
    }

    public function getByToken($token) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `forgotten_password_token` WHERE `token` LIKE :token;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':token' => $token,
        ]);
        return $statement->fetch();
    }
}
