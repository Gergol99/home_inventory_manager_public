<?php

namespace App\Model;

use App\Model\ICrudDao;
use App\_utils\Database;

class ItemDao implements IcrudDao {
    public function getAll() {

    }
    
    public function getById($id) {

    }

    public function save() {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $categoryId = $_POST['item_category'];
        $measurementId = $_POST['measurement_name'];

        $db = new Database();
        $conn = $db->getConnection();
        $sql = "INSERT INTO `item` (`name`, `category_id`, `measurement_id`, `created_at`) VALUES 
        (:name, :category_id, :measurement_id, now());";
        $statement = $conn->prepare($sql);
        $statement->execute([
            ':name' => $name,
            ':category_id' => $categoryId,
            ':measurement_id' => $measurementId
        ]);

        $sql = "SELECT LAST_INSERT_ID() AS 'last_id';";
        $statement = $conn->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        $statement->execute();
        @session_start();
        $_SESSION['last_item_id'] = $statement->fetch()->last_id;
    }

    public function update() {

    }

    public function delete() {

    }
}