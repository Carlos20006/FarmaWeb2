<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Medicamento.php';

class MedicamentoController {
    private $model;
    private $db;
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->db = $pdo ?: $pdo;
        $this->model = new Medicamento($this->db);
    }
    
    public function index() {
        return $this->model->getAll();
    }
    
    public function getById($id) {
        return $this->model->getById($id);
    }
    
    public function create($data) {
        return $this->model->create($data);
    }
    
    public function update($id, $data) {
        return $this->model->update($id, $data);
    }
    
    public function delete($id) {
        return $this->model->delete($id);
    }
}

// Si se llama directamente desde la vista
if(isset($_GET['action'])) {
    $controller = new MedicamentoController();
    switch($_GET['action']) {
        case 'index':
            $medicamentos = $controller->index();
            break;
        case 'delete':
            $controller->delete($_GET['id']);
            header('Location: index.php');
            break;
    }
}
?>