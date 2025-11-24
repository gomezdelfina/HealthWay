<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/administrador.php');

    header('Content-Type: application/json');

    $resp = UsuariosDataAccess::getRoles();

    echo json_encode([
        "success" => $resp["status"] === "success",
        "message" => $resp["status"] === "success" ? "Roles obtenidos." : $resp["mensaje"],
        "data" => $resp["data"] ?? []
    ]);
?>