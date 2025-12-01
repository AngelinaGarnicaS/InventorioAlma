const appVue = Vue.createApp({
    data() {
        return {
            ropa: [],
            venta: [],
            mostrarAlerta: false,
            mensajeAlerta: '',
            categoriaSeleccionada: "", 
            categorias: [] 
        }
    },
    computed: {
        totalventa() {
            return this.venta.reduce((suma, cosa) => suma + cosa.subtotal, 0);
        },
        ropaFiltrada() {
            if (this.categoriaSeleccionada === "") {
                return this.ropa;
            }
            return this.ropa.filter(p => p.categoria === this.categoriaSeleccionada);
        }
    },
    methods: {
        cargarProductos() {
            fetch('api.php?action=list')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.ropa = data.data;
                        // ✔ Limpia duplicados, espacios y mayúsculas/minúsculas
                        this.categorias =
                        [...new Set(this.ropa.map(p =>
                            p.categoria.trim().toLowerCase()
                        ))]
                        .map(categoria => categoria.charAt(0).toUpperCase() + categoria.slice(1));
                    }
                });
        },
        agregarAlventa(prenda) {
            // Si ya está en la venta
            const cosa = this.venta.find(i => i.id_producto === prenda.id);
            if (cosa) {
                if (cosa.cantidad < prenda.existencias) {
                    cosa.cantidad++;
                    cosa.subtotal = cosa.cantidad * cosa.precio;
                    prenda.existencias--; // DISMINUYEN LAS EXISTENCIAS EN LA TARJETA
                }
            } else {
                // Agregar nuevo producto a la venta
                this.venta.push({
                    id_producto: prenda.id,
                    nombre: prenda.nombre,
                    precio: prenda.precio,
                    cantidad: 1,
                    subtotal: prenda.precio
                });
                prenda.existencias--; 
            }
        },
        eliminarDelventa(index) {
            const cosa = this.venta[index];
            const prenda = this.prendaCorrespondiente(cosa.id_producto);
            // devolver todas las existencias
            if (prenda) prenda.existencias += cosa.cantidad;
            this.venta.splice(index, 1);
        },
        vaciarventa() {
            this.venta.forEach(cosa => {
                const prenda = this.ropa.find(p => p.id === cosa.id_producto);
                if (prenda) prenda.existencias += cosa.cantidad;
            });
            this.venta = [];
        },
        comprar() {
            if(this.venta.length === 0) return;
            fetch('registrar_venta.php', {
                method:'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify(this.venta)
            })
            .then(res=>res.json())
            .then(data=>{
                this.mensajeAlerta = data.mensaje;
                this.mostrarAlerta = true;
                setTimeout(()=>this.mostrarAlerta=false,3000);
                this.vaciarventa();
                this.cargarProductos();
            });
        },
        confirmarEliminacion(id) {
            if(confirm("¿Seguro que quieres eliminar este producto?")){
                window.location.href = `eliminar_producto.php?id=${id}`;
            }
        },
        prendaCorrespondiente(id_producto) {
            return this.ropa.find(p => p.id === id_producto);
        },
        sumarCantidad(index) {
            const cosa = this.venta[index];
            const prenda = this.prendaCorrespondiente(cosa.id_producto);
            if (prenda.existencias > 0) {
                cosa.cantidad++;
                cosa.subtotal = cosa.cantidad * cosa.precio;
                prenda.existencias--; // disminuir existencias visibles
                }
            },
            restarCantidad(index) {
                const cosa = this.venta[index];
                const prenda = this.prendaCorrespondiente(cosa.id_producto);
                // devolver existencias
                if (prenda) prenda.existencias++;
                if (cosa.cantidad > 1) {
                    cosa.cantidad--;
                    cosa.subtotal = cosa.cantidad * cosa.precio;
                } else {
                    // eliminar si ya no queda ninguna
                    this.venta.splice(index, 1);
                }
            },
    },
    mounted() {
        this.cargarProductos();
    }
}).mount('#appVue');