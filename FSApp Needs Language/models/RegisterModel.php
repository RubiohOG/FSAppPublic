<?php

require_once __DIR__ . '/../core/db_connection.php';

class RegisterModel {
    private $db;

    public static function userExists($username) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public static function registerUser($username, $email, $password) {
        global $db;
        $stmt = $db->prepare("INSERT INTO users (username, email, passwd) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $password]);
    }

    public static function emailExists($email) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}
