<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $model;
    
    public function __construct($pdo = null) {
        global $pdo;
        $this->model = new Cliente($pdo ?: $pdo);
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
?>