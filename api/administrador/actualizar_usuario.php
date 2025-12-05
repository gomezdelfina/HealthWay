<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/administrador.php');

    header('Content-Type: application/json');

    // Validar método
    if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
        echo json_encode(["status" => "error", "mensaje" => "Método no permitido"]);
        exit;
    }

    // ID obligatorio
    $idUsuario = $_GET["id"] ?? null;
    if (!$idUsuario) {
        echo json_encode(["status" => "error", "mensaje" => "Falta el ID del usuario"]);
        exit;
    }

    // Leer JSON enviado con fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["status" => "error", "mensaje" => "JSON inválido"]);
        exit;
    }

    // Rol obligatorio
    if (!isset($data["user-role"])) {
        echo json_encode(["status" => "error", "mensaje" => "Falta el rol de usuario"]);
        exit;
    }

    $rol = UsuariosDataAccess::getRoleId($data["user-role"]);
    if ($rol["status"] !== "success") {
        echo json_encode(["status" => "error", "mensaje" => "Rol inválido"]);
        exit;
    }

    $data["IdRol"] = $rol["data"];

    // Actualizar
    $resp = UsuariosDataAccess::actualizarUsuario($idUsuario, $data);

    // Respuesta final
    echo json_encode($resp);
?>