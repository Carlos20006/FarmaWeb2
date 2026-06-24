<?php
class Cliente {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY id_cliente DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, documento, telefono, direccion) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['nombre'], $data['documento'], $data['telefono'], $data['direccion']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre=?, documento=?, telefono=?, direccion=? WHERE id_cliente=?");
        return $stmt->execute([$data['nombre'], $data['documento'], $data['telefono'], $data['direccion'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    }
}
?>