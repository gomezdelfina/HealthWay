<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/PacAdministrador.php');

    header("Content-Type: application/json; charset=utf-8");

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        echo json_encode(["success" => false, "message" => "JSON inválido"]);
        exit;
    }

    try {
        $resp = PacientesDataAccess::CrearPaciente($data);

        echo json_encode([
            "success" => $resp["status"] === "success",
            "message" => $resp["mensaje"] ?? "",
            "data"    => $resp["data"] ?? []
        ]);

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>