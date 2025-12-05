<?php
    require_once(__DIR__ . '/../../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/SolicitudesDataAccess.php'); 

    header("Content-Type: application/json; charset=utf-8");


    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["status" => "error", "mensaje" => "Método no permitido."]);
        exit;
    }

    // Validamos y obtenemos los datos del POST
    $idSolicitud = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nuevoEstado = filter_input(INPUT_POST, 'nuevoEstado', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $errores = [];

    if (!$idSolicitud || $idSolicitud <= 0) { 
        $errores[] = "ID de solicitud no válido."; 
    }
    if (empty($nuevoEstado)) { 
        $errores[] = "El nuevo estado es obligatorio."; 
    }

    if (!empty($errores)) {
        echo json_encode(["status" => "error", "mensaje" => implode(" ", $errores)]);
        exit;
    }

    try {
        // La funcion de la capa de  datos se encarga de cambiar el estado de la solicitud 
        // Y de asignar el estado 'En espera de internación' al paciente .
        $resp = SolicitudesDataAccess::ActualizarEstadoSolicitud($idSolicitud, $nuevoEstado);

        echo json_encode([
            "status" => $resp["status"],
            "mensaje" => $resp["mensaje"] ?? "Estado actualizado correctamente.",
        ]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "mensaje" => "Error al actualizar estado: " . $e->getMessage()]);
    }
?>