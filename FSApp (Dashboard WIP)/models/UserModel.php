<?php
// models/UserModel.php
require_once __DIR__ . '/../core/db_connection.php';
//require_once(__DIR__."/../core/ValidationException.php");

class UserModel {

    //Obtener un usuario por su email
    public function getUserByEmail($email) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
