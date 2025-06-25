<?php

// Consulta para listado
$sql = "SELECT u.id, u.nombre_usuario AS usuario, u.estado, p.nombre AS nombre, p.apellidos, u.rol
        FROM usuarios_hospital u
        JOIN personal p ON u.id_personal = p.id   ";
$usuarios = $pdo->query($sql);

$personal = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellidos) AS nombre_completo FROM personal")->fetchAll(PDO::FETCH_ASSOC);

?>

<div id="content" class="container-fluid py-4">
  <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <h3 class="mb-3 mb-md-0">
        <i class="bi bi-person-gear me-2 text-primary"></i> Gestión de Usuarios
      </h3>
      <div class="d-flex gap-2">
        <input type="text" class="form-control form-control-sm" placeholder="Buscar usuario...">
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistro">
          <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
        </button>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle table-sm">
      <thead class="table-light text-nowrap">
        <tr>
          <th>ID</th>
          <th>Personal</th>
          <th>Usuario</th>
          <th>Rol</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']) ?></td>
            <td><?= htmlspecialchars($u['usuario']) ?></td>
            <td><?= htmlspecialchars($u['rol']) ?></td>
            <td class="text-center">
              <button class="btn btn-sm toggle-estado-btn <?= $u['estado'] ? 'btn-success' : 'btn-danger' ?>"
                data-id="<?= $u['id'] ?>" data-estado="<?= $u['estado'] ?>">
                <i class="bi <?= $u['estado'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                <span class="estado-text badge <?= $u['estado'] ? 'bg-success' : 'bg-danger' ?>">
                  <?= $u['estado'] ? 'Activo' : 'Inactivo' ?>
                </span>
              </button>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditar"
                data-id="<?= $u['id'] ?>" data-usuario="<?= $u['usuario'] ?>" data-estado="<?= $u['estado'] ?>">
                <i class="bi bi-pencil-square"></i>
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Registro -->
 <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/guardar_usuario.php" method="POST">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-person-add me-2"></i> Registrar Nuevo Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-vcard me-1"></i> Personal</label>
            <select class="form-select" name="id_personal" required>
              <option value="">Seleccione...</option>
              <?php foreach ($personal as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre_completo']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-badge-fill me-1"></i> Nombre de usuario</label>
            <input type="text" name="nombre_usuario" class="form-control" required maxlength="25">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-shield-lock-fill me-1"></i> Contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="6">
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-gear me-1"></i> Rol</label>
            <select name="rol" class="form-select" required>
              <option value="">Seleccione...</option>
              <option value="administrador">Administrador</option>
              <option value="urgencia">Urgencia</option>
              <option value="enfermeria">Enfermería</option>
              <option value="doctor">Doctor</option>
              <option value="laboratorio">Laboratorio</option>
              <option value="medicina_interna">Medicina Interna</option>
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



<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/editar_usuario.php" method="POST">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i> Editar Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" name="id" id="edit-id">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-badge-fill me-1"></i> Nombre de usuario</label>
            <input type="text" name="nombre_usuario" id="edit-nombre" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-toggle-on me-1"></i> Estado</label>
            <select name="estado" id="edit-estado" class="form-select" required>
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-person-gear me-1"></i> Rol</label>
            <select name="rol" id="edit-rol" class="form-select" required>
              <option value="">Seleccione...</option>
              <option value="administrador">Administrador</option>
              <option value="urgencia">Urgencia</option>
              <option value="enfermeria">Enfermería</option>
              <option value="doctor">Doctor</option>
              <option value="laboratorio">Laboratorio</option>
              <option value="medicina_interna">Medicina Interna</option>
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


<script>
  // Validación
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();

  // Modal editar
  const modalEditar = document.getElementById('modalEditar');
  modalEditar.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    document.getElementById('edit-id').value = btn.getAttribute('data-id');
    document.getElementById('edit-nombre').value = btn.getAttribute('data-usuario');
    document.getElementById('edit-estado').value = btn.getAttribute('data-estado');
  });

  // Toggle estado usuario
  function activarEventosUsuarios() {
    document.querySelectorAll('.toggle-estado-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const estado = parseInt(btn.dataset.estado);
        const nuevo = estado === 1 ? 0 : 1;
        if (!confirm(`¿Estás seguro de ${nuevo ? 'activar' : 'desactivar'} este usuario?`)) return;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('estado', estado);

        try {
          const res = await fetch('api/toggle_usuario.php', { method: 'POST', body: datos });
          const data = await res.json();
          if (data.success) location.reload();
          else alert('Error al cambiar estado.');
        } catch (err) {
          console.error(err);
          alert('Error de conexión.');
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', activarEventosUsuarios);
</script>