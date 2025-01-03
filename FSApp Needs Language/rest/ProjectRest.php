<?php

require_once 'BaseRest.php';
require_once __DIR__ . '/../models/ProjectModel.php';

class ProjectRest extends BaseRest {
    /**
     * GET /projects
     * Listar proyectos del usuario autenticado
     */
    public function readAll() {
        $headers = getallheaders();
        $username = null;

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
                $decoded = base64_decode($matches[1]);
                list($username, $password) = explode(':', $decoded, 2);
            }
        }

        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }

        try {
            $projects = ProjectModel::getUserProjects($username);
            $this->sendResponse($projects);
        } catch (Exception $e) {
            $this->sendError('Error retrieving projects: ' . $e->getMessage(), 500);
        }
    }

    public function create() {
        $headers = getallheaders();
        $username = null;

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
                $decoded = base64_decode($matches[1]);
                list($username, $password) = explode(':', $decoded, 2);
            }
        }

        if (!$username) {
            $this->sendError('Unauthorized: Token is required', 401);
            return;
        }

        $input = $this->getRequestBody();
        $projectName = $input['project_name'] ?? '';

        if (empty($projectName)) {
            $this->sendError('Project name is required', 400);
            return;
        }

        try {
            ProjectModel::createProject($projectName, $username);
            $this->sendResponse(['message' => 'Project created successfully'], 201);
        } catch (Exception $e) {
            $this->sendError('Error creating project: ' . $e->getMessage(), 500);
        }
    }
}

// URI-MAPPING for this Rest endpoint
$projectRest = new ProjectRest();
URIDispatcher::getInstance()
    ->map("GET", "/projects", array($projectRest, "readAll"))
    ->map("POST", "/projects", array($projectRest, "create"));

