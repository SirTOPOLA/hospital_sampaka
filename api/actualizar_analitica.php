
<?php
// api/actualizar_analitica.php
header('Content-Type: application/json');
require_once '../config/database.php'; // Ajusta esta ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_analitica = $_POST['id_analitica_editar'] ?? null;
    $id_prueba = $_POST['id_prueba_editar'] ?? null;
    $resultado = $_POST['resultado_editar'] ?? null;
    $estado = $_POST['estado_editar'] ?? null;
    $id_consulta = $_POST['id_consulta_editar'] ?? null;
    $pagado = isset($_POST['pagado_editar']) ? 1 : 0;

    if (!$id_analitica || !$id_prueba || !$estado) {
        echo json_encode(['success' => false, 'message' => 'Campos obligatorios incompletos para la actualización.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE analiticas SET
                resultado = :resultado,
                estado = :estado,
                id_prueba = :id_prueba,
                id_consulta = :id_consulta,
                pagado = :pagado
            WHERE id = :id_analitica
        ");

        $stmt->bindParam(':resultado', $resultado);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_prueba', $id_prueba, PDO::PARAM_INT);
        $stmt->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
        $stmt->bindParam(':pagado', $pagado, PDO::PARAM_INT);
        $stmt->bindParam(':id_analitica', $id_analitica, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Analítica actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la analítica.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>