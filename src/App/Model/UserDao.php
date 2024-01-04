<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class UserDao implements IcrudDao {
    public function getAll() {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `id`, `username`, `email` FROM `user`;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `id`, `username`, `email` FROM `user` WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':id' => $id,
        ]);
        return $statement->fetch();
    }

    public function save() {
        $username = $_POST['username'];
        $email = strtolower($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `user` (`username`, `email`, `password`, `created_at`) 
        VALUES (:username, :email, :password, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
    }

    public function update() {
    }

    public function updateUserData() {
        @session_start();
        $id = $_SESSION['user_id'];
        $username = $_POST['username'];
        $email = strtolower($_POST['email']);

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `user` SET `username` = :username, `email` = :email, `updated_at` = now() WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
        ]);
    }

    public function updatePassword() {
        @session_start();
        $id = $_SESSION['user_id'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `user` SET `password` = :password, `updated_at` = now() WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
            ':password' => $password,
        ]);
    }

    public function delete() {
        @session_start();
        $id = $_SESSION['user_id'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "DELETE FROM `user` WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
        ]);
    }

    public function usernameExists() {
        $username = $_POST['username'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT count(*) AS 'username_count' FROM `user` WHERE `username` = :username;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':username' => $username,
        ]);
        return $statement->fetch()->username_count > 0 ? true : false;
    }

    public function emailExists() {
        $email = $_POST['email'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT count(*) AS 'email_count' FROM `user` WHERE `email` = :email;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':email' => $email,
        ]);
        return $statement->fetch()->email_count > 0 ? true : false;
    }

    public function isLoginDataValid() {
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT `password` FROM `user` WHERE `email` = :email;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':email' => $email,
        ]);
        $data = $statement->fetch();
        
        if (isset($data->password)) {
            $passwordHash = $data->password;
            return password_verify($password, $passwordHash);
        } else {
            return false;
        }
    }

    public function getUserData() {
        $email = strtolower($_POST['email']);

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `user` WHERE `email` = :email;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([':email' => $email]);
        return $statement->fetch();
    }

    public function resetPassword($id) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `user` SET `password` = :password, `updated_at` = now() WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':id' => $id,
            ':password' => $password
        ]);
    }
}