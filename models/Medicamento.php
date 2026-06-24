<?php
class Medicamento {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM medicamentos ORDER BY id_medicamento DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM medicamentos WHERE id_medicamento = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO medicamentos (nombre_generico, presentacion, categoria, precio, stock_minimo) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['nombre'], $data['presentacion'], $data['categoria'], $data['precio'], $data['stock_minimo']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE medicamentos SET nombre_generico=?, presentacion=?, categoria=?, precio=?, stock_minimo=? WHERE id_medicamento=?");
        return $stmt->execute([$data['nombre'], $data['presentacion'], $data['categoria'], $data['precio'], $data['stock_minimo'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM medicamentos WHERE id_medicamento = ?");
        return $stmt->execute([$id]);
    }
}
?>