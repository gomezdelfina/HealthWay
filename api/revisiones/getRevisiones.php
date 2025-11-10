<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');

    $response = [];
    $userId = '';

    try{
        $revs = Revisiones::getRevisiones();
        $response['code'] = 200;
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
        echo json_encode($revs);
    }
    
?>