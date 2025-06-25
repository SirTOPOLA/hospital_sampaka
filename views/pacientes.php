<?php 
$stmt = $pdo->query("
  SELECT p.id, p.codigo_paciente, p.nombre, p.apellidos, p.fecha_nacimiento, p.telefono, 
         p.residencia, p.nacionalidad, u.nombre_usuario AS usuario
  FROM pacientes p
  LEFT JOIN usuarios_hospital u ON p.id_usuario = u.id
  ORDER BY p.id DESC
");
?>

<div id="content" class="container-fluid py-4">
  <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <h3 class="mb-3 mb-md-0">
        <i class="bi bi-person-vcard me-2 text-primary"></i> Gestión de Pacientes
      </h3>
      <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Buscar paciente...">
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistroPaciente">
          <i class="bi bi-person-plus me-1"></i> Nuevo Paciente
        </button>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle table-sm">
      <thead class="table-light text-nowrap">
        <tr>
          <th>ID</th>
          <th>Código</th>
          <th>Nombre Completo</th>
          <th>Teléfono</th>
          <th>Residencia</th>
          <th>Nacionalidad</th>
          <th>Usuario Responsable</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['codigo_paciente']) ?></td>
            <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></td>
            <td><?= htmlspecialchars($p['telefono']) ?></td>
            <td><?= htmlspecialchars($p['residencia']) ?></td>
            <td><?= htmlspecialchars($p['nacionalidad']) ?></td>
            <td><?= htmlspecialchars($p['usuario']) ?></td>
            <td>
              <button class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#modalEditarPaciente"
                      data-id="<?= $p['id'] ?>"
                      data-codigo="<?= htmlspecialchars($p['codigo_paciente']) ?>"
                      data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                      data-apellidos="<?= htmlspecialchars($p['apellidos']) ?>"
                      data-fecha="<?= $p['fecha_nacimiento'] ?>"
                      data-telefono="<?= htmlspecialchars($p['telefono']) ?>"
                      data-residencia="<?= htmlspecialchars($p['residencia']) ?>"
                      data-nacionalidad="<?= htmlspecialchars($p['nacionalidad']) ?>"
                      data-usuario="<?= $p['usuario'] ?>">
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>









<div class="modal fade" id="modalRegistroPaciente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/guardar_paciente.php" method="POST">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-person-plus-fill me-2"></i> Registrar Nuevo Paciente
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-upc-scan me-1"></i> Código Paciente</label>
            <input type="text" name="codigo_paciente" class="form-control" required maxlength="50">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-fill me-1"></i> Nombre</label>
            <input type="text" name="nombre" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-vcard-fill me-1"></i> Apellidos</label>
            <input type="text" name="apellidos" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-calendar-date-fill me-1"></i> Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-telephone-fill me-1"></i> Teléfono</label>
            <input type="tel" name="telefono" class="form-control" maxlength="20">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-house-fill me-1"></i> Residencia</label>
            <input type="text" name="residencia" class="form-control" maxlength="150">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-mortarboard-fill me-1"></i> Profesión</label>
            <input type="text" name="profesion" class="form-control" maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-briefcase-fill me-1"></i> Ocupación</label>
            <input type="text" name="ocupacion" class="form-control" maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-globe me-1"></i> Nacionalidad</label>
            <input type="text" name="nacionalidad" class="form-control" maxlength="50">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-bounding-box me-1"></i> Tutor</label>
            <input type="text" name="tutor" class="form-control" maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-telephone-forward-fill me-1"></i> Teléfono Tutor</label>
            <input type="text" name="telefono_tutor" class="form-control" maxlength="20">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-circle me-1"></i> Usuario Responsable</label>
            <select name="id_usuario" class="form-select" required>
              <option value="">Seleccione...</option>
              <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre_usuario']) ?></option>
              <?php endforeach; ?>
            </select>
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

<div class="modal fade" id="modalEditarPaciente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/editar_paciente.php" method="POST">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i> Editar Información del Paciente
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" name="id" id="edit-id">
        <div class="row g-3">
          <!-- Mismos campos del registro pero con ID edit-... -->
          <div class="col-md-6">
            <label class="form-label">Código Paciente</label>
            <input type="text" name="codigo_paciente" id="edit-codigo" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" id="edit-apellidos" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Fecha Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="edit-fecha" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="edit-telefono" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Residencia</label>
            <input type="text" name="residencia" id="edit-residencia" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Profesión</label>
            <input type="text" name="profesion" id="edit-profesion" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Ocupación</label>
            <input type="text" name="ocupacion" id="edit-ocupacion" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Nacionalidad</label>
            <input type="text" name="nacionalidad" id="edit-nacionalidad" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Tutor</label>
            <input type="text" name="tutor" id="edit-tutor" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono Tutor</label>
            <input type="text" name="telefono_tutor" id="edit-telefono-tutor" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Usuario Responsable</label>
            <select name="id_usuario" id="edit-usuario" class="form-select" required>
              <option value="">Seleccione...</option>
              <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre_usuario']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-arrow-repeat me-1"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>
