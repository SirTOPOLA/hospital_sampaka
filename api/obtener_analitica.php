
<?php
// api/obtener_analitica.php
header('Content-Type: application/json');
require_once '../config/database.php'; // Ajusta esta ruta

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_analitica = $_GET['id'];

    try {
        $stmt = $pdo->prepare("
            SELECT
                a.*,
                p.nombre AS nombre_paciente,
                p.apellidos AS apellidos_paciente,
                p.codigo_paciente,
                tp.nombre_prueba
            FROM
                analiticas a
            LEFT JOIN
                pacientes p ON a.id_paciente = p.id
            LEFT JOIN
                pruebas_hospital tp ON a.id_prueba = tp.id
            WHERE a.id = :id
        ");
        $stmt->bindParam(':id', $id_analitica, PDO::PARAM_INT);
        $stmt->execute();
        $analitica = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($analitica) {
            echo json_encode($analitica);
        } else {
            echo json_encode(['error' => 'Analítica no encontrada.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de analítica no proporcionado o inválido.']);
}
?>