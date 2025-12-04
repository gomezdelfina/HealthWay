<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $busqueda = $_GET['busqueda'] ?? '';

    $resultados = internaciones::BuscarInternacion($busqueda);

    echo json_encode($resultados);
?>