<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $response = [];
    $userId = '';

    if(isset($_SESSION['usuario']) & Permisos::tienePermiso(6,$_SESSION['usuario'])){
        try{
            $pac = internaciones::getPacientesInterAct();
            $response['code'] = 200;
        }catch(Exception $e){
            $response['code'] = 500;
            $response['msg'] = 'Error interno de aplicacion';
        }
    }else{
        $response['code'] = 403;
        $response['msg'] = 'El usuario no tiene permiso para la peticion';
    }

    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($pac);
    }
    
?>