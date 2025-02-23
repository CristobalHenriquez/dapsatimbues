<?php
// Incluir el archivo de conexión
require_once 'includes/db_connection.php';

try {
    // Obtener categorías usando la conexión mysqli existente
    $query_categorias = "SELECT * FROM categorias ORDER BY orden ASC";
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
                        <h2 class="card-title">Gestión de Categorías</h2>
                        <button class="btn btn-success" onclick="mostrarModalAgregar()">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Nueva Categoría
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table tabla-categorias" id="tabla-categorias">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Orden</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($categoria = mysqli_fetch_assoc($result_categorias)): ?>
                                    <tr data-id="<?php echo $categoria['id_categoria']; ?>">
                                        <td><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['descripcion_categoria']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary me-2" onclick="moverCategoria(<?php echo $categoria['id_categoria']; ?>, 'up')">
                                                <i class="bi bi-arrow-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="moverCategoria(<?php echo $categoria['id_categoria']; ?>, 'down')">
                                                <i class="bi bi-arrow-down"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary" onclick="editarCategoria(<?php echo $categoria['id_categoria']; ?>)">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarCategoria(<?php echo $categoria['id_categoria']; ?>, '<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-labelledby="editarCategoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarCategoriaModalLabel">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editarCategoriaForm">
                        <input type="hidden" id="id_categoria" name="id_categoria">
                        <div class="form-group">
                            <label for="nombre_categoria">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_categoria">Descripción</label>
                            <textarea class="form-control" id="descripcion_categoria" name="descripcion_categoria" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" onclick="guardarCambiosCategoria()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar categoría -->
    <div class="modal fade" id="agregarCategoriaModal" tabindex="-1" aria-labelledby="agregarCategoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarCategoriaModalLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agregarCategoriaForm">
                        <div class="form-group mb-3">
                            <label for="nuevo_nombre_categoria">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nuevo_nombre_categoria" name="nombre_categoria" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nuevo_descripcion_categoria">Descripción</label>
                            <textarea class="form-control" id="nuevo_descripcion_categoria" name="descripcion_categoria" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="agregarCategoria()">Agregar Categoría</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabla-categorias').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                "pageLength": 10,
                "order": [[2, "asc"]],
                "columnDefs": [
                    { "orderable": false, "targets": [2, 3] }
                ],
                "responsive": true
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

        function editarCategoria(id) {
            fetch('controladores/editar-categoria.php?id=' + id)
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

                    document.getElementById('id_categoria').value = data.id_categoria;
                    document.getElementById('nombre_categoria').value = data.nombre_categoria;
                    document.getElementById('descripcion_categoria').value = data.descripcion_categoria;

                    const modalElement = document.getElementById('editarCategoriaModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error', 'Error al cargar los datos de la categoría: ' + error.message, 'error');
                });
        }

        function guardarCambiosCategoria() {
            const formData = new FormData(document.getElementById('editarCategoriaForm'));

            fetch('controladores/editar-categoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Éxito', 'Categoría actualizada correctamente', 'success');
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

        function eliminarCategoria(id, nombreCategoria) {
            confirmarAccion(
                '¿Está seguro?',
                `¿Desea eliminar la categoría "${nombreCategoria}"?`,
                () => {
                    fetch('controladores/eliminar-categoria.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarAlerta('Éxito', 'Categoría eliminada correctamente', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            throw new Error(data.error || 'Error al eliminar la categoría');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mostrarAlerta('Error', 'Error al eliminar la categoría: ' + error.message, 'error');
                    });
                }
            );
        }

        function mostrarModalAgregar() {
            const modalElement = document.getElementById('agregarCategoriaModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function agregarCategoria() {
            const formData = new FormData(document.getElementById('agregarCategoriaForm'));

            fetch('controladores/agregar-categoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Éxito', 'Categoría agregada correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.error || 'Error al agregar la categoría');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error', 'Error al agregar la categoría: ' + error.message, 'error');
            });
        }

        function moverCategoria(id, direccion) {
            fetch('controladores/mover-categoria.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, direccion: direccion })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    throw new Error(data.error || 'Error al mover la categoría');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error', 'Error al mover la categoría: ' + error.message, 'error');
            });
        }
    </script>

<?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>