<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $id = $_GET['id'] ?? '';

    $resultados = internaciones::FinalizarInternacion($id);

    echo json_encode($resultados);
?>