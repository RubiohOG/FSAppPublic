<?php
// models/ProjectModel.php

require_once __DIR__ . '/../core/db_connection.php';

class ProjectModel {
    // Obtener todos los proyectos de un usuario
    public static function getAllProjects($username) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM projects WHERE owner_username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getUserProjects($username) {
        global $db;
        $stmt = $db->prepare("
            SELECT * FROM projects 
            WHERE owner_username = :username 
            OR project_id IN (
                SELECT project_id FROM project_users WHERE user_name = :username_alt
            )
        ");
        $stmt->execute(['username' => $username, 'username_alt' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
       

    // Obtener un proyecto por su ID
    public static function getProjectById($projectId) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM projects WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo proyecto
    /*
    public static function createProject($projectName, $username) {
        global $db;
        $stmt = $db->prepare("INSERT INTO projects (project_name, owner_username) VALUES (?, ?)");
        $stmt->execute([$projectName, $username]);
    }
    */

    public static function createProject($projectName, $username) {
        global $db;
    
        try {
            $db->beginTransaction();
            $stmt = $db->prepare("INSERT INTO projects (project_name, owner_username) VALUES (?, ?)");
            $stmt->execute([$projectName, $username]);
    
            $projectId = $db->lastInsertId();
    
            $stmt = $db->prepare("INSERT INTO project_users (project_id, user_name) VALUES (?, ?)");
            $stmt->execute([$projectId, $username]);

            $db->commit();
    
        } catch (PDOException $e) {
            $db->rollBack();
            echo "Error al crear el proyecto: " . $e->getMessage();
        }
    }
    
    
}
