<?php
// api/obtener_prueba.php
header('Content-Type: application/json');
require_once '../config/conexion.php'; // Ajusta esta ruta a tu archivo de conexión PDO

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_prueba = $_GET['id'];

    try {
        $stmt = $pdo->prepare("
            SELECT
                id,
                nombre_prueba,
                precio,
                fecha_registro
            FROM
                pruebas_hospital
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id_prueba, PDO::PARAM_INT);
        $stmt->execute();
        $prueba = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($prueba) {
            echo json_encode($prueba); // Devuelve los datos de la prueba en formato JSON
        } else {
            echo json_encode(['error' => 'Prueba no encontrada.']); // Si no se encuentra la prueba
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID de prueba no proporcionado o inválido.']); // Si el ID es inválido
}
?> 