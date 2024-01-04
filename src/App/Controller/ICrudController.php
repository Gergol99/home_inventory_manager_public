<?php

namespace App\Controller;

interface ICrudController {
    public function list();
    public function add();
    public function updateById($id);
    public function deleteById($id);
}