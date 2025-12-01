<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Producto</title>
    <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 form-container">
    <h1 class="titulo-form">Agregar Nuevo Producto</h1>
    <form action="subir_producto.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Existencias</label>
            <input type="number" name="existencias" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Imagen del Producto</label>
            <input type="file" name="imagen" class="form-control" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-agregar">
                <i class="bi bi-plus-circle"></i> Agregar Producto
            </button>
            <a href="index.php" class="btn btn-cancelar">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>

</body>
</html>
