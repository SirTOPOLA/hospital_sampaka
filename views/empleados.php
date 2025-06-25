<?php
// Consulta para listado de personal
$sql = "SELECT id, codigo_personal, nombre, apellidos, residencia, telefono, cargo FROM personal ORDER BY nombre ASC";
$personal = $pdo->query($sql);
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
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistroPersonal">
          <i class="bi bi-person-plus me-1"></i> Nuevo Empleado
        </button>
      </div>
    </div>
  </div>

  <!-- Tabla responsive -->
  <div class="table-responsive mt-3">
    <table id="tablaPersonal" class="table table-hover table-bordered align-middle table-sm">
      <thead class="table-light text-nowrap">
        <tr>
          <th><i class="bi bi-hash me-1 text-muted"></i>ID</th>
          <th><i class="bi bi-person-vcard me-1 text-muted"></i>Código</th>
          <th><i class="bi bi-person me-1 text-muted"></i>Nombre Completo</th>
          <th><i class="bi bi-geo-alt me-1 text-muted"></i>Residencia</th>
          <th><i class="bi bi-telephone me-1 text-muted"></i>Teléfono</th>
          <th><i class="bi bi-briefcase me-1 text-muted"></i>Cargo</th>
          <th><i class="bi bi-tools me-1 text-muted"></i>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = $personal->fetch(PDO::FETCH_ASSOC)): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['codigo_personal']) ?></td>
            <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></td>
            <td><?= htmlspecialchars($p['residencia']) ?: '-' ?></td>
            <td><?= htmlspecialchars($p['telefono']) ?: '-' ?></td>
            <td><?= htmlspecialchars($p['cargo']) ?: '-' ?></td>
            <td class="text-nowrap">
              <button class="btn btn-sm btn-outline-warning me-1 btnEditarPersonal" data-id="<?= $p['id'] ?>"
                data-codigo="<?= htmlspecialchars($p['codigo_personal'], ENT_QUOTES) ?>"
                data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                data-apellidos="<?= htmlspecialchars($p['apellidos'], ENT_QUOTES) ?>"
                data-residencia="<?= htmlspecialchars($p['residencia'], ENT_QUOTES) ?>"
                data-telefono="<?= htmlspecialchars($p['telefono'], ENT_QUOTES) ?>"
                data-cargo="<?= htmlspecialchars($p['cargo'], ENT_QUOTES) ?>" data-bs-toggle="modal"
                data-bs-target="#modalEditarPersonal">
                <i class="bi bi-pencil-square"></i>
              </button>
              <form method="POST" action="api/eliminar_personal.php" class="d-inline"
                onsubmit="return confirm('¿Eliminar registro?');">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

<!-- Modal Registro Personal -->
<div class="modal fade" id="modalRegistroPersonal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/guardar_personal.php" method="POST">
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-person-plus-fill me-2"></i> Registro de Personal
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <p class="text-muted mb-4">
          <i class="bi bi-info-circle"></i> Complete todos los campos obligatorios marcados con un <strong>*</strong>.
        </p>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="reg-codigo" class="form-label"><i class="bi bi-upc-scan me-1"></i> Código <span class="text-danger">*</span></label>
            <input type="text" id="reg-codigo" name="codigo_personal" class="form-control" required maxlength="50">
          </div>
          <div class="col-md-6">
            <label for="reg-nombre" class="form-label"><i class="bi bi-person-fill me-1"></i> Nombre <span class="text-danger">*</span></label>
            <input type="text" id="reg-nombre" name="nombre" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label for="reg-apellidos" class="form-label"><i class="bi bi-person-vcard-fill me-1"></i> Apellidos <span class="text-danger">*</span></label>
            <input type="text" id="reg-apellidos" name="apellidos" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label for="reg-residencia" class="form-label"><i class="bi bi-house-door-fill me-1"></i> Residencia</label>
            <input type="text" id="reg-residencia" name="residencia" class="form-control" maxlength="150">
          </div>
          <div class="col-md-6">
            <label for="reg-telefono" class="form-label"><i class="bi bi-telephone-fill me-1"></i> Teléfono</label>
            <input type="tel" id="reg-telefono" name="telefono" class="form-control" maxlength="20">
          </div>
          <div class="col-md-6">
            <label for="reg-cargo" class="form-label"><i class="bi bi-briefcase-fill me-1"></i> Cargo</label>
            <input type="text" id="reg-cargo" name="cargo" class="form-control" maxlength="100">
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
        <button type="submit" class="btn btn-success px-4">
          <i class="bi bi-check2-circle me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>



<!-- Modal Editar Personal -->
<div class="modal fade" id="modalEditarPersonal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form class="modal-content shadow-lg rounded-4 border-0 needs-validation" novalidate action="api/editar_personal.php" method="POST">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i> Editar Información de Personal
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" name="id" id="edit-id">

        <div class="row g-3">
          <div class="col-md-6">
            <label for="edit-codigo" class="form-label"><i class="bi bi-upc-scan me-1"></i> Código Personal</label>
            <input type="text" id="edit-codigo" name="codigo_personal" class="form-control" required maxlength="50">
          </div>
          <div class="col-md-6">
            <label for="edit-nombre" class="form-label"><i class="bi bi-person-fill me-1"></i> Nombre</label>
            <input type="text" id="edit-nombre" name="nombre" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label for="edit-apellidos" class="form-label"><i class="bi bi-person-vcard-fill me-1"></i> Apellidos</label>
            <input type="text" id="edit-apellidos" name="apellidos" class="form-control" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label for="edit-residencia" class="form-label"><i class="bi bi-house-door-fill me-1"></i> Residencia</label>
            <input type="text" id="edit-residencia" name="residencia" class="form-control" maxlength="150">
          </div>
          <div class="col-md-6">
            <label for="edit-telefono" class="form-label"><i class="bi bi-telephone-fill me-1"></i> Teléfono</label>
            <input type="tel" id="edit-telefono" name="telefono" class="form-control" maxlength="20">
          </div>
          <div class="col-md-6">
            <label for="edit-cargo" class="form-label"><i class="bi bi-briefcase-fill me-1"></i> Cargo</label>
            <input type="text" id="edit-cargo" name="cargo" class="form-control" maxlength="100">
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




<!-- JS para llenar modal editar -->
<script>
  
document.querySelectorAll('.btnEditarPersonal').forEach(button => {
  button.addEventListener('click', () => {
    const modal = document.getElementById('modalEditarPersonal');
    modal.querySelector('[name="id"]').value = button.dataset.id;
    modal.querySelector('[name="codigo_personal"]').value = button.dataset.codigo;
    modal.querySelector('[name="nombre"]').value = button.dataset.nombre;
    modal.querySelector('[name="apellidos"]').value = button.dataset.apellidos;
    modal.querySelector('[name="residencia"]').value = button.dataset.residencia;
    modal.querySelector('[name="telefono"]').value = button.dataset.telefono;
    modal.querySelector('[name="cargo"]').value = button.dataset.cargo;
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

