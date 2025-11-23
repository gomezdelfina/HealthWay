<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $response = [];
    $userId = '';

    // VALIDACIONES
    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 404;
        $response['msg'] = 'Error al procesar la solicitud';
    } else {
        $userId = $_SESSION['usuario'];

        if (trim($userId) == '') {
            $response['code'] = 404;
            $response['msg'] = 'Error al procesar la solicitud';
        } elseif (!preg_match('/^[1-9]+$/', $userId)) {
            $response['code'] = 404;
            $response['msg'] = 'Error al procesar la solicitud';
        } else {
            try{
                $user = Permisos::getPermisosByIdUser($userId);
                $response['code'] = 200;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($user);
    }
    
?>