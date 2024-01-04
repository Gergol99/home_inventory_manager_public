<?php

namespace App\Model;

use App\_utils\Database;

class ShoppingListDao {
    public function getAll() {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `shopping_list`";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getAllByHouseholdId($householdId) {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `shopping_list` WHERE `household_id` = :household_id";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute([
            ':household_id' => $householdId
        ]);
        return $statement->fetchAll();
    }
}