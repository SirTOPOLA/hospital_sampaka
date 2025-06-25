<?php
// api/eliminar_prueba.php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta esta ruta a tu archivo de conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prueba = $_POST['id_prueba'] ?? null;

    // Validación básica del ID
    if (!is_numeric($id_prueba)) {
        echo json_encode(['success' => false, 'message' => 'ID de prueba no proporcionado o inválido.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM pruebas_hospital WHERE id = :id_prueba");
        $stmt->bindParam(':id_prueba', $id_prueba, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Prueba eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la prueba.']);
        }
    } catch (PDOException $e) {
        // Considera manejar errores de clave foránea de forma más específica si aplica
        if ($e->getCode() === '23000') { // Código SQLSTATE para violación de integridad (ej: FK)
             echo json_encode(['success' => false, 'message' => 'No se puede eliminar la prueba porque está asociada a otras analíticas.']);
        } else {
             echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>