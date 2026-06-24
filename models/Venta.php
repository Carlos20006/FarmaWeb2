<?php
class Venta {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT v.*, c.nombre as cliente_nombre, u.nombre_usuario as vendedor
            FROM ventas v
            JOIN clientes c ON v.id_cliente = c.id_cliente
            JOIN usuarios u ON v.id_usuario = u.id_usuario
            ORDER BY v.id_venta DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT v.*, c.nombre as cliente_nombre
            FROM ventas v
            JOIN clientes c ON v.id_cliente = c.id_cliente
            WHERE v.id_venta = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDetalles($id_venta) {
        $stmt = $this->db->prepare("
            SELECT vd.*, l.numero_lote, m.nombre_generico, m.precio
            FROM venta_detalle vd
            JOIN lotes l ON vd.id_lote = l.id_lote
            JOIN medicamentos m ON l.id_medicamento = m.id_medicamento
            WHERE vd.id_venta = ?
        ");
        $stmt->execute([$id_venta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($id_cliente, $id_usuario, $productos) {
        try {
            $this->db->beginTransaction();
            
            $total = 0;
            $detalles = [];
            
            foreach($productos as $item) {
                $id_med = $item['id_medicamento'];
                $cantidad = $item['cantidad'];
                $restante = $cantidad;
                
                // Buscar lotes disponibles (FIFO)
                $stmt = $this->db->prepare("
                    SELECT * FROM lotes 
                    WHERE id_medicamento = ? AND cantidad_actual > 0 AND fecha_vencimiento >= CURDATE() 
                    ORDER BY fecha_vencimiento ASC
                ");
                $stmt->execute([$id_med]);
                $lotes = $stmt->fetchAll();
                
                if(empty($lotes)) {
                    throw new Exception("No hay stock disponible para el medicamento ID $id_med");
                }
                
                foreach($lotes as $lote) {
                    if($restante <= 0) break;
                    
                    $tomar = min($lote['cantidad_actual'], $restante);
                    $precio = $this->getPrecioMedicamento($id_med);
                    $total += $tomar * $precio;
                    
                    $detalles[] = [
                        'id_lote' => $lote['id_lote'],
                        'cantidad' => $tomar,
                        'precio' => $precio
                    ];
                    
                    // Descontar del lote
                    $update = $this->db->prepare("UPDATE lotes SET cantidad_actual = cantidad_actual - ? WHERE id_lote = ?");
                    $update->execute([$tomar, $lote['id_lote']]);
                    
                    $restante -= $tomar;
                }
                
                if($restante > 0) {
                    throw new Exception("Stock insuficiente para el medicamento ID $id_med");
                }
            }
            
            // Registrar venta
            $stmt = $this->db->prepare("INSERT INTO ventas (total, id_cliente, id_usuario) VALUES (?, ?, ?)");
            $stmt->execute([$total, $id_cliente, $id_usuario]);
            $id_venta = $this->db->lastInsertId();
            
            // Registrar detalles
            foreach($detalles as $det) {
                $stmt = $this->db->prepare("INSERT INTO venta_detalle (id_venta, id_lote, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_venta, $det['id_lote'], $det['cantidad'], $det['precio']]);
            }
            
            $this->db->commit();
            return ['success' => true, 'id_venta' => $id_venta, 'total' => $total];
            
        } catch(Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function getPrecioMedicamento($id_med) {
        $stmt = $this->db->prepare("SELECT precio FROM medicamentos WHERE id_medicamento = ?");
        $stmt->execute([$id_med]);
        return $stmt->fetchColumn();
    }
}
?>