<?php
class Proveedor {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM proveedores ORDER BY id_proveedor DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['nombre'], $data['contacto'], $data['telefono'], $data['email']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE proveedores SET nombre=?, contacto=?, telefono=?, email=? WHERE id_proveedor=?");
        return $stmt->execute([$data['nombre'], $data['contacto'], $data['telefono'], $data['email'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
        return $stmt->execute([$id]);
    }
}
?>