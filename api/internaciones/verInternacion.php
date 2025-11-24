<?php

    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo json_encode(['error' => 'ID de internación inválido']);
        exit;
    }

    $id = (int)$_GET['id'];

    $resultados = internaciones::VerInternacion($id);

    echo json_encode($resultados);
    exit;

?>