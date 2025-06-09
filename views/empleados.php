<?php
// Consulta para listado de empleados
$sql = "SELECT id, nombre, dni, direccion, telefono FROM empleados ORDER BY nombre ASC";
$empleados = $pdo->query($sql);

// No necesitas roles ni usuarios aquí salvo que quieras vincular, pero por ahora solo empleados
?>
<div id="content" class="container-fluid py-4">

<!-- Header con título, buscador y botón nuevo empleado -->
<div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <h3 class="mb-3 mb-md-0">
      <i class="bi bi-person-badge me-2 text-primary"></i> Gestión de Empleados
    </h3>
    <div class="d-flex gap-2">
      <input id="buscarEmpleado" type="text" class="form-control form-control-sm" placeholder="Buscar empleado...">
      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistroEmpleado">
        <i class="bi bi-person-plus me-1"></i> Nuevo Empleado
      </button>
    </div>
  </div>
</div>

<!-- Tabla responsive -->
<div class="table-responsive mt-3">
  <table id="tablaEmpleados" class="table table-hover table-bordered align-middle table-sm">
    <thead class="table-light text-nowrap">
      <tr>
        <th><i class="bi bi-hash me-1 text-muted"></i>ID</th>
        <th><i class="bi bi-person me-1 text-muted"></i>Nombre</th>
        <th><i class="bi bi-card-text me-1 text-muted"></i>DNI</th>
        <th><i class="bi bi-geo-alt me-1 text-muted"></i>Dirección</th>
        <th><i class="bi bi-telephone me-1 text-muted"></i>Teléfono</th>
        <th><i class="bi bi-tools me-1 text-muted"></i>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($e = $empleados->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><?= $e['id'] ?></td>
          <td><?= htmlspecialchars($e['nombre']) ?></td>
          <td><?= htmlspecialchars($e['dni']) ?></td>
          <td><?= htmlspecialchars($e['direccion']) ?: '-' ?></td>
          <td><?= htmlspecialchars($e['telefono']) ?: '-' ?></td>
          <td class="text-nowrap">
            <button class="btn btn-sm btn-outline-warning me-1 btnEditarEmpleado" 
                    data-id="<?= $e['id'] ?>"
                    data-nombre="<?= htmlspecialchars($e['nombre'], ENT_QUOTES) ?>"
                    data-dni="<?= htmlspecialchars($e['dni'], ENT_QUOTES) ?>"
                    data-direccion="<?= htmlspecialchars($e['direccion'], ENT_QUOTES) ?>"
                    data-telefono="<?= htmlspecialchars($e['telefono'], ENT_QUOTES) ?>"
                    data-bs-toggle="modal" data-bs-target="#modalEditarEmpleado">
              <i class="bi bi-pencil-square"></i>
            </button>
            <form method="POST" action="api/eliminar_empleado.php" class="d-inline" onsubmit="return confirm('¿Eliminar empleado?');">
              <input type="hidden" name="id" value="<?= $e['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</div>

<!-- Modal Registro Empleado -->
<div class="modal fade" id="modalRegistroEmpleado" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
  <form class="modal-content needs-validation" novalidate action="api/guardar_empleado.php" method="POST">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-person-plus"></i> Registrar Empleado</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
      <div class="mb-3">
        <label for="reg-nombre" class="form-label"><i class="bi bi-person"></i> Nombre</label>
        <input type="text" id="reg-nombre" name="nombre" class="form-control" required maxlength="100" autofocus>
        <div class="invalid-feedback">Por favor ingrese el nombre.</div>
      </div>
      <div class="mb-3">
        <label for="reg-dni" class="form-label"><i class="bi bi-card-text"></i> DNI</label>
        <input type="text" id="reg-dni" name="dni" class="form-control" required maxlength="15">
        <div class="invalid-feedback">Por favor ingrese un DNI válido.</div>
      </div>
      <div class="mb-3">
        <label for="reg-direccion" class="form-label"><i class="bi bi-geo-alt"></i> Dirección</label>
        <input type="text" id="reg-direccion" name="direccion" class="form-control" maxlength="150">
      </div>
      <div class="mb-3">
        <label for="reg-telefono" class="form-label"><i class="bi bi-telephone"></i> Teléfono</label>
        <input type="tel" id="reg-telefono" name="telefono" class="form-control" maxlength="20">
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

<!-- Modal Editar Empleado -->
<div class="modal fade" id="modalEditarEmpleado" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
  <form class="modal-content needs-validation" novalidate action="api/editar_empleado.php" method="POST">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Empleado</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
      <input type="hidden" name="id" id="edit-id">
      <div class="mb-3">
        <label for="edit-nombre" class="form-label">Nombre</label>
        <input type="text" id="edit-nombre" name="nombre" class="form-control" required maxlength="100">
        <div class="invalid-feedback">Por favor ingrese el nombre.</div>
      </div>
      <div class="mb-3">
        <label for="edit-dni" class="form-label">DNI</label>
        <input type="text" id="edit-dni" name="dni" class="form-control" required maxlength="15">
        <div class="invalid-feedback">Por favor ingrese un DNI válido.</div>
      </div>
      <div class="mb-3">
        <label for="edit-direccion" class="form-label">Dirección</label>
        <input type="text" id="edit-direccion" name="direccion" class="form-control" maxlength="150">
      </div>
      <div class="mb-3">
        <label for="edit-telefono" class="form-label">Teléfono</label>
        <input type="tel" id="edit-telefono" name="telefono" class="form-control" maxlength="20">
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

<!-- JS para llenar modal editar -->
<script>
document.querySelectorAll('.btnEditarEmpleado').forEach(button => {
  button.addEventListener('click', () => {
    const modal = document.getElementById('modalEditarEmpleado');
    modal.querySelector('#edit-id').value = button.dataset.id;
    modal.querySelector('#edit-nombre').value = button.dataset.nombre;
    modal.querySelector('#edit-dni').value = button.dataset.dni;
    modal.querySelector('#edit-direccion').value = button.dataset.direccion || '';
    modal.querySelector('#edit-telefono').value = button.dataset.telefono || '';
  });
});

// Bootstrap validation example
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();

// Opcional: Filtro simple para buscar empleado en tabla
document.getElementById('buscarEmpleado').addEventListener('input', e => {
  const term = e.target.value.toLowerCase();
  document.querySelectorAll('#tablaEmpleados tbody tr').forEach(row => {
    const nombre = row.cells[1].textContent.toLowerCase();
    const dni = row.cells[2].textContent.toLowerCase();
    row.style.display = (nombre.includes(term) || dni.includes(term)) ? '' : 'none';
  });
});
</script>
