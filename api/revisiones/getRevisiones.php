<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];
    $userId = '';

    try{
        // -- VALIDACIONES
        //ID USER
        if (!isset($_SESSION['usuario'])) {
            $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
        } else {
            $userId = $_SESSION['usuario'];

            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            } elseif (!Permisos::tienePermiso(9,$_SESSION['usuario'])){
                $errors['usuario'] = 'El usuario no tiene permiso para la peticion';
            }

            if(empty($errors)){
                $revs = Revisiones::getRevisiones();

                $response['code'] = 200;
                $response['msg'] = $revs;
            }else{
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
    }catch(Exception $e){
        $response['code'] = 500;
        $response['msg'] = 'Error interno de aplicacion';
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
    
?>