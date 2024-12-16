<?php

require_once __DIR__ . '/../core/db_connection.php';

class LoginModel {
    private $db;

    public static function checkLogin($username, $password) {
        global $db;

        $stmt = $db->prepare("SELECT passwd FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['passwd'])) {
            return true;
        }
        return false;
    }
}
