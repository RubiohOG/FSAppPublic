<?php
require_once(__DIR__."/../models/LoginModel.php");

class BaseRest {
    /*
    public function authenticateUser() {
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
			header('WWW-Authenticate: Basic realm="Rest API of MVCBLOG"');
			die('This operation requires authentication');
		}
		else {
			$loginModel = new LoginModel();
			if ($loginModel->checkLogin(
			$_SERVER['PHP_AUTH_USER'],
			$_SERVER['PHP_AUTH_PW'])) {

				return new User($_SERVER['PHP_AUTH_USER']);
			} else {
				header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
				header('WWW-Authenticate: Basic realm="Rest API of MVCBLOG"');

				die('The username/password is not valid');
			}
		}
	}
    */

    protected function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function sendError($message, $statusCode = 400) {
        $this->sendResponse(['error' => $message], $statusCode);
    }

    protected function getRequestBody() {
        return json_decode(file_get_contents('php://input'), true);
    }
}
