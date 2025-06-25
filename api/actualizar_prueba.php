<?php
// api/actualizar_prueba.php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta esta ruta a tu archivo de conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prueba = $_POST['id_prueba_editar'] ?? null;
    $nombre_prueba = $_POST['nombre_prueba_editar'] ?? null;
    $precio = $_POST['precio_editar'] ?? null;

    // Validación básica de los campos
    if (!is_numeric($id_prueba) || empty($nombre_prueba) || !is_numeric($precio)) {
        echo json_encode(['success' => false, 'message' => 'Datos de actualización inválidos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE pruebas_hospital SET
                nombre_prueba = :nombre_prueba,
                precio = :precio
            WHERE id = :id_prueba
        ");

        $stmt->bindParam(':nombre_prueba', $nombre_prueba);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':id_prueba', $id_prueba, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Prueba actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la prueba.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?> 