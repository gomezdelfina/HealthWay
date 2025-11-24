<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/administrador.php');

    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    $idUsuario = $_GET["id"] ?? null;
    if (!$idUsuario) {
        echo json_encode(["success" => false, "message" => "ID faltante"]);
        exit;
    }

    $resp = UsuariosDataAccess::eliminarUsuario($idUsuario);

    echo json_encode([
        "success" => $resp["status"] === "success",
        "message" => $resp["status"] === "success" ? "Usuario deshabilitado." : $resp["mensaje"]
    ]);
?>