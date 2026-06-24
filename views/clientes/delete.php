<?php
session_start();
if (!isset($_GET['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_GET['csrf_token'])) {
    die('Token de seguridad inválido');
}

require_once dirname(__DIR__, 2) . '/controllers/ClienteController.php';

$controller = new ClienteController();
$controller->delete($_GET['id']);
$_SESSION['mensaje'] = "Cliente eliminado";
header('Location: index.php');
exit;
?>