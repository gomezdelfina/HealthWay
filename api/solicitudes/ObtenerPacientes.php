<?php
    require_once(__DIR__ . '/../../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/SolicitudesDataAccess.php');

    header("Content-Type: application/json; charset=utf-8");

    try {
        // Reutilizamos el método simulado para el dropdown
        $data = SolicitudesDataAccess::ObtenerPacientes();

        echo json_encode([
            "status" => "success",
            "mensaje" => "Pacientes cargados.",
            "data" => $data
        ]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "mensaje" => "Error al obtener pacientes: " . $e->getMessage(), "data" => []]);
    }
?>