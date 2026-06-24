<?php
class Lote {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAllWithMedicamento() {
        $stmt = $this->db->query("
            SELECT l.*, m.nombre_generico 
            FROM lotes l 
            JOIN medicamentos m ON l.id_medicamento = m.id_medicamento 
            ORDER BY l.id_lote DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO lotes (numero_lote, id_medicamento, cantidad_inicial, cantidad_actual, fecha_vencimiento) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['numero_lote'], $data['id_medicamento'], $data['cantidad'], $data['cantidad'], $data['fecha_vencimiento']]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM lotes WHERE id_lote = ?");
        return $stmt->execute([$id]);
    }
}
?>