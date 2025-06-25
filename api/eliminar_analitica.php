
<?php
// api/eliminar_analitica.php
header('Content-Type: application/json');
require_once '../config/database.php'; // Ajusta esta ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_analitica = $_POST['id_analitica'] ?? null;

    if (!$id_analitica) {
        echo json_encode(['success' => false, 'message' => 'ID de analítica no proporcionado para eliminar.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM analiticas WHERE id = :id_analitica");
        $stmt->bindParam(':id_analitica', $id_analitica, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Analítica eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la analítica.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>
