<?php

require_once 'App/_utils/Database.php';

use App\_utils\Database;

$db = new Database();
$conn = $db->getConnection();
$sql = "DELETE FROM `forgotten_password_token` WHERE TIMESTAMPDIFF(MINUTE, `created_at`, NOW()) > 30";
$statement = $conn->prepare($sql);
$statement->execute();