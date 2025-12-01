<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <script src="vue3.js"></script>
</head>
<body>

<div class="container mt-5 form-container">
    <h1 class="titulo-form">Editar Producto</h1>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3 imagen-actual">
            <img src="../imagenes/<?php echo $producto['imagen']; ?>" alt="Imagen del producto">
        </div>

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $producto['nombre']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required><?php echo $producto['descripcion']; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" class="form-control" value="<?php echo $producto['precio']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Existencias</label>
            <input type="number" name="existencias" class="form-control" value="<?php echo $producto['existencias']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Categoría</label>
            <input type="text" name="categoria" class="form-control" value="<?php echo $producto['categoria']; ?>" required>
        </div>

        <div class="mb-3">
            <label>Nueva Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-guardar">
                <i class="bi bi-save"></i> Guardar Cambios
            </button>
            <a href="index.php" class="btn btn-cancelar">
                <i class="bi bi-x-circle"></i> Cancelar
            </a>
        </div>
    </form>
</div>
</body>
</html>
