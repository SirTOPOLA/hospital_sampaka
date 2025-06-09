<?php
// Consulta para el listado
$sql = "SELECT u.id, u.nombre AS usuario, u.estado, e.nombre AS empleado, r.nombre AS rol
        FROM usuarios u
        JOIN empleados e ON u.empleado_id = e.id
        JOIN roles r ON u.rol_id = r.id";
$usuarios = $pdo->query($sql);

// Para selects del modal (guardamos en arrays para reutilizar)
$empleados = $pdo->query("SELECT id, nombre FROM empleados")->fetchAll(PDO::FETCH_ASSOC);
$roles = $pdo->query("SELECT id, nombre FROM roles")->fetchAll(PDO::FETCH_ASSOC);
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
          <th><i class="bi bi-hash me-1 text-muted"></i>ID</th>
          <th><i class="bi bi-person-badge me-1 text-muted"></i>Empleado</th>
          <th><i class="bi bi-person me-1 text-muted"></i>Usuario</th>
          <th><i class="bi bi-shield-lock me-1 text-muted"></i>Rol</th>
          <th><i class="bi bi-toggle-on me-1 text-muted"></i>Estado</th>
          <th><i class="bi bi-tools me-1 text-muted"></i>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($u = $usuarios->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['empleado']) ?></td>
            <td><?= htmlspecialchars($u['usuario']) ?></td>
            <td><?= htmlspecialchars($u['rol']) ?></td>
            <td class="text-nowrap text-center">
  <div class="d-flex align-items-center justify-content-center gap-2">
    <!-- Botón toggle activar/desactivar -->
    <button class="btn btn-sm toggle-estado-btn <?= $u['estado'] ? 'btn-success' : 'btn-danger' ?>"
            data-id="<?= $u['id'] ?>" data-estado="<?= $u['estado'] ?>">
      <i class="bi <?= $u['estado'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
      <span class="estado-text badge <?= $u['estado'] ? 'bg-success' : 'bg-danger' ?>">
        <?= $u['estado'] ? 'Activo' : 'Inactivo' ?>
      </span>
    </button>
  </div>
</td>

            <td class="text-nowrap">
              <!-- Botón editar -->
              <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditar"
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
<div class="modal fade" id="modalRegistro" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content needs-validation" novalidate action="api/guardar_usuario.php" method="POST">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-person-add"></i> Registrar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-person-vcard"></i> Empleado</label>
          <select class="form-select" name="empleado_id" required>
            <option value="">Seleccione...</option>
            <?php foreach ($empleados as $e): ?>
              <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-person"></i> Nombre de usuario</label>
          <input type="text" name="nombre" class="form-control" required maxlength="25">
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
          <input type="password" name="contrasena" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-shield-lock"></i> Rol</label>
          <select name="rol_id" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php foreach ($roles as $r): ?>
              <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-save"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" action="api/editar_usuario.php" method="POST">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="edit-id">
        <div class="mb-3">
          <label class="form-label">Nombre de usuario</label>
          <input type="text" name="nombre" id="edit-nombre" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Estado</label>
          <select name="estado" id="edit-estado" class="form-select" required>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Rol</label>
          <select name="rol_id" class="form-select" required>
            <?php foreach ($roles as $r): ?>
              <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-arrow-repeat"></i> Actualizar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Validación de formularios
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

  // Rellenar modal editar
  const modalEditar = document.getElementById('modalEditar');
  modalEditar.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    document.getElementById('edit-id').value = btn.getAttribute('data-id');
    document.getElementById('edit-nombre').value = btn.getAttribute('data-usuario');
    document.getElementById('edit-estado').value = btn.getAttribute('data-estado');
  });


  function activeUser() {
  document.querySelectorAll('.toggle-estado-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.getAttribute('data-id');
      const estadoActual = parseInt(btn.getAttribute('data-estado'), 10);
      const nuevoEstado = estadoActual === 1 ? 0 : 1;

      const confirmMsg = nuevoEstado === 1
        ? '¿Estás seguro de que deseas ACTIVAR este usuario?'
        : '¿Estás seguro de que deseas DESACTIVAR este usuario?';

      if (!confirm(confirmMsg)) return;

      const formData = new FormData();
      formData.append('id', id);
      formData.append('estado', estadoActual);

      try {
        const response = await fetch('api/toggle_usuario.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          const nuevoEstado = data.nuevo_estado;

          // Actualiza atributos y clases
          btn.setAttribute('data-estado', nuevoEstado);
          btn.classList.toggle('btn-success', nuevoEstado === 1);
          btn.classList.toggle('btn-danger', nuevoEstado === 0);
          btn.querySelector('i').className = `bi ${nuevoEstado === 1 ? 'bi-toggle-on' : 'bi-toggle-off'}`;

          // Actualiza el texto visual al lado del botón
          const spanEstado = btn.closest('td').querySelector('.estado-text');
          spanEstado.className = `estado-text badge ${nuevoEstado === 1 ? 'bg-success' : 'bg-danger'}`;
          spanEstado.textContent = nuevoEstado === 1 ? 'Activo' : 'Inactivo';
        } else {
          alert('Error: ' + (data.message || 'No se pudo cambiar el estado.'));
        }
      } catch (err) {
        console.error(err);
        alert('Error de red o del servidor.');
      }
    });
  });
}
  document.addEventListener('DOMContentLoaded', () => {

    activeUser()
  });

</script>