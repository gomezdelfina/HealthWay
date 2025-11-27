<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $idUser = '';

    if(isset($_SESSION['usuario']) & Permisos::tienePermiso($_SESSION['usuario'],44)){
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
        } else {
            $data = $_POST;
        }

        if (empty($data)) {
            $response['code'] = 400;
            $response['msg'] = 'No se recibieron datos';
        }else{

            // VALIDACIONES
            if (!isset($data['id'])) {
                $response['code'] = 400;
                $response['msg'] = 'No se recibieron datos';
            } else {
                $idUser = $data['idUser'];

                if (trim($idUser) == '') {
                    $response['code'] = 400;
                    $response['msg'] = 'No se recibieron datos';
                } elseif (!preg_match('/^[0-9]+$/', $idUser)) {
                    $response['code'] = 400;
                    $response['msg'] = 'El parámetro no tiene el formato correcto';
                } else {

                    try{
                        $user = Usuarios::getUsuarioById($idUser);
                        $response['code'] = 200;
                    }catch(Exception $e){
                        $response['code'] = 500;
                        $response['msg'] = 'Error interno de aplicacion';
                    }
                }
            }
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
        echo json_encode($user);
    }   
?>