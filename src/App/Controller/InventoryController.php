<?php

namespace App\Controller;

use App\Model\InventoryDao;
use App\Model\MeasurementDao;
use App\Model\ItemDao;
use App\Model\ItemCategoryDao;

class InventoryController implements ICrudController {
    public function load($view, $data = []) {
        extract($data);
        ob_start();
        include "App/View/inventory/{$view}.php";
        return $data;
    }

    public function list() {

    }

    public function add() {
        $measurementDao = new MeasurementDao();
        $measurements = $measurementDao->getAll();

        $itemCategoryDao = new ItemCategoryDao();
        $itemCategories = $itemCategoryDao->getAll();

        if (isset($_POST['save'])) {
            $itemDao = new ItemDao();
            $itemDao->save();
            $inventoryDao = new InventoryDao();
            $inventoryDao->save();
            $hid = $_GET['hid'];
            header("Location: index.php?controller=household&action=list&hid=$hid");
        }

        $this->load('add', [
            'measurements' => $measurements,
            'itemCategories' => $itemCategories
        ]);
    }

    public function updateById($id) {

    }

    public function deleteById($id) {

    }

    public function addViaShoppingList() {
        $measurementDao = new MeasurementDao();
        $measurements = $measurementDao->getAll();

        $itemCategoryDao = new ItemCategoryDao();
        $itemCategories = $itemCategoryDao->getAll();

        if (isset($_POST['save'])) {
            $itemDao = new ItemDao();
            $itemDao->save();
            $inventoryDao = new InventoryDao();
            $inventoryDao->shoppingListSave();
            $hid = $_GET['hid'];
            header("Location: index.php?controller=household&action=list&hid=$hid");
        }

        $this->load('addViaShoppingList', [
            'measurements' => $measurements,
            'itemCategories' => $itemCategories
        ]);
    }
}