<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class ItemCategoryDao implements IcrudDao {
    public function getAll() {
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "SELECT * FROM `item_category`;";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function getById($id) {

    }

    public function save() {

    }

    public function update() {

    }

    public function delete() {

    }
}