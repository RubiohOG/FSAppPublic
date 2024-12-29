<?php
// models/ProjectDetailsModel.php

require_once __DIR__ . '/../core/db_connection.php';

class ProjectDetailsModel {
    public function getProjectDetails($projectId) {
        global $db;

        try {
            $stmt = $db->prepare("SELECT * FROM projects WHERE project_id = ?");
            $stmt->execute([$projectId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los detalles del proyecto: " . $e->getMessage();
            return false;
        }
    }

    public function getPayments($projectId) {
        global $db;
    
        try {
            $stmt = $db->prepare("SELECT payment_id, payer_username, payment_name, amount FROM payments WHERE project_id = ?");
            $stmt->execute([$projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los pagos: " . $e->getMessage();
            return false;
        }
    }

    public function getUserProjects($username) {
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
    
    public function addUserToProject($projectId, $username) {
        global $db;
        $stmt = $db->prepare("INSERT INTO project_users (project_id, user_name) VALUES (?, ?)");
        return $stmt->execute([$projectId, $username]);
    }

    public function userExistsInProject($projectId, $username) {
        global $db;

        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM project_users WHERE project_id = :projectId AND user_name = :username");
            $stmt->bindParam(':projectId', $projectId);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0; //Return true si el usuario ya existe en el proyecto
        } catch (PDOException $e) {
            echo "Error al verificar si el usuario existe en el proyecto: " . $e->getMessage();
            return false;
        }
    }

    public function getProjectUsers($projectId) {
        global $db;
        try {
            $stmt = $db->prepare("SELECT user_name FROM project_users WHERE project_id = ?");
            $stmt->execute([$projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener los usuarios del proyecto: " . $e->getMessage();
            return false;
        }
    }

    public function createPayment($projectId, $payerUsername, $paymentName, $amount, $beneficiaries) {
        global $db;
    
        try {
            $stmt = $db->prepare("INSERT INTO payments (project_id, payer_username, payment_name, amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$projectId, $payerUsername, $paymentName, $amount]);
            $paymentId = $db->lastInsertId();

            $numBeneficiaries = count($beneficiaries);
            $debtAmount = $amount / $numBeneficiaries;
    
            foreach ($beneficiaries as $beneficiary) {
                if ($beneficiary != $payerUsername) { //El que paga no tiene deuda consigo mismo
                    $stmt = $db->prepare("INSERT INTO debts (payment_id, debtor_username, amount) VALUES (?, ?, ?)");
                    $stmt->execute([$paymentId, $beneficiary, $debtAmount]);
                }
            }
    
            return true;
        } catch (PDOException $e) {
            echo "Error al crear el pago y registrar las deudas: " . $e->getMessage();
            return false;
        }
    }    

    public function getDebts($projectId) {
        global $db;
        $stmt = $db->prepare("SELECT d.debtor_username, p.payer_username, d.amount
                              FROM debts d
                              JOIN payments p ON d.payment_id = p.payment_id
                              WHERE p.project_id = ?");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePayment($paymentId) {
        global $db;
        try {
            $stmt = $db->prepare("DELETE FROM payments WHERE payment_id = ?");
            return $stmt->execute([$paymentId]);
        } catch (PDOException $e) {
            echo "Error al eliminar el pago: " . $e->getMessage();
            return false;
        }
    }

    public function editPayment($paymentId, $paymentName, $amount, $beneficiaries) {
        global $db;
    
        try {
            $db->beginTransaction();
    
            $stmt = $db->prepare("UPDATE payments SET payment_name = ?, amount = ? WHERE payment_id = ?");
            $stmt->execute([$paymentName, $amount, $paymentId]);
    
            $stmt = $db->prepare("DELETE FROM debts WHERE payment_id = ?");
            $stmt->execute([$paymentId]);
    
            $debtAmount = count($beneficiaries) > 0 ? $amount / count($beneficiaries) : 0;
    
            foreach ($beneficiaries as $beneficiary) {
                if ($beneficiary !== $_SESSION['currentuser']) {
                    $stmt = $db->prepare("INSERT INTO debts (payment_id, debtor_username, amount) VALUES (?, ?, ?)");
                    $stmt->execute([$paymentId, $beneficiary, $debtAmount]);
                }
            }
    
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            echo "Error al actualizar el pago: " . $e->getMessage();
            return false;
        }
    }

    //Funciones exclusivas para obtener los deudores de cada pago
    public function getPaymentDetails($paymentId) {
        $paymentDetails = $this->model->getPaymentById($paymentId);
        $beneficiaries = $this->model->getPaymentBeneficiaries($paymentId);
        $paymentDetails['beneficiaries'] = $beneficiaries;
        return $paymentDetails;
    }    
    public function getPaymentBeneficiaries($paymentId) {
        global $db;
        $stmt = $db->prepare("SELECT debtor_username FROM debts WHERE payment_id = ?");
        $stmt->execute([$paymentId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function getPaymentById($paymentId) {
        global $db;
        try {
            $stmt = $db->prepare("SELECT * FROM payments WHERE payment_id = ?");
            $stmt->execute([$paymentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener el pago: " . $e->getMessage();
            return false;
        }
    }
    
    
    
}

