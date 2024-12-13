<?php

require_once 'BaseRest.php';
require_once __DIR__ . '/../models/RegisterModel.php';

class RegisterRest extends BaseRest {
    public function create() {
        $input = $this->getRequestBody();

        // Validar datos obligatorios
        if (!isset($input['username']) || !isset($input['password']) || !isset($input['email'])) {
            $this->sendError('Username, password, and email are required', 400);
            return;
        }

        $username = $input['username'];
        $password = $input['password'];
        $email = $input['email'];

        try {
            // Llamar al modelo con el método correcto
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $success = RegisterModel::registerUser($username, $email, $hashedPassword);

            if ($success) {
                $this->sendResponse(['message' => 'User registered successfully'], 201);
            } else {
                $this->sendError('Failed to register user', 500);
            }
        } catch (Exception $e) {
            if ($e->getCode() === 23000) { // Código de error para claves duplicadas en MySQL
                $this->sendError('Username or email already exists', 409);
            } else {
                $this->sendError($e->getMessage(), 500);
            }
        }
    }
}

$registerRest = new RegisterRest();
URIDispatcher::getInstance()
->map("POST", "/register", array($registerRest,"create"));
