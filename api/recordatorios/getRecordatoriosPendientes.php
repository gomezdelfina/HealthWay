<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $userId = '';

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 404;
        $response['msg'] = 'El IdUsuario no puede estar vacio';
    } elseif(!Permisos::tienePermiso(12, $_SESSION['usuario'])){
        $response['code'] = 401;
        $response['msg'] = 'El usuario no tiene permiso para la peticion';
    } else {
        $userId = $_SESSION['usuario'];

        if (trim($userId) == '') {
            $response['code'] = 400;
            $response['msg'] = 'El IdUsuario no puede estar vacio';
        } elseif (!preg_match('/^[0-9]+$/', $userId)) {
            $response['code'] = 400;
            $response['msg'] = 'EL IdUsuario no puede estar vacio';
        } else {
            try{
                $recs = Recordatorio::getRecordatoriosPendientes($userId);
                $response['code'] = 200;
                $response['msg'] = $recs;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
    
?>