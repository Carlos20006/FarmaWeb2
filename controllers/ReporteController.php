<?php
require_once __DIR__ . '/../config/db.php';

class ReporteController {
    private $db;
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->db = $pdo ?: $pdo;
    }
    
    public function getVencidosProximos() {
        $stmt = $this->db->prepare("
            SELECT m.nombre_generico, l.numero_lote, l.fecha_vencimiento, l.cantidad_actual,
                   DATEDIFF(l.fecha_vencimiento, CURDATE()) as dias_restantes
            FROM lotes l 
            JOIN medicamentos m ON l.id_medicamento = m.id_medicamento 
            WHERE l.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY l.fecha_vencimiento ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStockCritico() {
        $stmt = $this->db->prepare("
            SELECT m.*, COALESCE(SUM(l.cantidad_actual), 0) as stock_total
            FROM medicamentos m
            LEFT JOIN lotes l ON m.id_medicamento = l.id_medicamento AND l.fecha_vencimiento >= CURDATE()
            GROUP BY m.id_medicamento
            HAVING stock_total <= m.stock_minimo
            ORDER BY stock_total ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMasVendidos($limite = 10) {
        $limite = (int)$limite;
        $stmt = $this->db->prepare("
            SELECT m.nombre_generico, SUM(vd.cantidad) as total_vendido, COUNT(DISTINCT v.id_venta) as numero_ventas
            FROM venta_detalle vd
            JOIN lotes l ON vd.id_lote = l.id_lote
            JOIN medicamentos m ON l.id_medicamento = m.id_medicamento
            JOIN ventas v ON vd.id_venta = v.id_venta
            GROUP BY m.id_medicamento
            ORDER BY total_vendido DESC
            LIMIT ?
        ");
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>