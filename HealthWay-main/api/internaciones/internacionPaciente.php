<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

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


                $resultados = internaciones::InternacionPaciente();

                //echo json_encode($resultados);
                $response['code'] = 200;
                $response['msg']  = $resultados;


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