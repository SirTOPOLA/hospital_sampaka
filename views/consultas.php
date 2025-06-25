<?php
$consultas = $pdo->query("
  SELECT c.id, c.motivo_consulta, c.temperatura, c.pulso, c.fecha_registro,
         p.nombre, p.apellidos, p.codigo_paciente,
         u.nombre_usuario
  FROM consulta c
  LEFT JOIN pacientes p ON c.id_paciente = p.id
  LEFT JOIN usuarios_hospital u ON c.id_usuario = u.id
  ORDER BY c.fecha_registro DESC
");
?>

<div id="content" class="container-fluid py-4">
    <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-3 mb-md-0">
                <i class="bi bi-clipboard2-pulse me-2 text-primary"></i> Historial de Consultas
            </h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Buscar paciente...">
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistroConsulta">
                    <i class="bi bi-clipboard-plus me-1"></i> Nueva Consulta
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle table-sm">
            <thead class="table-light text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Motivo</th>
                    <th>Temperatura</th>
                    <th>Pulso</th>
                    <th>Registrado Por</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $consultas->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?><br>
                            <span class="badge bg-secondary"><?= htmlspecialchars($c['codigo_paciente']) ?></span>
                        </td>
                        <td><?= htmlspecialchars(substr($c['motivo_consulta'], 0, 40)) ?>...</td>
                        <td><?= $c['temperatura'] ?> °C</td>
                        <td><?= $c['pulso'] ?> bpm</td>
                        <td><?= htmlspecialchars($c['nombre_usuario']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($c['fecha_registro'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEditarConsulta" data-id="<?= $c['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modalRegistroConsulta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/guardar_consulta.php" method="POST">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-clipboard-plus me-2"></i> Registrar Nueva Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Buscar Paciente</label>
                        <input type="text" id="buscadorPaciente" class="form-control"
                            placeholder="Buscar por nombre, código o apellido..." autocomplete="off">
                        <div id="resultadosPacientes" class="list-group mt-1"
                            style="max-height: 200px; overflow-y: auto;"></div>
                        <input type="hidden" name="id_paciente" id="idPacienteSeleccionado" required>
                        <div class="invalid-feedback">Debe seleccionar un paciente de la lista.</div>
                    </div>


                    <div class="col-md-12">
                        <label class="form-label">Motivo de Consulta</label>
                        <input type="text" name="motivo_consulta" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Temperatura (°C)</label>
                        <input type="number" step="0.1" name="temperatura" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pulso (bpm)</label>
                        <input type="number" name="pulso" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Frecuencia Cardíaca</label>
                        <input type="number" name="frecuencia_cardiaca" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Frecuencia Respiratoria</label>
                        <input type="number" name="frecuencia_respiratoria" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tensión Arterial</label>
                        <input type="text" name="tension_arterial" class="form-control" placeholder="Ej: 120/80">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Saturación O<sub>2</sub> (%)</label>
                        <input type="number" step="0.1" name="saturacion_oxigeno" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">IMC</label>
                        <input type="number" step="0.01" name="masa_indice_corporal" class="form-control">
                    </div>
                </div>
                <hr class="my-4">
                <h5 class="fw-bold text-secondary">Detalles de la Consulta</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">¿Opera actualmente?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="operacion" value="1" id="operacion">
                            <label class="form-check-label" for="operacion">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">¿Orina sin dificultad?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="orina" value="1" id="orina">
                            <label class="form-check-label" for="orina">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">¿Defeca regularmente?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="defeca" value="1" id="defeca">
                            <label class="form-check-label" for="defeca">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Intervalo entre defecaciones (días)</label>
                        <input type="number" name="intervalo_defecacion_dias" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">¿Duerme bien?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="duerme_bien" value="1"
                                id="duerme_bien">
                            <label class="form-check-label" for="duerme_bien">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Horas de sueño promedio</label>
                        <input type="number" name="horas_sueno" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">¿Es alérgico a algo?</label>
                        <input type="text" name="alergico" class="form-control" placeholder="Ej: Penicilina">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Antecedentes patológicos personales</label>
                        <textarea class="form-control" name="antecedentes_patologicos" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Antecedentes patológicos familiares</label>
                        <textarea class="form-control" name="antecedentes_patologicos_familiares" rows="2"></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
 


<div class="modal fade" id="modalEditarConsulta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate
            action="api/actualizar_consulta.php" method="POST">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Editar Consulta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" name="id_consulta_editar" id="idConsultaEditar">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Paciente</label>
                        <input type="text" id="pacienteNombreEditar" class="form-control" readonly>
                        </div>

                    <div class="col-md-12">
                        <label class="form-label">Motivo de Consulta</label>
                        <input type="text" name="motivo_consulta_editar" id="motivoConsultaEditar" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Temperatura (°C)</label>
                        <input type="number" step="0.1" name="temperatura_editar" id="temperaturaEditar" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pulso (bpm)</label>
                        <input type="number" name="pulso_editar" id="pulsoEditar" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Frecuencia Cardíaca</label>
                        <input type="number" name="frecuencia_cardiaca_editar" id="frecuenciaCardiacaEditar" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Frecuencia Respiratoria</label>
                        <input type="number" name="frecuencia_respiratoria_editar" id="frecuenciaRespiratoriaEditar" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tensión Arterial</label>
                        <input type="text" name="tension_arterial_editar" id="tensionArterialEditar" class="form-control" placeholder="Ej: 120/80">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Saturación O<sub>2</sub> (%)</label>
                        <input type="number" step="0.1" name="saturacion_oxigeno_editar" id="saturacionOxigenoEditar" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Peso (kg)</label>
                        <input type="number" step="0.01" name="peso_editar" id="pesoEditar" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">IMC</label>
                        <input type="number" step="0.01" name="masa_indice_corporal_editar" id="masaIndiceCorporalEditar" class="form-control">
                    </div>
                </div>
                <hr class="my-4">
                <h5 class="fw-bold text-secondary">Detalles de la Consulta</h5>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">¿Opera actualmente?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="operacion_editar" value="1" id="operacionEditar">
                            <label class="form-check-label" for="operacionEditar">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">¿Orina sin dificultad?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="orina_editar" value="1" id="orinaEditar">
                            <label class="form-check-label" for="orinaEditar">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">¿Defeca regularmente?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="defeca_editar" value="1" id="defecaEditar">
                            <label class="form-check-label" for="defecaEditar">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Intervalo entre defecaciones (días)</label>
                        <input type="number" name="intervalo_defecacion_dias_editar" id="intervaloDefecacionDiasEditar" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">¿Duerme bien?</label><br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="duerme_bien_editar" value="1" id="duermeBienEditar">
                            <label class="form-check-label" for="duermeBienEditar">Sí</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Horas de sueño promedio</label>
                        <input type="number" name="horas_sueno_editar" id="horasSuenoEditar" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">¿Es alérgico a algo?</label>
                        <input type="text" name="alergico_editar" id="alergicoEditar" class="form-control" placeholder="Ej: Penicilina">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Antecedentes patológicos personales</label>
                        <textarea class="form-control" name="antecedentes_patologicos_editar" id="antecedentesPatologicosEditar" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Antecedentes patológicos familiares</label>
                        <textarea class="form-control" name="antecedentes_patologicos_familiares_editar" id="antecedentesPatologicosFamiliaresEditar" rows="2"></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tu script actual para el buscador de pacientes va aquí (ya lo tienes)

    // --- Lógica para el modal de edición de consultas ---
    const modalEditarConsulta = document.getElementById('modalEditarConsulta');
    modalEditarConsulta.addEventListener('show.bs.modal', function (event) {
        // Botón que disparó el modal
        const button = event.relatedTarget;
        // Extrae la información de los atributos data-*
        const consultaId = button.getAttribute('data-id');

        // Realiza una solicitud AJAX para obtener los detalles de la consulta
        fetch(`api/obtener_consulta.php?id=${consultaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Error del servidor:', data.error);
                    alert('Error al cargar los datos de la consulta: ' + data.error);
                    return;
                }
                
                // Rellena el formulario del modal con los datos recibidos
                document.getElementById('idConsultaEditar').value = data.id;
                document.getElementById('pacienteNombreEditar').value = `${data.nombre} ${data.apellidos} - ${data.codigo_paciente}`;
                document.getElementById('motivoConsultaEditar').value = data.motivo_consulta;
                document.getElementById('temperaturaEditar').value = data.temperatura;
                document.getElementById('pulsoEditar').value = data.pulso;
                document.getElementById('frecuenciaCardiacaEditar').value = data.frecuencia_cardiaca;
                document.getElementById('frecuenciaRespiratoriaEditar').value = data.frecuencia_respiratoria;
                document.getElementById('tensionArterialEditar').value = data.tension_arterial;
                document.getElementById('saturacionOxigenoEditar').value = data.saturacion_oxigeno;
                document.getElementById('pesoEditar').value = data.peso;
                document.getElementById('masaIndiceCorporalEditar').value = data.masa_indice_corporal;

                // Checkboxes
                document.getElementById('operacionEditar').checked = data.operacion == 1;
                document.getElementById('orinaEditar').checked = data.orina == 1;
                document.getElementById('defecaEditar').checked = data.defeca == 1;
                document.getElementById('duermeBienEditar').checked = data.duerme_bien == 1;

                // Campos de texto para detalles
                document.getElementById('intervaloDefecacionDiasEditar').value = data.intervalo_defecacion_dias;
                document.getElementById('horasSuenoEditar').value = data.horas_sueno;
                document.getElementById('alergicoEditar').value = data.alergico;
                document.getElementById('antecedentesPatologicosEditar').value = data.antecedentes_patologicos;
                document.getElementById('antecedentesPatologicosFamiliaresEditar').value = data.antecedentes_patologicos_familiares;

            })
            .catch(error => {
                console.error('Error al obtener los detalles de la consulta:', error);
                alert('No se pudieron cargar los datos de la consulta. Intente de nuevo.');
            });
    });

    // Añadir validación de Bootstrap si no la tienes globalmente
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
 
    document.getElementById('buscadorPaciente').addEventListener('input', function () {
        const input = this.value.trim();
        const resultados = document.getElementById('resultadosPacientes');

        if (input.length < 2) {
            resultados.innerHTML = '';
            return;
        }

        const formData = new FormData();
        formData.append('busqueda', input);

        fetch('api/buscar_pacientes.php', {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (!res.ok) throw new Error('Error en la respuesta del servidor');
                return res.json();
            })
            .then(data => {
                resultados.innerHTML = '';

                // Verificamos si el resultado es un array válido
                if (!Array.isArray(data) || data.length === 0) {
                    resultados.innerHTML = '<div class="list-group-item text-muted">Sin resultados</div>';
                    return;
                }

                data.forEach(p => {
                    if (!p || !p.id_paciente || !p.nombre || !p.apellido || !p.codigo) return; // validación por cada objeto

                    const item = document.createElement('div');
                    item.className = 'list-group-item list-group-item-action';
                    item.style.cursor = 'pointer';
                    item.innerHTML = `
        <div class="form-check">
          <input class="form-check-input" type="radio" name="pacienteRadio" value="${p.id_paciente}" id="paciente${p.id_paciente}">
          <label class="form-check-label w-100" for="paciente${p.id_paciente}">
            ${p.nombre} ${p.apellido} - <small class="text-muted">${p.codigo}</small>
          </label>
        </div>
      `;
                    item.addEventListener('click', () => {
                        document.getElementById('buscadorPaciente').value = `${p.nombre} ${p.apellido} - ${p.codigo}`;
                        document.getElementById('idPacienteSeleccionado').value = p.id_paciente;
                        resultados.innerHTML = '';
                    });
                    resultados.appendChild(item);
                });
            })
            .catch(error => {
                console.error('Error al buscar pacientes:', error);
                resultados.innerHTML = '<div class="list-group-item text-danger">Error al cargar resultados</div>';
            });
    });
</script>