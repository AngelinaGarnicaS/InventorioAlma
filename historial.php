<?php
require 'db.php';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'desc';
if($orden!=='asc' && $orden!=='desc') $orden='desc';

$ventas = $pdo->query("SELECT * FROM ventas ORDER BY fecha $orden")->fetchAll(PDO::FETCH_ASSOC);

function obtenerDetalles($pdo,$idVenta){
    $stmt = $pdo->prepare("SELECT dv.*, p.nombre FROM detalle_venta dv JOIN productos p ON dv.id_producto=p.id_producto WHERE dv.id_venta=:id");
    $stmt->execute([':id'=>$idVenta]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Ventas</title>
<link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <script src="vue3.js"></script>
  <script src="bootstrap.js"></script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4 container-historial">
    <h1>Historial de Ventas</h1>
    <div class="mb-3">
        <label>Ordenar por fecha:</label>
        <select class="form-select w-auto d-inline-block" onchange="location.href='historial.php?orden='+this.value">
            <option value="desc" <?= $orden=='desc'?'selected':'' ?>>Más recientes</option>
            <option value="asc" <?= $orden=='asc'?'selected':'' ?>>Más antiguas</option>
        </select>
    </div>

    <?php if(count($ventas)>0): ?>
        <?php foreach($ventas as $v): $detalles = obtenerDetalles($pdo,$v['id_venta']); ?>
        <div class="card mb-3">
            <div class="card-header">
                <strong>Venta:</strong> <?= $v['id_venta'] ?> |
                <strong>Fecha:</strong> <?= $v['fecha'] ?> |
                <strong>Total:</strong> $<?= $v['total'] ?>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($detalles as $d): ?>
                        <tr>
                            <td><?= $d['nombre'] ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td>$<?= $d['subtotal'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-ventas">No hay ventas registradas.</p>
    <?php endif; ?>
</div>

</body>
</html>
