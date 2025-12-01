<?php
require 'db.php';
header('Content-Type: application/json');

$ventaActual = json_decode(file_get_contents("php://input"), true);
if(!$ventaActual || count($ventaActual) === 0){
    echo json_encode(['mensaje'=>'No hay productos en la venta']); 
    exit;
}

$total = 0;
foreach($ventaActual as $cosa) $total += $cosa['subtotal'];

try {
    $pdo->beginTransaction();

    // validar las existencias
    foreach($ventaActual as $cosa){
        $stmtStock = $pdo->prepare("SELECT existencias FROM productos WHERE id_producto = :id_producto");
        $stmtStock->execute([':id_producto' => $cosa['id_producto']]);
        $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);
        if(!$producto || $producto['existencias'] < $cosa['cantidad']){
            throw new Exception("Existencias insuficientes para " . $cosa['nombre']);
        }
    }

    // registrar venta
    $stmtVenta = $pdo->prepare("INSERT INTO ventas (fecha,total) VALUES (NOW(),:total)");
    $stmtVenta->execute([':total'=>$total]);
    $idVenta = $pdo->lastInsertId();

    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_venta (id_venta,id_producto,cantidad,subtotal) VALUES (:id_venta,:id_producto,:cantidad,:subtotal)");
    $stmtActualizar = $pdo->prepare("UPDATE productos SET existencias = existencias - :cantidad WHERE id_producto = :id_producto");

    foreach($ventaActual as $cosa){
        $stmtDetalle->execute([
            ':id_venta'=>$idVenta,
            ':id_producto'=>$cosa['id_producto'],
            ':cantidad'=>$cosa['cantidad'],
            ':subtotal'=>$cosa['subtotal']
        ]);
        $stmtActualizar->execute([
            ':cantidad'=>$cosa['cantidad'],
            ':id_producto'=>$cosa['id_producto']
        ]);
    }

    $pdo->commit();
    echo json_encode(['mensaje'=>'Venta registrada correctamente']);
} catch(Exception $e){
    $pdo->rollBack();
    echo json_encode(['mensaje'=>'Error: '.$e->getMessage()]);
}
?>
