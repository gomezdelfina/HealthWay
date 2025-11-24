<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/PacAdministrador.php');

    header("Content-Type: application/json; charset=utf-8");

    try {
        $resp = PacientesDataAccess::ObtenerObrasSociales();

        echo json_encode([
            "success" => $resp["status"] === "success",
            "message" => $resp["mensaje"] ?? "",
            "data"    => $resp["data"] ?? []
        ]);

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>