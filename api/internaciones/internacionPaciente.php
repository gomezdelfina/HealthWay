<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    try {
        $resultados = internaciones::InternacionPaciente();

        echo json_encode([
            "success" => true,
            "data" => $resultados
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "data" => [],
            "error" => $e->getMessage()
        ]);
    }
?>