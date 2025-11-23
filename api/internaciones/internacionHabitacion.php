<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    if (isset($_GET['tipoHab'])) {
        $tipo = $_GET['tipoHab'];
        
        $resultados = internaciones::InternacionHabitacion($tipo);

        echo json_encode($resultados);
        exit;
    }

    // Si no hay tipoHab, devuelvo array vacío
    echo json_encode([]);
?>