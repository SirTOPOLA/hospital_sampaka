<?php
// Asegúrate de incluir tu archivo de conexión a la base de datos aquí
// Por ejemplo: require_once 'config/database.php';
// $pdo debe ser tu objeto de conexión PDO

// Ejemplo de conexión PDO si aún no lo tienes globalmente (ajusta a tu configuración real)
/*
try {
    $host = 'localhost';
    $db = 'nombre_de_tu_db';
    $user = 'usuario_db';
    $pass = 'password_db';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
*/

// Consulta para obtener todas las pruebas de hospital
$pruebas_hospital = $pdo->query("
    SELECT
        id,
        nombre_prueba,
        precio,
        fecha_registro
    FROM
        pruebas_hospital
    ORDER BY
        fecha_registro DESC
");
?>

<div id="content" class="container-fluid py-4">
    <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-3 mb-md-0">
                <i class="bi bi-microscope me-2 text-secondary"></i> Gestión de Pruebas de Hospital
            </h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="buscarPrueba"
                    placeholder="Buscar prueba...">
                <button class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal"
                    data-bs-target="#modalRegistroPrueba">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Prueba
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-hover table-bordered align-middle table-sm">
            <thead class="table-light text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Prueba</th>
                    <th>Precio</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $pruebas_hospital->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['nombre_prueba']) ?></td>
                        <td><?= number_format($p['precio'], 2) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($p['fecha_registro'])) ?></td>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEditarPrueba" data-id="<?= $p['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modalEliminarPrueba" data-id="<?= $p['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para Registrar Nueva Prueba -->
<div class="modal fade" id="modalRegistroPrueba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/guardar_prueba.php" method="POST">
            <div class="modal-header bg-secondary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2"></i> Registrar Nueva Prueba
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nombre de la Prueba <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_prueba" class="form-control" required
                            placeholder="Ej: Hemograma Completo">
                        <div class="invalid-feedback">Por favor, ingrese el nombre de la prueba.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Precio <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="precio" class="form-control" required
                            placeholder="Ej: 50.00">
                        <div class="invalid-feedback">Por favor, ingrese un precio válido.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-secondary text-white px-4">
                    <i class="bi bi-save me-1"></i> Guardar Prueba
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Editar Prueba -->
<div class="modal fade" id="modalEditarPrueba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/actualizar_prueba.php" method="POST">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Editar Prueba
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" name="id_prueba_editar" id="idPruebaEditar">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nombre de la Prueba <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_prueba_editar" id="nombrePruebaEditar" class="form-control"
                            required placeholder="Ej: Hemograma Completo">
                        <div class="invalid-feedback">Por favor, ingrese el nombre de la prueba.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Precio <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="precio_editar" id="precioEditar" class="form-control"
                            required placeholder="Ej: 50.00">
                        <div class="invalid-feedback">Por favor, ingrese un precio válido.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Actualizar Prueba
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Eliminar Prueba -->
<div class="modal fade" id="modalEliminarPrueba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0" action="api/eliminar_prueba.php" method="POST">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p>¿Estás seguro de que deseas eliminar la prueba con ID: <strong id="pruebaIdEliminar"></strong>?</p>
                <input type="hidden" name="id_prueba" id="confirmarEliminarPruebaId">
            </div>
            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
        </form>
    </div>
</div>


<script>
    // --- Lógica para el modal de Edición de Prueba ---
    const modalEditarPrueba = document.getElementById('modalEditarPrueba');
    modalEditarPrueba.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botón que disparó el modal
        const pruebaId = button.getAttribute('data-id'); // Obtener el ID de la prueba

        // Realizar una solicitud AJAX para obtener los detalles de la prueba
        fetch(`api/obtener_prueba.php?id=${pruebaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error del servidor:', data.error);
                    alert('Error al cargar los datos de la prueba: ' + data.error); // Usar alert solo para depuración en este contexto. Considera un modal personalizado.
                    return;
                }

                // Rellenar el formulario del modal con los datos recibidos
                document.getElementById('idPruebaEditar').value = data.id;
                document.getElementById('nombrePruebaEditar').value = data.nombre_prueba;
                document.getElementById('precioEditar').value = parseFloat(data.precio).toFixed(2); // Formatear a 2 decimales

            })
            .catch(error => {
                console.error('Error al obtener los detalles de la prueba:', error);
                alert('No se pudieron cargar los datos de la prueba. Intente de nuevo.'); // Usar alert solo para depuración en este contexto.
            });
    });

    // --- Lógica para el modal de Eliminación de Prueba ---
    const modalEliminarPrueba = document.getElementById('modalEliminarPrueba');
    modalEliminarPrueba.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botón que disparó el modal
        const pruebaId = button.getAttribute('data-id'); // Obtener el ID de la prueba

        // Mostrar el ID en el modal de confirmación
        document.getElementById('pruebaIdEliminar').textContent = pruebaId;
        // Establecer el ID en el input oculto del formulario de eliminación
        document.getElementById('confirmarEliminarPruebaId').value = pruebaId;
    });

    // --- Validación de formularios Bootstrap (Asegúrate de tenerla si no es global) ---
    // Esta función garantiza que la validación de Bootstrap funcione para los formularios con la clase 'needs-validation'
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // --- Filtrado de tabla (opcional, solo para el cliente) ---
    // Permite buscar y filtrar las filas de la tabla en tiempo real
    document.getElementById('buscarPrueba').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase(); // Texto de búsqueda en minúsculas
        const tableRows = document.querySelectorAll('#content table tbody tr'); // Todas las filas de la tabla

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase(); // Contenido de la fila en minúsculas
            if (rowText.includes(searchTerm)) {
                row.style.display = ''; // Mostrar la fila si coincide
            } else {
                row.style.display = 'none'; // Ocultar la fila si no coincide
            }
        });
    });
</script>