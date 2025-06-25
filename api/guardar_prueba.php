<?php
// api/guardar_prueba.php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta esta ruta a tu archivo de conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_prueba = $_POST['nombre_prueba'] ?? null;
    $precio = $_POST['precio'] ?? null;

    // Validación básica de los campos
    if (empty($nombre_prueba) || !is_numeric($precio)) {
        echo json_encode(['success' => false, 'message' => 'Nombre de prueba o precio inválido.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO pruebas_hospital (nombre_prueba, precio)
            VALUES (:nombre_prueba, :precio)
        ");

        $stmt->bindParam(':nombre_prueba', $nombre_prueba);
        $stmt->bindParam(':precio', $precio);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Prueba registrada correctamente.', 'id' => $pdo->lastInsertId()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la prueba.']);
        }
    } catch (PDOException $e) {
        // Captura y muestra errores de la base de datos
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    // Si la solicitud no es POST, se devuelve un error
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?> 
