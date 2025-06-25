<?php
require '../config/conexion.php'; // Asegúrate de que esta conexión use PDO

$term = $_POST['busqueda'] ?? '';

if (strlen($term) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT id_paciente, nombre, apellido, codigo 
            FROM pacientes 
            WHERE nombre LIKE :term OR apellido LIKE :term OR codigo LIKE :term 
            ORDER BY nombre ASC 
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $termLike = "%$term%";
    $stmt->bindValue(':term', $termLike, PDO::PARAM_STR);
    $stmt->execute();

    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($pacientes);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos']);
}
