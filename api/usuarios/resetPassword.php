<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');

    $errors = [];
    $response = [];
    $msgError = [];

    $clave = '';
    $token = '';

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
    } else {
        $data = $_POST;
    }

    if (empty($data)) {
        $response['code'] = 400;
        $response['msg'] = 'No se recibieron datos.';
    }else{
        // VALIDACIONES
        //-- CLAVE 
        if (!isset($data['clave'])) {
            $errors['clave'] = 'Error al validar campo clave.';
        } else {
            $clave = trim($data['clave']);

            if ($clave == '') {
                $errors['clave'] = 'El campo clave no puede ser vacío.';
            }
        }

        //-- TOKEN
        if (!isset($data['token'])) {
            $errors['token'] = 'Error al validar campo token.';
        } else {
            $token = $data['token'];

            if ($token == '') {
                $errors['token'] = 'El campo token no puede ser vacío.';
            }
        }

        //PROCESAR
        if (!empty($errors)) {
            if(isset($errors['token'])){
                $msgError[] = [
                    'campo' => 'token',
                    'error' => $errors['token']
                ];
            };

            if(isset($errors['clave'])){
                $msgError[] = [
                    'campo' => 'clave',
                    'error' => $errors['clave']
                ];
            };

            $response['code'] = 400;
            $response['msg'] = $msgError;
        }else{
            try{

                $newPass = [
                    'token' => $token,
                    'clave' => $clave
                ];
            
                $passResult = Usuarios::updatePassword($newPass);
                
                if($passResult){
                    $response['code'] = 200;
                    $response['msg'] = $passResult;
                }else{
                    throw new Exception("No se encontro un token de sesion activo.");
                }
            }catch(Exception $e){
                $msgError[] = [
                    'modulo' => 'Usuarios',
                    'error' =>'Error interno de aplicacion : ' . $e
                ];

                $response['code'] = 500;
                $response['msg'] = $msgError;
            }
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
    
?>