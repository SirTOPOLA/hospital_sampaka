<?php
// api/obtener_pruebas_hospital.php
header('Content-Type: application/json');
require_once '../config/database.php'; // Ajusta esta ruta

try {
    $stmt = $pdo->query("SELECT id, nombre_prueba AS nombre FROM pruebas_hospital ORDER BY nombre_prueba ASC");
    $pruebas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pruebas);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener pruebas: ' . $e->getMessage()]);
}
?>