<?php
class Usuario {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY id_usuario DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($username, $password, $rol = 'vendedor') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_usuario, password_hash, rol) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $rol]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }
}
?>