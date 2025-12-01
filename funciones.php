<?php
// Función de conexión a la base de datos usando PDO
function conexion($dbname, $user, $pass){
    try {
        $conexion = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        return false;
    }
}

// Función para obtener detalles de una venta
function obtenerDetallesVenta($conexion, $id_venta) {
    $stmt = $conexion->prepare("SELECT dv.*, p.nombre 
                                FROM detalle_venta dv 
                                JOIN productos p ON dv.id_producto = p.id_producto 
                                WHERE dv.id_venta = :id_venta");
    $stmt->execute([':id_venta' => $id_venta]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
