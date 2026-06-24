<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Lote.php';

class LoteController {
    private $model;
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->model = new Lote($pdo ?: $pdo);
    }
    
    public function index() {
        return $this->model->getAllWithMedicamento();
    }
    
    public function create($data) {
        return $this->model->create($data);
    }
    
    public function delete($id) {
        return $this->model->delete($id);
    }
}
?>