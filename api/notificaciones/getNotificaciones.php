<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/Notificaciones.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');

    $response = [];
    $errors = [];
    $userId = '';

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } else {
        $userId = $_SESSION['usuario'];

            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            }

            if(empty($errors)){

                $roles = Usuarios::getRolByUser($userId);

                $notifs = [];

                foreach($roles as $rol){
                    $notifByRol = Notificaciones::obtenerNoLeidas($rol['DescRol']);

                    $notifs = array_merge($notifs, (array)$notifByRol);
                }

                $response['code'] = 200;
                $response['msg'] = $notifs;
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

    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($notifs);
?>