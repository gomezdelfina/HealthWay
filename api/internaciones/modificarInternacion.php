<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data["id"] ?? '';
    $newEstado = $data["newEstado"] ?? '';
    $observacion = $data["observacion"] ?? '';

    $resultado = internaciones::ModificarInternacion($id, $newEstado, $observacion);

    echo json_encode($resultado);
?>