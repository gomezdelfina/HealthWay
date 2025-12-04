<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/PacAdministrador.php');

    header("Content-Type: application/json; charset=utf-8");

    $id = $_GET["id"] ?? null;
    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID faltante"]);
        exit;
    }

    try {
        $resp = PacientesDataAccess::EliminarPaciente($id);

        echo json_encode([
            "success" => $resp["status"] === "success",
            "message" => $resp["mensaje"] ?? "",
            "data"    => $resp["data"] ?? []
        ]);

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>