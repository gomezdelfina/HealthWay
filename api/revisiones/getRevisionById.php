<?php 
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');

    $response = [];
    $errors = [];
    $idRev = '';

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
    } else {
        $data = $_POST;
    }

    if(empty($data)){
        $response['code'] = 400;
        $response['msg'] = 'El contenido de la petición no puede estar vacío';
    }else{
        //VALIDACIONES
        //IDREVISION
        if(!isset($data['idRevision'])){
            $errors['idRev'] = 'El campo idRevision no puede estar vacío';
        }else{
            $idRev = trim($data['idRevision']);

            if($idRev == ''){
                $errors['idRev'] = 'El campo IdRev no puede estar vacío';
            }else if(!preg_match('/^[0-9]+$/', $idRev)){
                $errors['idRev'] = 'El campo IdRev no contiene un formato correcto';
            }
        }

        if(empty($errors)){
            try{
                $revision = Revisiones::getRevisionById($idRev);

                $response['code'] = 200;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }else{
            $response['code'] = 400;
            $response['msg'] = 'Problemas al validar el formato de los campos: ' . $errors['idRev'];
        }
    }

    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($revision);
    }
?>