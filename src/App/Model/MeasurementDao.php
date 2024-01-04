<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class MeasurementDao implements IcrudDao {
    public function getAll() {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `measurement`;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `measurement` WHERE `id` = :id;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':id' => $id,
        ]);
        return $statement->fetch();
    }

    public function save() {
        // LATER -----------------------
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `measurement`;";
        $statement = $conn->prepare($sql);
        $statement->execute();
    }

    public function update() {

    }

    public function delete() {
        $user_id = null; // LATER -----------------------
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "UPDATE `measurement` (`deleted_at`=:now(), `deleted_by`=:user_id, `deleted`=1);";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':user_id' => $user_id
        ]);
    }
}