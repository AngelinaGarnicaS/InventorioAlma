<?php
require 'db.php';
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'list') {
    try {
        $stmt = $pdo->query("SELECT id_producto AS id, nombre, descripcion, categoria, precio, existencias, imagen FROM productos ORDER BY id_producto DESC");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $productos]);
    } catch(Exception $e){
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no válida']);
?>
