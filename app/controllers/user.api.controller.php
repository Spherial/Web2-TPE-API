<?php
    require_once './app/controllers/api.controller.php';
    require_once './app/models/user.model.php';

    class UserApiController extends ApiController {
        private $authHelper;
        private $userModel;
    
        function __construct() {
            parent::__construct();
            $this->authHelper = new AuthHelper();
            $this->userModel = new UserModel(); // Asegúrate de tener la clase UserModel con las funciones necesarias.
        }
    
        function getToken($params = []) {
            $basic = $this->authHelper->getAuthHeaders();
    
            if (empty($basic)) {
                $this->view->response('No envió encabezados de autenticación.', 401);
                return;
            }
    
            $basic = explode(" ", $basic);
    
            if ($basic[0] != "Basic") {
                $this->view->response('Los encabezados de autenticación son incorrectos.', 401);
                return;
            }
    
            $userpass = base64_decode($basic[1]);
            $userpass = explode(":", $userpass);
    
            $user = $userpass[0];
            $pass = $userpass[1];
    
            // consulta la base de datos para validar las credenciales.
            $userData = $this->userModel->getUserByUsername($user);
            
            if ($userData && password_verify($pass, $userData->password)) {
                // Usuario es válido
                $tokenData = [
                    "name" => $userData->username,
                    "id" => $userData->id_usuario,
                ];
    
                $token = $this->authHelper->createToken($tokenData);
                $this->view->response($token);
            } else {
                $this->view->response('El usuario o contraseña son incorrectos.', 401);
            }
        }
    }