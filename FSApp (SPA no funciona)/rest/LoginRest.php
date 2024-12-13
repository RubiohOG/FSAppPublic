<?php
require_once 'BaseRest.php';
require_once __DIR__ . '/../models/LoginModel.php';

class LoginRest extends BaseRest {
    /**
     * POST /rest/login
     * Maneja la autenticación del usuario
     */
    public function checkLogin() {
        $input = $this->getRequestBody();
        $username = $input['username'] ?? null;
        $password = $input['password'] ?? null;

        if (!$username || !$password) {
            $this->sendError('Username and password are required', 400);
            return;
        }

        if (LoginModel::checkLogin($username, $password)) {
            $token = base64_encode("$username:" . uniqid());

            $this->sendResponse([
                'message' => 'Login successful',
                'token' => $token,
                'username' => $username
            ]);
        } else {
            $this->sendError('Invalid username or password', 401);
        }
    }

    public function logOut() {
        $this->sendResponse(['message' => 'Logout successful']);
    }

    public function readAll() {
        $this->sendResponse(['message' => 'User is authenticated'], 200);
    }
}

// URI-MAPPING for this Rest endpoint
$loginRest = new LoginRest();
URIDispatcher::getInstance()
->map("GET", "/login", array($loginRest,"checkLogin"));
