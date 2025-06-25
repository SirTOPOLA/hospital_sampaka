
<?php
// api/guardar_analitica.php
header('Content-Type: application/json');
require_once '../config/database.php'; // Ajusta esta ruta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? null;
    $id_prueba = $_POST['id_prueba'] ?? null;
    $resultado = $_POST['resultado'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $id_consulta = $_POST['id_consulta'] ?? null; // Puede ser nulo
    $pagado = isset($_POST['pagado']) ? 1 : 0;

    //  Obtener id_usuario del usuario logueado (ej: $_SESSION['user_id'])
    // Por ahora, usa un valor de ejemplo o asegúrate de que esté disponible
    $id_usuario = $_SESSION['usuario']['id']; // EJEMPLO: reemplaza con el ID real del usuario logueado

    if (!$id_paciente || !$id_prueba || !$estado) {
        echo json_encode(['success' => false, 'message' => 'Campos obligatorios incompletos.']);
        exit;
    }

    try {
        // Obtener codigo_paciente basado en id_paciente
        $stmt_paciente = $pdo->prepare("SELECT codigo_paciente FROM pacientes WHERE id = :id_paciente");
        $stmt_paciente->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        $stmt_paciente->execute();
        $paciente_data = $stmt_paciente->fetch(PDO::FETCH_ASSOC);
        $codigo_paciente = $paciente_data ? $paciente_data['codigo_paciente'] : null;

        if (!$codigo_paciente) {
            echo json_encode(['success' => false, 'message' => 'Paciente no encontrado.']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO analiticas (resultado, estado, id_prueba, id_consulta, id_usuario, id_paciente, codigo_paciente, pagado)
            VALUES (:resultado, :estado, :id_prueba, :id_consulta, :id_usuario, :id_paciente, :codigo_paciente, :pagado)
        ");

        $stmt->bindParam(':resultado', $resultado);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id_prueba', $id_prueba, PDO::PARAM_INT);
        $stmt->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        $stmt->bindParam(':codigo_paciente', $codigo_paciente);
        $stmt->bindParam(':pagado', $pagado, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Analítica registrada correctamente.', 'id' => $pdo->lastInsertId()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la analítica.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>