<?php
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : false;
if (!$id) {
    header('Location: index.php');
    exit;
}

// buscar producto
$stmt = $pdo->prepare('SELECT * FROM productos WHERE id_producto = :id');
$stmt->execute([':id' => $id]);
$producto = $stmt->fetch();

if (!$producto) {
    header('Location: index.php');
    exit;
}

// eliminar imagen
$imagenPath = '../imagenes/' . $producto['imagen'];
if (!empty($producto['imagen']) && file_exists($imagenPath)) {
    unlink($imagenPath);
}

// eliminar registro de la base de datos
$stmt = $pdo->prepare('DELETE FROM productos WHERE id_producto = :id');
$stmt->execute([':id' => $id]);

header('Location: index.php');
exit;
