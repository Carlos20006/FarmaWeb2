<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Venta.php';

class VentaController {
    private $model;
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->model = new Venta($pdo ?: $pdo);
    }
    
    public function index() {
        return $this->model->getAll();
    }
    
    public function getById($id) {
        return $this->model->getById($id);
    }
    
    public function getDetalles($id_venta) {
        return $this->model->getDetalles($id_venta);
    }
    
    public function create($id_cliente, $id_usuario, $productos) {
        return $this->model->create($id_cliente, $id_usuario, $productos);
    }
}
?>