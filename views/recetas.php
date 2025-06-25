<?php
// Asegúrate de incluir tu archivo de conexión a la base de datos aquí
// Por ejemplo: require_once 'config/database.php';
// $pdo debe ser tu objeto de conexión PDO

// La conexión PDO debe estar configurada en tu archivo database.php
// Ejemplo (ajusta a tu configuración real):
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

// Consulta para obtener todas las recetas con información relacionada
$recetas = $pdo->query("
    SELECT
        r.id,
        r.descripcion,
        r.observaciones,
        r.fecha_registro,
        p.nombre AS nombre_paciente,
        p.apellidos AS apellidos_paciente,
        p.codigo_paciente,
        uh.nombre_usuario,
        c.motivo_consulta AS consulta_motivo
    FROM
        receta r
    LEFT JOIN
        pacientes p ON r.id_paciente = p.id
    LEFT JOIN
        usuarios_hospital uh ON r.id_usuario = uh.id
    LEFT JOIN
        consulta c ON r.id_consulta = c.id
    ORDER BY
        r.fecha_registro DESC
");
?>

<div id="content" class="container-fluid py-4">
    <div class="thead sticky-top bg-white pb-2" style="top: 60px; z-index: 1040; border-bottom: 1px solid #dee2e6;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-3 mb-md-0">
                <i class="bi bi-file-medical me-2 text-warning"></i> Gestión de Recetas
            </h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="buscarReceta" placeholder="Buscar receta...">
                <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalRegistroReceta">
                    <i class="bi bi-prescription2 me-1"></i> Nueva Receta
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
                    <th>Consulta Asoc.</th>
                    <th>Descripción</th>
                    <th>Observaciones</th>
                    <th>Registrado Por</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = $recetas->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id']) ?></td>
                        <td>
                            <?= htmlspecialchars($r['nombre_paciente'] . ' ' . $r['apellidos_paciente']) ?><br>
                            <span class="badge bg-secondary"><?= htmlspecialchars($r['codigo_paciente']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['consulta_motivo'] ? $r['consulta_motivo'] . ' (ID: ' . $r['id_consulta'] . ')' : 'N/A') ?></td>
                        <td><?= htmlspecialchars(substr($r['descripcion'], 0, 50)) ?><?php echo (strlen($r['descripcion']) > 50 ? '...' : ''); ?></td>
                        <td><?= htmlspecialchars(substr($r['observaciones'], 0, 50)) ?><?php echo (strlen($r['observaciones']) > 50 ? '...' : ''); ?></td>
                        <td><?= htmlspecialchars($r['nombre_usuario']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($r['fecha_registro'])) ?></td>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEditarReceta" data-id="<?= $r['id'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modalEliminarReceta" data-id="<?= $r['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>




