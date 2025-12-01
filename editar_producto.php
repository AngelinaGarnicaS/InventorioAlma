<?php
require 'db.php';
$error = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto=:id");
$stmt->execute([':id'=>$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$producto) { header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = htmlspecialchars($_POST['nombre']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $existencias = (int)$_POST['existencias'];
    $categoria = htmlspecialchars($_POST['categoria']);
    $imagen = $_FILES['imagen'] ?? null;
    $nuevoNombre = $producto['imagen'];

    if($imagen && $imagen['error'] === 0){
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
        if(!in_array(strtolower($ext), $allowed)){
            $error = "Tipo de imagen no permitido";
        } else {
            $nuevoNombre = uniqid() . "." . $ext;
            move_uploaded_file($imagen['tmp_name'], "../imagenes/$nuevoNombre");
            if(!empty($producto['imagen']) && file_exists("../imagenes/".$producto['imagen'])){
                unlink("../imagenes/".$producto['imagen']);
            }
        }
    }

    if(empty($error)){
        $stmt = $pdo->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, existencias=:existencias, categoria=:categoria, imagen=:imagen WHERE id_producto=:id");
        $stmt->execute([
            ':nombre'=>$nombre,
            ':descripcion'=>$descripcion,
            ':precio'=>$precio,
            ':existencias'=>$existencias,
            ':categoria'=>$categoria,
            ':imagen'=>$nuevoNombre,
            ':id'=>$id
        ]);
        header('Location: index.php');
        exit;
    }
}

require 'views/editar_producto.view.php';
?>
