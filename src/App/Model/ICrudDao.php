<?php

namespace App\Model;

interface ICrudDao {
    public function getAll();
    public function getById($id);
    public function save();
    public function update();
    public function delete();
}