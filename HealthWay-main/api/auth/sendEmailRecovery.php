<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/utils/mailer.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

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
        //-- ID USER
        if (!isset($data['idUsuario'])) {
            $errors['idUsuario'] = 'Error al validar el campo idUsuario.';
        } else {
            $idUsuario= trim($data['idUsuario']);

            if ($idUsuario== '') {
                $errors['idUsuario'] = 'El campo idUsuario no puede ser vacío.';
            }elseif(!preg_match('/^[0-9]+$/', $idUsuario)){
                $errors['idUsuario'] = 'El campo idUsuario no contiene el formato correcto.';
            }elseif(!Permisos::tienePermiso(49, $idUsuario)){
                $errors['idUsuario'] = 'El usuario no tiene permiso para la peticion';
            }
        }

        //-- EMAIL
        if (!isset($data['email'])) {
            $errors['email'] = 'Error al validar el campo email.';
        } else {
            $email = trim($data['email']);

            if ($email == 'nombre@ejemplo.com' || $email == '') {
                $errors['email'] = 'El campo email no puede ser vacío.';
            } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/', $email)) {
                $errors['email'] = 'El campo email no contiene el formato correcto.';
            }
        }

        //-- NOMBRE
        if (!isset($data['nameUsuario'])) {
            $errors['nameUsuario'] = 'Error al validar el campo nameUsuario.';
        } else {
            $nameUsuario = trim($data['nameUsuario']);

            if ($nameUsuario == '') {
                $errors['nameUsuario'] = 'El campo nameUsuario no puede ser vacío.';
            } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'\-\.]+$/u', $nameUsuario)) {
                $errors['nameUsuario'] = 'El campo nameUsuario no contiene el formato correcto.';
            }
        }

        //-- APELLIDO
        if (!isset($data['apellidoUsuario'])) {
            $errors['apellidoUsuario'] = 'Error al validar el campo apellidoUsuario.';
        } else {
            $apellidoUsuario = trim($data['apellidoUsuario']);

            if ($apellidoUsuario == '') {
                $errors['apellidoUsuario'] = 'El campo apellidoUsuario no puede ser vacío.';
            } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'\-\.]+$/u', $apellidoUsuario)) {
                $errors['apellidoUsuario'] = 'El campo apellidoUsuario no contiene el formato correcto.';
            }
        }

        //PROCESAR
        if (!empty($errors)) {
            if(isset($errors['idUsuario'])){
                $msgError[] = [
                    'campo' => 'idUsuario',
                    'error' => $errors['idUsuario']
                ];
            };
            if(isset($errors['email'])){
                $msgError[] = [
                    'campo' => 'email',
                    'error' => $errors['email']
                ];
            };
            if(isset($errors['nameUsuario'])){
                $msgError[] = [
                    'campo' => 'nameUsuario',
                    'error' => $errors['nameUsuario']
                ];
            };
            if(isset($errors['apellidoUsuario'])){
                $msgError[] = [
                    'campo' => 'apellidoUsuario',
                    'error' => $errors['apellidoUsuario']
                ];
            };

            $response['code'] = 400;
            $response['msg'] = $msgError;
        }else{
            try{
                //Genera un token de sesion
                $token = bin2hex(random_bytes(16));
                //Genera una expiracion del token
                $expiracion = date('Y-m-d H:i:s', time() + 1800);

                $token = [
                    "email" => $email,
                    "token" => $token,
                    "expiracion" => $expiracion
                ];

                $resultToken = Usuarios::updateTokenSesion($token);

                if($resultToken){
                    try{
                        $dest = [
                            "idUsuario" => $idUsuario,
                            "email" => $email,
                            "nombre" => $nameUsuario,
                            "apellido" => $apellidoUsuario
                        ];

                        $asunto = 'Healthway - Recuperacion de contrasenia';

                        $href = $_SERVER['HTTP_ORIGIN'] . $dirBaseUrl . "/modules/resetPass/setPass.php?token=" . $token["token"];
                        $body = file_get_contents($dirBaseFile . '/modules/resetPass/email_recoveryLogin.php');
                        $body = str_replace(':linkRecuperacion', $href, $body);

                        $emailSend = Mailer::enviarEmail($dest, $asunto, $body);

                        if($emailSend){
                            $response['code'] = 200;
                            $response['msg'] = $emailSend;
                        }else{
                            throw new Exception();
                        }
                    }catch(Exception $e){
                        $msgError[] = [
                            'modulo' => 'Mailer',
                            'error' =>'Error interno de aplicacion : ' . $e
                        ];

                        $response['code'] = 500;
                        $response['msg'] = $msgError;
                    }
                }else{
                    throw new Exception("Problemas al actualizar el token de sesion: " . $e);
                }     
            }
            catch(Exception $e){
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