<?php
    require_once(__DIR__ . '/../../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/SolicitudesDataAccess.php'); 

    header("Content-Type: application/json; charset=utf-8");

    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["status" => "error", "mensaje" => "Método no permitido."]);
        exit;
    }

   
    $pacienteId = filter_input(INPUT_POST, 'pacienteId', FILTER_VALIDATE_INT);
    $medicoId = filter_input(INPUT_POST, 'medicoId', FILTER_VALIDATE_INT);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_SPECIAL_CHARS);
    $diagnostico = filter_input(INPUT_POST, 'diagnostico', FILTER_SANITIZE_SPECIAL_CHARS);
    $prioridad = filter_input(INPUT_POST, 'prioridad', FILTER_SANITIZE_SPECIAL_CHARS);
    
    $errores = [];

    if (!$pacienteId) { $errores['pacienteId'] = "Seleccione un paciente válido."; }
    if (!$medicoId) { $errores['medicoId'] = "Seleccione un médico solicitante."; }
    if (empty($motivo)) { $errores['motivo'] = "El motivo de internación es obligatorio."; }
    if (empty($prioridad)) { $errores['prioridad'] = "La prioridad es obligatoria."; }

    if (!empty($errores)) {
        echo json_encode(["status" => "error", "mensaje" => "Revise los errores en el formulario.", "errores" => $errores]);
        exit;
    }

    $data = [
        'pacienteId' => $pacienteId,
        'medicoId' => $medicoId,
        'motivo' => $motivo,
        'diagnostico' => $diagnostico,
        'prioridad' => $prioridad,
        
    ];

    try {
        $resp = SolicitudesDataAccess::CrearSolicitud($data);

        echo json_encode([
            "status" => $resp["status"],
            "mensaje" => $resp["mensaje"] ?? "Solicitud procesada.",
            "data" => $resp["data"] ?? []
        ]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "mensaje" => "Error al procesar la solicitud: " . $e->getMessage()]);
    }
?>