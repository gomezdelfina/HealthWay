<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/administrador.php');

    header('Content-Type: application/json');

    $search = $_GET['search'] ?? null;

    $resp = UsuariosDataAccess::getUsuarios($search);

    echo json_encode([
        "success" => $resp["status"] === "success",
        "message" => $resp["status"] === "success" ? "Usuarios obtenidos." : $resp["mensaje"],
        "data" => $resp["data"] ?? []
    ]);
?>