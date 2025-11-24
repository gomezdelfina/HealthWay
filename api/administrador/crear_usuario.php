<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/administrador.php');

    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        echo json_encode(["success" => false, "message" => "JSON inválido"]);
        exit;
    }

    $rol = UsuariosDataAccess::getRoleId($data["role"]);
    if ($rol["status"] !== "success") {
        echo json_encode(["success" => false, "message" => "Rol inválido"]);
        exit;
    }

    $data["IdRol"] = $rol["data"];

    $resp = UsuariosDataAccess::crearUsuario($data);

    echo json_encode([
        "success" => $resp["status"] === "success",
        "message" => $resp["status"] === "success" ? "Usuario creado correctamente." : $resp["mensaje"]
    ]);
?>