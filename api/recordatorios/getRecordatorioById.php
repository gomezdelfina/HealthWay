<?php 
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');

    $response = [];
    $errors = [];
    $idRec = '';

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
        //IDRECORDATORIO
        if(!isset($data['idRecordatorio'])){
            $errors['idRec'] = 'El campo idRecordatorio no puede estar vacío';
        }else{
            $idRec = trim($data['idRecordatorio']);

            if($idRec == ''){
                $errors['idRec'] = 'El campo idRec no puede estar vacío';
            }else if(!preg_match('/^[0-9]+$/', $idRec)){
                $errors['idRec'] = 'El campo idRec no contiene un formato correcto';
            }
        }

        if(empty($errors)){
            try{
                $rec = Recordatorio::getRecordatorioById($idRec);

                $response['code'] = 200;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }else{
            $response['code'] = 400;
            $response['msg'] = 'Problemas al validar el formato de los campos: ' . $errors['idRec'];
        }
    }

    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($rec);
    }
?>