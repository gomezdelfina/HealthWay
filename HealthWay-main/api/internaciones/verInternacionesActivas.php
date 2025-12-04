<?php

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } elseif(!Permisos::tienePermiso(6, $_SESSION['usuario'])){
        $response['code'] = 401;
        $response['msg'] = 'El usuario no tiene permiso para la peticion';
    } else {
        try{
            $resultados = internaciones::VerInternacionesActivas();
            $response['code'] = 200;
            $response['msg'] = $resultados;
        }catch(Exception $e){
            $response['code'] = 500;
            $response['msg'] = 'Error interno de aplicacion';
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
?>