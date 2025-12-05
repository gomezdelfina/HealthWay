<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/PacAdministrador.php');

    header("Content-Type: application/json; charset=utf-8");

    $nombre = $apellido = $telefono = $dni = $fechaNac = $os = $genero = $estado = $email = [];
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
                    
                    $nombre = $_POST["nombre"] ?? "";
                    $apellido = $_POST["apellido"] ?? "";
                    $dni = $_POST["dni"] ?? "";
                    $email = $_POST["email"] ?? "";
                    $telefono = $_POST["telefono"] ?? "";
                    $fechaNac = $_POST["fechaNac"] ?? "";
                    $genero = $_POST["genero"] ?? "";
                    $estado = $_POST["estado"] ?? "";  
                    $os = $_POST["nombreOS"] ?? ""; 
                    
                    if ($nombre === "") {
                        $errores["nombre"] = "Nombre no Seleccionado";
                    }

                    if ($apellido === "") {
                        $errores["apellido"] = "Apellido no Seleccionado";
                    }

                    if ($dni === "") {
                        $errores["dni"] = "DNI no Seleccionado";
                    }

                    if ($email === "") {
                        $errores["email"] = "Email no Seleccionado";
                    }

                    if ($telefono === "") {
                        $errores["telefono"] = "Telefono no Seleccionado";
                    }

                    if ($fechaNac === "") {
                        $errores["fechaNac"] = "F. de Nacimiento no Seleccionada";
                    }

                    if ($genero === "") {
                        $errores["genero"] = "Genero no Seleccionado";
                    }

                    if ($estado === "") {
                        $errores["estado"] = "Estado Civil no Seleccionada";
                    }

                    if ($os === "") {
                        $errores["nombreOS"] = "Obra Social no Seleccionada";
                    }

                    if (!empty($errores)) {
                        echo json_encode([
                            "status"  => "error",
                            "errores" => $errores
                        ]);
                        exit;
                    }

                    $resp = PacientesDataAccess::CrearPaciente(
                        $nombre,
                        $apellido,
                        $dni, 
                        $email,
                        $telefono, 
                        $fechaNac, 
                        $genero, 
                        $estado,
                        $os
                    );

                    if ($resp === true) {
                        echo json_encode([
                            "status"  => "success",
                            "mensaje" => "Internación registrada correctamente"
                        ]);
                    } else {
                        echo json_encode([
                            "status"  => "error",
                            "mensaje" => "Error al registrar la internación",
                            "detalle" => $resp // útil para debug
                        ]);
                    }

                    $response['code'] = 200;
                    $response['msg']  = $resultado;

                }

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

    try {
        

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
?>