<?php
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = (float)$_POST['precio'];
    $existencias = (int)$_POST['existencias'];
    $categoria = $_POST['categoria'];
    $imagen = $_FILES['imagen'];

    // Validación imagen
    if ($imagen['error'] === 0) {

        $allowed = ['jpg', 'jpeg', 'png', 'gif','webp'];
        $ext = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Tipo de imagen no permitido";
        } else {

            // Carpeta donde se guardarán las imágenes
            $carpeta = "imagenes/";

            // Crear carpeta si no existe
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            // Ruta final
            $rutaImagen = $carpeta . uniqid("prod_") . "." . $ext;

            // Mover imagen
            if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                $error = "Error al subir la imagen";
            }
        }

    } else {
        $error = "Debe subir una imagen";
    }

    // Si NO HAY ERROR → Insertar
    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO productos (nombre, descripcion, precio, existencias, categoria, imagen)
                VALUES (:nombre, :descripcion, :precio, :existencias, :categoria, :imagen)
            ");

            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':existencias' => $existencias,
                ':categoria' => $categoria,
                ':imagen' => $rutaImagen
            ]);

            // Redirigir al index
            header("Location: index.php?agregado=1");
            exit;

        } catch (PDOException $e) {
            echo "ERROR SQL: " . $e->getMessage();
        }
    } else {
        echo $error;
    }
}

require 'views/subir_producto.view.php';
?>
