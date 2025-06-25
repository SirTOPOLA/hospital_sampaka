<?php
// Asegúrate de incluir tu archivo de conexión a la base de datos aquí
// Por ejemplo: require_once 'config/database.php';
// $pdo debe ser tu objeto de conexión PDO

// Ejemplo de conexión PDO si aún no lo tienes globalmente
// ESTO ES SOLO UN EJEMPLO, AJUSTA A TU CONFIGURACIÓN REAL
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

// Consulta para obtener todas las analíticas con información relacionada
$analiticas = $pdo->query("
    SELECT
        a.id,
        a.resultado,
        a.estado,
        a.fecha_registro,
        a.pagado,
        p.nombre AS nombre_paciente,
        p.apellidos AS apellidos_paciente,
        p.codigo_paciente,
        uh.nombre_usuario,
        tp.nombre_prueba,
        c.motivo_consulta AS consulta_motivo
    FROM
        analiticas a
    LEFT JOIN
        pacientes p ON a.id_paciente = p.id
    LEFT JOIN
        usuarios_hospital uh ON a.id_usuario = uh.id
    LEFT JOIN
        pruebas_hospital tp ON a.id_prueba = tp.id
    LEFT JOIN
        consulta c ON a.id_consulta = c.id
    ORDER BY
        a.fecha_registro DESC
");
?>

<div id="content" class="container-fluid py-4">
    <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-3 mb-md-0">
                <i class="bi bi-bar-chart-fill me-2 text-info"></i> Historial de Analíticas
            </h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="buscarAnalitica" placeholder="Buscar analítica...">
                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalRegistroAnalitica">
                    <i class="bi bi-file-earmark-plus me-1"></i> Nueva Analítica
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-hover table-bordered align-middle table-sm">
            <thead class="table-light text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Prueba</th>
                    <th>Consulta Asoc.</th>
                    <th>Resultado</th>
                    <th>Estado</th>
                    <th>Pagado</th>
                    <th>Registrado Por</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($a = $analiticas->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['id']) ?></td>
                        <td>
                            <?= htmlspecialchars($a['nombre_paciente'] . ' ' . $a['apellidos_paciente']) ?><br>
                            <span class="badge bg-secondary"><?= htmlspecialchars($a['codigo_paciente']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($a['nombre_prueba']) ?></td>
                        <td><?= htmlspecialchars($a['consulta_motivo'] ? $a['consulta_motivo'] . ' (ID: ' . $a['id_consulta'] . ')' : 'N/A') ?></td>
                        <td><?= htmlspecialchars(substr($a['resultado'], 0, 50)) ?><?php echo (strlen($a['resultado']) > 50 ? '...' : ''); ?></td>
                        <td>
                            <?php
                            $estado_class = '';
                            switch ($a['estado']) {
                                case 'Pendiente':
                                    $estado_class = 'bg-warning text-dark';
                                    break;
                                case 'Completado':
                                    $estado_class = 'bg-success';
                                    break;
                                case 'En Proceso':
                                    $estado_class = 'bg-primary';
                                    break;
                                case 'Cancelado':
                                    $estado_class = 'bg-danger';
                                    break;
                                default:
                                    $estado_class = 'bg-secondary';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $estado_class ?>"><?= htmlspecialchars($a['estado']) ?></span>
                        </td>
                        <td>
                            <?php if ($a['pagado']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Sí</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> No</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($a['nombre_usuario']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($a['fecha_registro'])) ?></td>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEditarAnalitica" data-id="<?= $a['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modalEliminarAnalitica" data-id="<?= $a['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>



<!-- Modal para Registrar Nueva Analítica -->
<div class="modal fade" id="modalRegistroAnalitica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/guardar_analitica.php" method="POST">
            <div class="modal-header bg-info text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-plus me-2"></i> Registrar Nueva Analítica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Buscar Paciente <span class="text-danger">*</span></label>
                        <input type="text" id="buscadorPacienteAnalitica" class="form-control"
                            placeholder="Buscar por nombre, código o apellido..." autocomplete="off" required>
                        <div id="resultadosPacientesAnalitica" class="list-group mt-1"
                            style="max-height: 200px; overflow-y: auto;"></div>
                        <input type="hidden" name="id_paciente" id="idPacienteSeleccionadoAnalitica" required>
                        <div class="invalid-feedback">Debe seleccionar un paciente de la lista.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Prueba <span class="text-danger">*</span></label>
                        <select name="id_prueba" id="tipoPruebaAnalitica" class="form-select" required>
                            <option value="">Seleccione una prueba...</option>
                            <!-- Opciones cargadas por JavaScript desde api/obtener_pruebas_hospital.php -->
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un tipo de prueba.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Resultado</label>
                        <textarea name="resultado" class="form-control" rows="3"
                            placeholder="Ingrese los resultados de la analítica"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="form-select" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Completado">Completado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un estado.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consulta Asociada (ID)</label>
                        <input type="number" name="id_consulta" class="form-control"
                            placeholder="Opcional: ID de la consulta">
                        <small class="form-text text-muted">Ingrese el ID de una consulta existente si aplica.</small>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="pagado" value="1" id="pagadoAnalitica">
                            <label class="form-check-label" for="pagadoAnalitica">Marcar como Pagado</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-info text-white px-4">
                    <i class="bi bi-save me-1"></i> Guardar Analítica
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Editar Analítica -->
<div class="modal fade" id="modalEditarAnalitica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/actualizar_analitica.php" method="POST">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Editar Analítica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" name="id_analitica_editar" id="idAnaliticaEditar">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Paciente</label>
                        <input type="text" id="pacienteAnaliticaEditar" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tipo de Prueba <span class="text-danger">*</span></label>
                        <select name="id_prueba_editar" id="tipoPruebaAnaliticaEditar" class="form-select" required>
                            <option value="">Seleccione una prueba...</option>
                            <!-- Opciones cargadas por JavaScript -->
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un tipo de prueba.</div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Resultado</label>
                        <textarea name="resultado_editar" id="resultadoAnaliticaEditar" class="form-control" rows="3"
                            placeholder="Ingrese los resultados de la analítica"></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado_editar" id="estadoAnaliticaEditar" class="form-select" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Completado">Completado</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                        <div class="invalid-feedback">Debe seleccionar un estado.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Consulta Asociada (ID)</label>
                        <input type="number" name="id_consulta_editar" id="idConsultaAnaliticaEditar" class="form-control"
                            placeholder="Opcional: ID de la consulta">
                        <small class="form-text text-muted">Ingrese el ID de una consulta existente si aplica.</small>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="pagado_editar" value="1" id="pagadoAnaliticaEditar">
                            <label class="form-check-label" for="pagadoAnaliticaEditar">Marcar como Pagado</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Actualizar Analítica
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Eliminar Analítica -->
<div class="modal fade" id="modalEliminarAnalitica" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0" action="api/eliminar_analitica.php" method="POST">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p>¿Estás seguro de que deseas eliminar la analítica con ID: <strong id="analiticaIdEliminar"></strong>?</p>
                <input type="hidden" name="id_analitica" id="confirmarEliminarAnaliticaId">
            </div>
            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
        </form>
    </div>
</div>



<script>
    // --- Lógica de Búsqueda de Pacientes para Nueva Analítica (Adaptada) ---
    // Usamos un ID diferente para no interferir con el buscador de consultas
    document.getElementById('buscadorPacienteAnalitica').addEventListener('input', function () {
        const input = this.value.trim();
        const resultados = document.getElementById('resultadosPacientesAnalitica');

        if (input.length < 2) {
            resultados.innerHTML = '';
            return;
        }

        const formData = new FormData();
        formData.append('busqueda', input);

        fetch('api/buscar_pacientes.php', { // Reutilizamos el mismo API de búsqueda de pacientes
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Error en la respuesta del servidor');
                return res.json();
            })
            .then(data => {
                resultados.innerHTML = '';
                if (!Array.isArray(data) || data.length === 0) {
                    resultados.innerHTML = '<div class="list-group-item text-muted">Sin resultados</div>';
                    return;
                }

                data.forEach(p => {
                    if (!p || !p.id_paciente || !p.nombre || !p.apellido || !p.codigo) return;

                    const item = document.createElement('div');
                    item.className = 'list-group-item list-group-item-action';
                    item.style.cursor = 'pointer';
                    item.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pacienteRadioAnalitica" value="${p.id_paciente}" id="pacienteAnalitica${p.id_paciente}">
                            <label class="form-check-label w-100" for="pacienteAnalitica${p.id_paciente}">
                                ${p.nombre} ${p.apellido} - <small class="text-muted">${p.codigo}</small>
                            </label>
                        </div>
                    `;
                    item.addEventListener('click', () => {
                        document.getElementById('buscadorPacienteAnalitica').value = `${p.nombre} ${p.apellido} - ${p.codigo}`;
                        document.getElementById('idPacienteSeleccionadoAnalitica').value = p.id_paciente;
                        resultados.innerHTML = '';
                        // Marcar el campo de paciente como válido si se seleccionó uno
                        document.getElementById('buscadorPacienteAnalitica').classList.remove('is-invalid');
                        document.getElementById('idPacienteSeleccionadoAnalitica').classList.remove('is-invalid');
                    });
                    resultados.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Error al buscar pacientes para analítica:', error);
                resultados.innerHTML = '<div class="list-group-item text-danger">Error al cargar resultados</div>';
            });
    });

    // --- Cargar Tipos de Prueba en Dropdowns ---
    function cargarTiposPrueba() {
        fetch('api/obtener_pruebas_hospital.php')
            .then(response => {
                if (!response.ok) throw new Error('Error al cargar tipos de prueba.');
                return response.json();
            })
            .then(data => {
                const selectRegistro = document.getElementById('tipoPruebaAnalitica');
                const selectEditar = document.getElementById('tipoPruebaAnaliticaEditar');

                // Limpiar opciones existentes (excepto la primera "Seleccione...")
                selectRegistro.querySelectorAll('option:not([value=""])').forEach(opt => opt.remove());
                selectEditar.querySelectorAll('option:not([value=""])').forEach(opt => opt.remove());

                data.forEach(prueba => {
                    const optionRegistro = document.createElement('option');
                    optionRegistro.value = prueba.id;
                    optionRegistro.textContent = prueba.nombre;
                    selectRegistro.appendChild(optionRegistro);

                    const optionEditar = document.createElement('option');
                    optionEditar.value = prueba.id;
                    optionEditar.textContent = prueba.nombre;
                    selectEditar.appendChild(optionEditar);
                });
            })
            .catch(error => {
                console.error('Error al cargar tipos de prueba:', error);
                // Aquí podrías mostrar un mensaje de error en la UI
            });
    }

    // Cargar tipos de prueba al cargar la página
    document.addEventListener('DOMContentLoaded', cargarTiposPrueba);


    // --- Lógica para el modal de Edición de Analítica ---
    const modalEditarAnalitica = document.getElementById('modalEditarAnalitica');
    modalEditarAnalitica.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botón que disparó el modal
        const analiticaId = button.getAttribute('data-id');

        fetch(`api/obtener_analitica.php?id=${analiticaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error del servidor:', data.error);
                    alert('Error al cargar los datos de la analítica: ' + data.error);
                    return;
                }

                // Rellenar el formulario del modal con los datos recibidos
                document.getElementById('idAnaliticaEditar').value = data.id;
                document.getElementById('pacienteAnaliticaEditar').value = `${data.nombre_paciente} ${data.apellidos_paciente} - ${data.codigo_paciente}`;
                document.getElementById('tipoPruebaAnaliticaEditar').value = data.id_prueba;
                document.getElementById('resultadoAnaliticaEditar').value = data.resultado;
                document.getElementById('estadoAnaliticaEditar').value = data.estado;
                document.getElementById('idConsultaAnaliticaEditar').value = data.id_consulta || ''; // Puede ser nulo
                document.getElementById('pagadoAnaliticaEditar').checked = data.pagado == 1;

            })
            .catch(error => {
                console.error('Error al obtener los detalles de la analítica:', error);
                alert('No se pudieron cargar los datos de la analítica. Intente de nuevo.');
            });
    });

    // --- Lógica para el modal de Eliminación de Analítica ---
    const modalEliminarAnalitica = document.getElementById('modalEliminarAnalitica');
    modalEliminarAnalitica.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botón que disparó el modal
        const analiticaId = button.getAttribute('data-id');

        document.getElementById('analiticaIdEliminar').textContent = analiticaId;
        document.getElementById('confirmarEliminarAnaliticaId').value = analiticaId;
    });

    // --- Validación de formularios Bootstrap (Asegúrate de tenerlo si no es global) ---
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    // Validar específicamente el campo de paciente en el modal de registro
                    if (form.id === 'modalRegistroAnalitica') {
                        const idPacienteInput = document.getElementById('idPacienteSeleccionadoAnalitica');
                        const buscadorPacienteInput = document.getElementById('buscadorPacienteAnalitica');
                        if (!idPacienteInput.value) {
                            idPacienteInput.setCustomValidity('Debe seleccionar un paciente.');
                            buscadorPacienteInput.classList.add('is-invalid');
                        } else {
                            idPacienteInput.setCustomValidity('');
                            buscadorPacienteInput.classList.remove('is-invalid');
                        }
                    }


                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // --- Filtrado de tabla (opcional, solo para el cliente) ---
    document.getElementById('buscarAnalitica').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#content table tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

</script>




 
 