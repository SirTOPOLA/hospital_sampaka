<?php
header('Content-Type: application/json');

// Incluye tu archivo de conexión a la base de datos
// Asegúrate de que $pdo esté disponible aquí
require_once '../config/database.php'; // Ajusta la ruta según tu estructura

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_consulta = $_GET['id'];

    try {
        $stmt = $pdo->prepare("
            SELECT c.*, p.nombre, p.apellidos, p.codigo_paciente
            FROM consulta c
            LEFT JOIN pacientes p ON c.id_paciente = p.id
            WHERE c.id = :id
        ");
        $stmt->bindParam(':id', $id_consulta, PDO::PARAM_INT);
        $stmt->execute();
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($consulta) {
            echo json_encode($consulta);
        } else {
            echo json_encode(['error' => 'Consulta no encontrada.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de consulta no proporcionado o inválido.']);
}
?>