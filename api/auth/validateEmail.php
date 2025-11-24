<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');

    $errors = [];
    $response = [];
    $msgError = [];

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
        //-- EMAIL
        if (!isset($data['email'])) {
            $errors['email'] = 'Error al validar el campo email.';
        } else {
            $emailRecovery = trim($data['email']);

            if ($emailRecovery == 'nombre@ejemplo.com' || $emailRecovery == '') {
                $errors['email'] = 'El campo email no puede ser vacío.';
            } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/', $emailRecovery)) {
                $errors['email'] = 'El campo email no contiene el formato correcto.';
            }
        }
        
        // PROCESAR
        if (!empty($errors)) {
            if(isset($errors['email'])){
                $msgError[] = [
                    'campo' => 'email',
                    'error' => $errors['email']
                ];
            };

            $response['code'] = 400;
            $response['msg'] = $msgError;
        }else{
            try{
                $userResult = Usuarios::getUsuarioByEmail($emailRecovery);
                
                $response['code'] = 200;
                $response['msg'] = $userResult;
            }catch(Exception $e){
                $msgError[] = [
                    'modulo' => 'Usuarios',
                    'error' => 'Error interno de aplicacion: ' . $e
                ];

                $response['code'] = 500;
                $response['msg'] = $msgError;
            }
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode( $response['msg']);
    
?>