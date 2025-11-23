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
        //-- USUARIO 
        if (!isset($data['usuario'])) {
            $errors['user'] = 'Error al validar campo usuario.';
        } else {
            $userLogin = trim($data['usuario']);

            if ($userLogin == '') {
                $errors['user'] = 'El campo usuario no puede ser vacío.';
            }
        }

        //-- CLAVE 
        if (!isset($data['clave'])) {
            $errors['clave'] = 'Error al validar campo clave.';
        } else {
            $claveLogin = trim($data['clave']);

            if ($claveLogin == '') {
                $errors['clave'] = 'El campo clave no puede ser vacío.';
            }
        }

        //PROCESAR
        if (!empty($errors)) {
            if(isset($errors['user'])){
                $msgError[] = [
                    'campo' => 'user',
                    'error' => $errors['user']
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
                $user = [
                    "Usuario" => $userLogin,
                    "Clave" => $claveLogin
                ];

                $userResult = Usuarios::getUsuarioByUserPsw($user);
                $response['code'] = 200;
                $response['msg'] = $userResult;

                $_SESSION['usuario'] = $userResult[0]["IdUsuario"];
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
    echo json_encode($response['msg']);
?>