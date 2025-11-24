<?php

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $busqueda = $_GET['busqueda'] ?? '';
    $busqueda = trim($busqueda);

    if ($busqueda === '') {
        echo json_encode([]);
        exit;
    }

    $resultados = Internaciones::buscarInternaciones();

    echo json_encode($resultados);
?>