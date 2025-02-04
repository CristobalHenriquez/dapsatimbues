<?php
// Incluir el archivo de conexión
require_once 'includes/db_connection.php';

try {
    // Obtener categorías usando la conexión mysqli existente
    $query_categorias = "SELECT * FROM categorias";
    $result_categorias = mysqli_query($db, $query_categorias);

    if (!$result_categorias) {
        throw new Exception("Error al obtener categorías: " . mysqli_error($db));
    }
?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="card-title">Gestión de Productos</h2>
                        <button class="btn btn-success" onclick="mostrarModalAgregar()">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Nuevo Producto
                        </button>
                    </div>
                    <div class="card-body">
                        <?php while ($categoria = mysqli_fetch_assoc($result_categorias)): ?>
                            <h3 class="categoria-titulo"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></h3>
                            <div class="table-responsive mb-4">
                                <table class="table tabla-productos" id="tabla-categoria-<?php echo $categoria['id_categoria']; ?>">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_productos = "SELECT * FROM productos WHERE id_categoria = " . $categoria['id_categoria'];
                                        $result_productos = mysqli_query($db, $query_productos);

                                        if (!$result_productos) {
                                            throw new Exception("Error al obtener productos: " . mysqli_error($db));
                                        }

                                        while ($producto = mysqli_fetch_assoc($result_productos)):
                                        ?>
                                            <tr data-id="<?php echo $producto['id_producto']; ?>">
                                                <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                                                <td><?php echo htmlspecialchars($producto['descripcion_producto']); ?></td>
                                                <td>$<?php echo number_format($producto['precio_producto'], 0, '', '.'); ?></td>
                                                <td>
                                                    <button class="btn btn-edit" onclick="editarProducto(<?php echo $producto['id_producto']; ?>)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-delete" onclick="eliminarProducto(<?php echo $producto['id_producto']; ?>, '<?php echo htmlspecialchars($producto['nombre_producto']); ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div class="modal fade" id="editarProductoModal" tabindex="-1" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarProductoForm">
                        <input type="hidden" id="id_producto" name="id_producto">
                        <div class="form-group">
                            <label for="nombre_producto">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                        </div>
                        <div class="form-group">
                            <label for="id_categoria">Categoría</label>
                            <select class="form-control" id="id_categoria" name="id_categoria" required>
                                <?php
                                mysqli_data_seek($result_categorias, 0);
                                while ($categoria = mysqli_fetch_assoc($result_categorias)):
                                ?>
                                    <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_producto">Descripción</label>
                            <textarea class="form-control" id="descripcion_producto" name="descripcion_producto" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="precio_producto">Precio($)</label>
                            <input type="number" class="form-control" id="precio_producto" name="precio_producto" step="0.01" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCambios()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para agregar producto -->
    <div class="modal fade" id="agregarProductoModal" tabindex="-1" aria-labelledby="agregarProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarProductoModalLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agregarProductoForm">
                        <div class="form-group mb-3">
                            <label for="nuevo_nombre_producto">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nuevo_nombre_producto" name="nombre_producto" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nuevo_id_categoria">Categoría</label>
                            <select class="form-control" id="nuevo_id_categoria" name="id_categoria" required>
                                <?php
                                mysqli_data_seek($result_categorias, 0);
                                while ($categoria = mysqli_fetch_assoc($result_categorias)):
                                ?>
                                    <option value="<?php echo $categoria['id_categoria']; ?>">
                                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nuevo_descripcion_producto">Descripción</label>
                            <textarea class="form-control" id="nuevo_descripcion_producto" name="descripcion_producto" rows="3" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nuevo_precio_producto">Precio($)</label>
                            <input type="number" class="form-control" id="nuevo_precio_producto" name="precio_producto" step="0.01" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="agregarProducto()">Agregar Producto</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <!-- SCRIPTS EDITAR y ELIMINAR PRODUCTO -->
    <script>
        $(document).ready(function() {
            $('.tabla-productos').each(function() {
                $(this).DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                    },
                    "pageLength": 10,
                    "order": [
                        [0, "asc"]
                    ],
                    "responsive": true
                });
            });
        });

        function mostrarAlerta(titulo, mensaje, tipo) {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: tipo,
                confirmButtonText: 'Aceptar'
            });
        }

        function confirmarAccion(titulo, mensaje, accionConfirmada) {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    accionConfirmada();
                }
            });
        }

        function editarProducto(id) {
            // Usar ruta absoluta desde la raíz del proyecto
            fetch('controladores/editar-producto.php?id=' + id)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    document.getElementById('id_producto').value = data.id_producto;
                    document.getElementById('nombre_producto').value = data.nombre_producto;
                    document.getElementById('id_categoria').value = data.id_categoria;
                    document.getElementById('descripcion_producto').value = data.descripcion_producto;
                    document.getElementById('precio_producto').value = data.precio_producto;

                    const modalElement = document.getElementById('editarProductoModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del producto: ' + error.message);
                });
        }

        function guardarCambios() {
            const formData = new FormData(document.getElementById('editarProductoForm'));

            fetch('controladores/editar-producto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Éxito', 'Producto actualizado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        throw new Error(data.error || 'Error al guardar los cambios');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error', 'Error al guardar los cambios: ' + error.message, 'error');
                });
        }

        function eliminarProducto(id, nombreProducto) {
            confirmarAccion(
                '¿Está seguro?',
                `¿Desea eliminar el producto "${nombreProducto}"?`,
                () => {
                    fetch('controladores/eliminar-producto.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                mostrarAlerta('Éxito', 'Producto eliminado correctamente', 'success');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                throw new Error(data.error || 'Error al eliminar el producto');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            mostrarAlerta('Error', 'Error al eliminar el producto: ' + error.message, 'error');
                        });
                }
            );
        }
    </script>
    <!-- SCRIPT AGREGAR PRODUCTO -->
    <script>
        function mostrarModalAgregar() {
            const modalElement = document.getElementById('agregarProductoModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function agregarProducto() {
            const formData = new FormData(document.getElementById('agregarProductoForm'));

            fetch('controladores/agregar-producto.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Éxito', 'Producto agregado correctamente', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        throw new Error(data.error || 'Error al agregar el producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error', 'Error al agregar el producto: ' + error.message, 'error');
                });
        }
    </script>

<?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>