<?php
    require_once(__DIR__ . '/../../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/SolicitudesDataAccess.php'); // Incluimos la clase de acceso a datos

    header("Content-Type: application/json; charset=utf-8");

    // me fijo si el parametro de busqueda existe
    $busqueda = filter_input(INPUT_GET, 'busqueda', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

    try {
        $data = SolicitudesDataAccess::obtenerSolicitudes($busqueda);

        echo json_encode([
            "status" => "success",
            "mensaje" => "Solicitudes cargadas correctamente.",
            "data" => $data
        ]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "mensaje" => "Error al obtener solicitudes: " . $e->getMessage(), "data" => []]);
    }
?>