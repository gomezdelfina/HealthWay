<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];

    try{
        if(isset($_SESSION['usuario']) & Permisos::tienePermiso(9,$_SESSION['usuario'])){
            $idUser = $_SESSION['usuario'];

            $estados = Revisiones::getEstadosRevByUser($idUser);
            $response['code'] = 200;
        }else{
            $response['code'] = 403;
            $response['msg'] = 'El usuario no tiene permiso para la peticion';
        }       
    }catch(Exception $e){
        $response['code'] = 500;
        $response['msg'] = 'Error interno de aplicacion';
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($estados);
    }
    
?>