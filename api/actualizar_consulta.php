<?php
header('Content-Type: application/json');

// Incluye tu archivo de conexión a la base de datos
// Asegúrate de que $pdo esté disponible aquí
require_once '../config/database.php'; // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_consulta = $_POST['id_consulta_editar'] ?? null;
    $motivo_consulta = $_POST['motivo_consulta_editar'] ?? null;
    $temperatura = $_POST['temperatura_editar'] ?? null;
    $pulso = $_POST['pulso_editar'] ?? null;
    $frecuencia_cardiaca = $_POST['frecuencia_cardiaca_editar'] ?? null;
    $frecuencia_respiratoria = $_POST['frecuencia_respiratoria_editar'] ?? null;
    $tension_arterial = $_POST['tension_arterial_editar'] ?? null;
    $saturacion_oxigeno = $_POST['saturacion_oxigeno_editar'] ?? null;
    $peso = $_POST['peso_editar'] ?? null;
    $masa_indice_corporal = $_POST['masa_indice_corporal_editar'] ?? null;

    // Detalles adicionales
    $operacion = isset($_POST['operacion_editar']) ? 1 : 0;
    $orina = isset($_POST['orina_editar']) ? 1 : 0;
    $defeca = isset($_POST['defeca_editar']) ? 1 : 0;
    $intervalo_defecacion_dias = $_POST['intervalo_defecacion_dias_editar'] ?? null;
    $duerme_bien = isset($_POST['duerme_bien_editar']) ? 1 : 0;
    $horas_sueno = $_POST['horas_sueno_editar'] ?? null;
    $alergico = $_POST['alergico_editar'] ?? null;
    $antecedentes_patologicos = $_POST['antecedentes_patologicos_editar'] ?? null;
    $antecedentes_patologicos_familiares = $_POST['antecedentes_patologicos_familiares_editar'] ?? null;

    if (!$id_consulta) {
        echo json_encode(['success' => false, 'message' => 'ID de consulta no proporcionado.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE consulta SET
                motivo_consulta = :motivo_consulta,
                temperatura = :temperatura,
                pulso = :pulso,
                frecuencia_cardiaca = :frecuencia_cardiaca,
                frecuencia_respiratoria = :frecuencia_respiratoria,
                tension_arterial = :tension_arterial,
                saturacion_oxigeno = :saturacion_oxigeno,
                peso = :peso,
                masa_indice_corporal = :masa_indice_corporal,
                operacion = :operacion,
                orina = :orina,
                defeca = :defeca,
                intervalo_defecacion_dias = :intervalo_defecacion_dias,
                duerme_bien = :duerme_bien,
                horas_sueno = :horas_sueno,
                alergico = :alergico,
                antecedentes_patologicos = :antecedentes_patologicos,
                antecedentes_patologicos_familiares = :antecedentes_patologicos_familiares
            WHERE id = :id_consulta
        ");

        $stmt->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
        $stmt->bindParam(':motivo_consulta', $motivo_consulta);
        $stmt->bindParam(':temperatura', $temperatura);
        $stmt->bindParam(':pulso', $pulso);
        $stmt->bindParam(':frecuencia_cardiaca', $frecuencia_cardiaca);
        $stmt->bindParam(':frecuencia_respiratoria', $frecuencia_respiratoria);
        $stmt->bindParam(':tension_arterial', $tension_arterial);
        $stmt->bindParam(':saturacion_oxigeno', $saturacion_oxigeno);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':masa_indice_corporal', $masa_indice_corporal);
        $stmt->bindParam(':operacion', $operacion, PDO::PARAM_INT);
        $stmt->bindParam(':orina', $orina, PDO::PARAM_INT);
        $stmt->bindParam(':defeca', $defeca, PDO::PARAM_INT);
        $stmt->bindParam(':intervalo_defecacion_dias', $intervalo_defecacion_dias);
        $stmt->bindParam(':duerme_bien', $duerme_bien, PDO::PARAM_INT);
        $stmt->bindParam(':horas_sueno', $horas_sueno);
        $stmt->bindParam(':alergico', $alergico);
        $stmt->bindParam(':antecedentes_patologicos', $antecedentes_patologicos);
        $stmt->bindParam(':antecedentes_patologicos_familiares', $antecedentes_patologicos_familiares);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Consulta actualizada correctamente.']);
            // Opcional: Redirigir o recargar la página después de una actualización exitosa
            // header('Location: ../tu_pagina_de_consultas.php');
            // exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la consulta.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>