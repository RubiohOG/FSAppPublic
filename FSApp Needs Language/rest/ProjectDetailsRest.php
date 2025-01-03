<?php

require_once 'BaseRest.php';
require_once __DIR__ . '/../models/ProjectDetailsModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class ProjectDetailsRest extends BaseRest {

    /*
     * GET /project-details/{id}
     * Obtener los detalles de un proyecto
     */
    public function read($projectId) {
        $headers = getallheaders();
        $username = $headers['Authorization'] ?? null;

        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }

        $projectModel = new ProjectDetailsModel();

        try {
            $projectDetails = $projectModel->getProjectDetails($projectId);
            $payments = $projectModel->getPayments($projectId);
            $debts = $projectModel->getDebts($projectId);
            $users = $projectModel->getProjectUsers($projectId);

            if (!$projectDetails) {
                $this->sendError('Project not found', 404);
                return;
            }

            $this->sendResponse([
                'project' => $projectDetails,
                'payments' => $payments,
                'debts' => $debts,
                'users' => $users
            ]);
        } catch (Exception $e) {
            $this->sendError('Error retrieving project details: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /project-details/{id}/add-user
     * Agregar un usuario a un proyecto
     */
    public function addUser($projectId) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;
        
        // Decodificar el encabezado Authorization
        $username = null;
        if ($authHeader && preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            $decoded = base64_decode($matches[1]);
            list($username, $password) = explode(':', $decoded, 2);
        }
        //$this->sendResponse($username);
        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            //$this->sendError($username, 401);
            return;
        }

        $input = $this->getRequestBody();
        $email = $input['email'] ?? null;

        if (!$projectId || !$email) {
            $this->sendError('Project ID and email are required', 400);
            return;
        }

        $projectModel = new ProjectDetailsModel();
        $userModel = new UserModel();

        try {
            $user = $userModel->getUserByEmail($email);

            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }

            $userExists = $projectModel->userExistsInProject($projectId, $user['username']);
            if ($userExists) {
                $this->sendError('User already exists in the project', 409);
                return;
            }

            $projectModel->addUserToProject($projectId, $user['username']);
            $this->sendResponse(['message' => 'User added to project successfully'], 201);
        } catch (Exception $e) {
            $this->sendError('Error adding user to project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /project-details/{id}/add-payment
     * Agregar un pago a un proyecto
     */
    public function createPayment($projectId) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        // Decodificar el encabezado Authorization
        $username = null;
        if ($authHeader && preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            $decoded = base64_decode($matches[1]);
            list($username, $password) = explode(':', $decoded, 2);
        }

        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }

        $input = $this->getRequestBody();
        //$projectId = $input['project_id'] ?? null;
        $payerUsername = $input['payer'] ?? null;
        $paymentName = $input['paymentName'] ?? null;
        $amount = $input['amount'] ?? null;
        $beneficiaries = $input['beneficiaries'] ?? [];

        if (!$projectId || !$paymentName || !$amount || empty($beneficiaries)) {
            $this->sendError('All payment details are required', 400);
            $this->sendError($projectId, 400);
            $this->sendError($paymentName, 400);
            $this->sendError($amount, 400);
            $this->sendError($beneficiaries, 400);
            return;
        }

        $projectModel = new ProjectDetailsModel();

        try {
            $projectModel->createPayment($projectId, $payerUsername, $paymentName, $amount, $beneficiaries);
            $this->sendResponse(['message' => 'Payment added successfully'], 201);
        } catch (Exception $e) {
            $this->sendError('Error adding payment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /project-details/{id}/delete-payment/{paymentId}
     * Eliminar un pago de un proyecto
     */
    public function deletePayment($project, $paymentId) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        // Decodificar el encabezado Authorization
        $username = null;
        if ($authHeader && preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            $decoded = base64_decode($matches[1]);
            list($username, $password) = explode(':', $decoded, 2);
        }

        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }

        $projectModel = new ProjectDetailsModel();

        try {
            $isDeleted = $projectModel->deletePayment($paymentId);
            if ($isDeleted) {
                $this->sendResponse(['message' => 'Payment deleted successfully']);
            } else {
                $this->sendError('Error deleting payment', 500);
            }
        } catch (Exception $e) {
            $this->sendError('Error deleting payment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * PUT /project-details/{id}/edit-payment/{paymentId}
     * Editar un pago de un proyecto
     */
    public function editPayment($project, $paymentId) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;
    
        // Decodificar el encabezado Authorization
        $username = null;
        if ($authHeader && preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            $decoded = base64_decode($matches[1]);
            list($username, $password) = explode(':', $decoded, 2);
        }
    
        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }
    
        $input = $this->getRequestBody();
        $paymentName = $input['paymentName'] ?? null;
        $amount = $input['amount'] ?? null;
        $beneficiaries = $input['beneficiaries'] ?? [];
        $payer = $input['payer'] ?? null;
    
        if (!$paymentName || !$amount || empty($beneficiaries) || !$payer) {
            $this->sendError('All payment details are required', 400);
            return;
        }
    
        $projectModel = new ProjectDetailsModel();
    
        try {         
            $projectModel->editPayment($paymentId, $paymentName, $amount, $beneficiaries, $payer);
            $this->sendResponse(['message' => 'Payment updated successfully']);
        } catch (Exception $e) {
            $this->sendError('Error updating payment: ' . $e->getMessage(), 500);
        }
    }

}


// URI-MAPPING for this Rest endpoint
$projectDetailsRest = new ProjectDetailsRest();

URIDispatcher::getInstance()
    ->map("GET", "/project-details/$1", array($projectDetailsRest, "read"))
    ->map("POST", "/project-details/$1/add-user", array($projectDetailsRest, "addUser"))
    ->map("POST", "/project-details/$1/add-payment", array($projectDetailsRest, "createPayment"))
    ->map("DELETE", "/project-details/$1/delete-payment/$2", array($projectDetailsRest, "deletePayment"))
    ->map("PUT", "/project-details/$1/edit-payment/$2", array($projectDetailsRest, "editPayment"));

