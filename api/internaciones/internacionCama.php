<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    if (isset($_GET['numeroHab'])) {
        $numeroHab = (int)$_GET['numeroHab'];
        
        $resultados = internaciones::InternacionCama($numeroHab);

        echo json_encode($resultados);
        exit;
    }

    echo json_encode([]);
?>