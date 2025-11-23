<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    if (!isset($_GET['idPaciente']) || !is_numeric($_GET['idPaciente'])) {
        
        echo json_encode([]);
        exit;

    }

    $idPaciente = intval($_GET['idPaciente'] ?? 0);
        
    $resultados = internaciones::InternacionSolicitud($idPaciente);

    echo json_encode($resultados);
    exit;

    // Si no hay tipoHab, devuelvo array vacío
    echo json_encode([]);
?>