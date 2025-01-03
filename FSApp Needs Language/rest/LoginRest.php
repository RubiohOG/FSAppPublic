<?php
require_once 'BaseRest.php';
require_once __DIR__ . '/../models/LoginModel.php';

class LoginRest extends BaseRest {
    /**
     * GET /rest/login
     * 
     */
    /*
    public function checkLogin() {
        
        $input = json_decode(file_get_contents('php://input'), true);
    
        $username = $input['username'] ?? null;
        $password = $input['password'] ?? null;
    
        if (!$username || !$password) {
            $this->sendError('Username and password are required', 400);
            return;
        }
    
        // Validar usuario y contraseña
        if (LoginModel::checkLogin($username, $password)) {
            $this->sendResponse([
                'message' => 'Login successful',
                'username' => $username
            ]);
        } else {
            $this->sendError('Invalid username or password', 401);
        }
    }*/

    public function checkLogin() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->sendError('Authorization header is missing', 400);
            return;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($authHeader, 'Basic ') === 0) {
            $encodedCredentials = substr($authHeader, 6);
            $decodedCredentials = base64_decode($encodedCredentials);
            list($username, $password) = explode(':', $decodedCredentials, 2);

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
        } else {
            $this->sendError('Invalid Authorization format', 400);
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
    ->map("GET", "/login", array($loginRest, "checkLogin"));
