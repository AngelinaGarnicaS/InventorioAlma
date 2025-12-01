<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario de Ropa</title>

  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="css/estilos.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <script src="vue3.js"></script>
  <script src="bootstrap.js"></script>
  <script defer src="script.js"></script>
</head>
<body>
  <div id="appVue">
    <?php require 'header.php'; ?>
    <div class="container mt-4">
      <div class="row">
      <!-- Productos -->
      <div class="col-md-6 productos-col">
        <h2 class="mb-3 text-center">Productos</h2>
        <div class="row">
          <!-- Filtro por categoría -->
          <div class="mb-3">
            <select v-model="categoriaSeleccionada" class="form-select">
              <label><strong>Filtrar por categoría:</strong></label>
              <option value="">Todas</option>
              <option v-for="categoria in categorias" :value="categoria">{{ categoria }}</option>
            </select>
          </div>
          <div class="col-12 col-md-6 mb-3" v-for="prenda in ropaFiltrada" :key="prenda.id">
            <div class="card h-100 shadow-sm">
              <img :src="'./' + prenda.imagen" class="card-img-top">
              <div class="card-body">
              <h5 class="card-title">{{ prenda.nombre }}</h5>
                <ul class="list-group list-group-flush small mb-3">
                  <li class="list-group-item"><strong>Descripción:</strong> {{ prenda.descripcion }}</li>
                  <li class="list-group-item"><strong>Categoría:</strong> {{ prenda.categoria }}</li>
                </ul>
              </div>
              <div class="card-footer text-center">
                <strong class="d-block mb-2">${{ prenda.precio }}</strong>
                <p class="mb-1">
                  <span class="badge"
                        :class="prenda.existencias > 0 ? 'bg-success' : 'bg-danger'">
                    {{ prenda.existencias > 0 ? 'Disponible' : 'Agotado' }}
                  </span>
                </p>
                
                <p class="text-muted">
                  Existencias: {{ prenda.existencias }}
                </p>
                <div class="card-footer d-flex justify-content-center gap-2">
                  <button class="btn btn-sm btn-agregar" :disabled="prenda.existencias <= 0" @click="agregarAlventa(prenda)">
                    <i class="bi bi-cart-plus"></i> Agregar
                  </button>
                  <a :href="'editar_producto.php?id=' + prenda.id" class="btn btn-sm btn-editar">
                    <i class="bi bi-pencil-square"></i> Editar</a>
                    <button class="btn btn-sm btn-eliminar" @click.prevent="confirmarEliminacion(prenda.id)">
                      <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Venta actual -->
      <div class="col-md-6">
        <h2 class="mb-4 text-center"><i class="bi bi-bag"></i> Venta Actual</h2>
        <div v-if="venta.length > 0">
          <table class="table table-bordered">
            <thead class="table-primary">
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(cosa, index) in venta" :key="cosa.id">
                <td>{{ cosa.nombre }}</td>
                <td>${{ cosa.precio }}</td>
                <td class="text-center">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary"
                            @click="restarCantidad(index)">
                        <i class="bi bi-dash"></i>
                    </button>
                
                    <span class="mx-2">{{ cosa.cantidad }}</span>
                
                    <button class="btn btn-sm btn-outline-secondary"
                            @click="sumarCantidad(index)"
                            :disabled="prendaCorrespondiente(cosa.id_producto).existencias <= 0">
                        <i class="bi bi-plus"></i>
                    </button>
                  </div>
                </td>

                <td>${{ cosa.subtotal }}</td>
                <td class="text-center">
                  <button class="btn btn-danger btn-sm" @click="eliminarDelventa(index)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th colspan="2">${{ totalventa }}</th>
              </tr>
              <tr>
                <td colspan="5" class="text-end">
                  <button class="btn btn-danger me-2" @click="vaciarventa()">
                    <i class="bi bi-ban"></i> Vaciar venta
                  </button>
                  <button class="btn btn-success" @click="comprar()">
                    <i class="bi bi-bag"></i> Registrar venta
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div v-else class="text-center text-muted mt-5">
          <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
          <p>No hay productos en la venta.</p>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>