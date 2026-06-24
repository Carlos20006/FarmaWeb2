<?php
require_once 'config/db.php';
require_once 'models/Usuario.php';

class AuthController {
    private $usuarioModel;
    
    public function __construct($pdo) {
        $this->usuarioModel = new Usuario($pdo);
    }
    
    public function login($username, $password) {
        $usuario = $this->usuarioModel->findByUsername($username);
        
        if($usuario && password_verify($password, $usuario['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['user_name'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];
            return true;
        }
        return false;
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>