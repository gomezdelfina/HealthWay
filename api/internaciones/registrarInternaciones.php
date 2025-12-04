<?php
    header("Content-Type: application/json; charset=utf-8");
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');
    require_once "phpqrcode/qrlib.php";

    $paciente = $solicitud = $estado = $habitacion = $cama = $fechaInicio = $fechaFin = "";
    $errores = [];
    $errors = [];
    $response = [];

    try {

        if (!isset($_SESSION['usuario'])) {
            $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
        } else {
            $userId = $_SESSION['usuario'];

            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            } elseif (!Permisos::tienePermiso(7,$_SESSION['usuario'])){
                $errors['usuario'] = 'El usuario no tiene permiso para la peticion';
            }

            if (empty($errors)) {

                if ($_SERVER["REQUEST_METHOD"] === "POST") {

                    // ------------------------------
                    // VALIDACIÓN CAMPOS
                    // ------------------------------
                    $paciente = $_POST["paciente"] ?? "";
                    if ($paciente === "") {
                        $errores["paciente"] = "Paciente no Seleccionado";
                    }

                    $solicitud = $_POST["solicitud"] ?? "";
                    if ($solicitud === "") {
                        $errores["solicitud"] = "Solicitud no Seleccionada";
                    }

                    $estado = $_POST["estado"] ?? "";
                    if ($estado === "") {
                        $errores["estado"] = "Estado no Seleccionado";
                    }

                    // HABITACIÓN
                    if (!empty($_POST["camaIndPac"])) {
                        $habitacion = $_POST["camaIndPac"];
                    } else if (!empty($_POST["camaComPac"])) {
                        $habitacion = $_POST["camaComPac"];
                    } else {
                        $errores["habitacionPac"] = "Habitación no Seleccionada";
                    }

                    // CAMA
                    $cama = $_POST["camaPac"] ?? "";
                    if ($cama === "") {
                        $errores["camaPac"] = "Cama no Seleccionada";
                    }

                    // FECHAS
                    $fechaInicio = $_POST["fechaInicio"] ?? "";
                    $fechaFin    = $_POST["fechaFin"] ?? "";

                    if ($fechaInicio === "") {
                        $errores["fechaInicio"] = "La fecha de inicio es obligatoria.";
                    }

                    if ($fechaFin === "") {
                        $errores["fechaFin"] = "La fecha de fin es obligatoria.";
                    }

                    if ($fechaInicio && $fechaFin) {
                        try {
                            $inicio = new DateTime($fechaInicio);
                            $fin = new DateTime($fechaFin);
                            if ($fin < $inicio) {
                                $errores["fechaFin"] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
                            }
                        } catch (Exception $e) {
                            $errores["fechaFin"] = "Formato de fecha inválido.";
                        }
                    }

                    // Si hay errores → retorno inmediato
                    if (!empty($errores)) {
                        echo json_encode([
                            "status"  => "error",
                            "errores" => $errores
                        ]);
                        exit;
                    }

                    // ------------------------------
                    // GUARDAR INTERNACION
                    // ------------------------------
                    $resultado = internaciones::RegistrarInternacion(
                        $paciente,
                        $solicitud,
                        $estado,
                        $habitacion,
                        $cama,
                        $fechaInicio,
                        $fechaFin
                    );

                    // ------------------------------
                    // RESPUESTA
                    // ------------------------------
                    if ($resultado === true) {
                        echo json_encode([
                            "status"  => "success",
                            "mensaje" => "Internación registrada correctamente"
                        ]);
                    } else {
                        echo json_encode([
                            "status"  => "error",
                            "mensaje" => "Error al registrar la internación",
                            "detalle" => $resultado // útil para debug
                        ]);
                    }
                }

                $response['code'] = 200;
                $response['msg']  = $resultado;

            } else {

                $msgError = [];

                if(isset($errors['usuario'])){
                    $msgError[] = [
                        'campo' => 'usuario',
                        'error' => $errors['usuario']
                    ];
                };

                $response['code'] = 400;
                $response['msg'] = $msgError;
            }
            
        }
            
    } catch (PDOexception $e) {

        $response['code'] = 500;
        $response['msg'] = 'Error interno de aplicacion';

    }

    http_response_code($response['code']);
    echo json_encode($response['msg']);

?>
