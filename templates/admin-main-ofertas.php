<?php
require_once 'includes/db_connection.php';

try {
    // Obtener ofertas usando mysqli
    $query_ofertas = "SELECT * FROM ofertas ORDER BY orden, dia_semana";
    $result_ofertas = mysqli_query($db, $query_ofertas);

    if (!$result_ofertas) {
        throw new Exception("Error al obtener ofertas: " . mysqli_error($db));
    }
?>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #4a3728; color: white;">
                        <h2 class="card-title">Gestión de Ofertas</h2>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarOfertaModal">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Nueva Oferta
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabla-ofertas">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Título</th>
                                        <th>Imagen</th>
                                        <th>Visible</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result_ofertas) > 0) {
                                        while ($oferta = mysqli_fetch_assoc($result_ofertas)):
                                    ?>
                                            <tr>
                                                <td data-label="Día"><?php echo htmlspecialchars($oferta['dia_semana']); ?></td>
                                                <td data-label="Título"><?php echo htmlspecialchars($oferta['titulo']); ?></td>
                                                <td data-label="Imagen">
                                                    <img src="<?php echo htmlspecialchars($oferta['imagen']); ?>"
                                                        alt="Oferta" class="img-thumbnail" style="max-width: 100px;">
                                                </td>
                                                <td data-label="Estado">
                                                    <span class="badge <?php echo $oferta['visible'] ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo $oferta['visible'] ? 'Visible' : 'No visible'; ?>
                                                    </span>
                                                </td>
                                                <td data-label="Acciones">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-outline-primary editar-oferta"
                                                            data-id="<?php echo $oferta['id']; ?>"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editarOfertaModal">
                                                            <i class="bi bi-pencil-square"></i>
                                                            <span class="d-none d-md-inline ms-1">Editar</span>
                                                        </button>
                                                        <button class="btn btn-outline-danger eliminar-oferta"
                                                            data-id="<?php echo $oferta['id']; ?>">
                                                            <i class="bi bi-trash"></i>
                                                            <span class="d-none d-md-inline ms-1">Eliminar</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        endwhile;
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay ofertas disponibles</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Oferta -->
    <div class="modal fade" id="agregarOfertaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nueva Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAgregarOferta" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="dia_semana" class="form-label">Día de la semana</label>
                            <select class="form-select" name="dia_semana" required>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="visible" checked>
                                <label class="form-check-label" for="visible">
                                    Visible
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Oferta -->
    <div class="modal fade" id="editarOfertaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarOferta" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editar_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editar_dia_semana" class="form-label">Día de la semana</label>
                            <select class="form-select" name="dia_semana" id="editar_dia_semana" required>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editar_titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" name="titulo" id="editar_titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editar_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="editar_descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editar_imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="editar_imagen" accept="image/*">
                            <small class="form-text text-muted">Dejar vacío para mantener la imagen actual</small>
                            <div id="imagen_actual" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="visible" id="editar_visible">
                                <label class="form-check-label" for="editar_visible">
                                    Visible
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card-header {
            background-color: #4a3728 !important;
            color: white !important;
        }

        .card-header h2 {
            margin-bottom: 0;
            font-size: 1.5rem;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
            margin: 0 2px;
        }

        .img-thumbnail {
            border: 1px solid #dee2e6;
            padding: 0.25rem;
            background-color: #fff;
            border-radius: 0.25rem;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#tabla-ofertas').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                "pageLength": 10,
                "responsive": true
            });
        });
    </script>

    <script>
        // Agregar Oferta
        document.getElementById('formAgregarOferta').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('controladores/agregar-oferta.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Oferta agregada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al procesar la solicitud. Por favor, inténtalo de nuevo.'
                    });
                });
        });

        // Eliminar Oferta
        document.querySelectorAll('.eliminar-oferta').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará la oferta y su imagen asociada",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Confirmar eliminación',
                            text: "¿Realmente deseas eliminar esta oferta? Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar definitivamente',
                            cancelButtonText: 'Cancelar'
                        }).then((finalResult) => {
                            if (finalResult.isConfirmed) {
                                fetch('controladores/eliminar-oferta.php', {
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
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Éxito',
                                                text: data.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: data.message
                                            });
                                        }
                                    });
                            }
                        });
                    }
                });
            });
        });

        // Editar Oferta
        document.querySelectorAll('.editar-oferta').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                fetch(`controladores/obtener-oferta.php?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error
                            });
                            return;
                        }
                        document.getElementById('editar_id').value = data.id;
                        document.getElementById('editar_dia_semana').value = data.dia_semana;
                        document.getElementById('editar_titulo').value = data.titulo;
                        document.getElementById('editar_descripcion').value = data.descripcion;
                        document.getElementById('editar_visible').checked = data.visible == 1;

                        const imagenActual = document.getElementById('imagen_actual');
                        imagenActual.innerHTML = `<img src="${data.imagen}" alt="Imagen actual" class="img-thumbnail" style="max-width: 200px;">`;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al cargar los datos de la oferta. Por favor, inténtalo de nuevo.'
                        });
                    });
            });
        });

        // Guardar Edición
        document.getElementById('formEditarOferta').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('controladores/editar-oferta.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Oferta actualizada correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                });
        });
    </script>

<?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>