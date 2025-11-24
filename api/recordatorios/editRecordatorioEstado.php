<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');

    $response = [];
    $errors = [];

    $idRecordatorio = '';
    $estado = '';

    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (strpos($contentType, 'application/json') !== false) {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
    } else {
        $data = $_POST;
    }

    if (empty($data)) {
        $response['code'] = 400;
        $response['msg'] = 'El contenido de la petición no puede estar vacío';
    }else{
        //ID RECORDATORIO
        if (!isset($data['IdRecordatorio'])) {
            $errors['IdRecordatorio'] = 'El campo IdRecordatorio no puede faltar en la petición';
        } else {
            $idRecordatorio = trim($data['IdRecordatorio']);

            if ($idRecordatorio == '') {
                $errors['IdRecordatorio'] = 'El campo IdRecordatorio no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $idRecordatorio)) {
                $errors['IdRecordatorio'] = 'El campo IdRecordatorio no tiene el formato correcto';
            }
        }

        //ESTADO
        if (!isset($data['Estado'])) {
            $errors['Estado'] = 'El campo Estado no puede faltar en la petición';
        } else {
            $estado = trim($data['Estado']);

            if ($estado == '') {
                $errors['Estado'] = 'El campo Estado no puede estar vacío';
            } elseif ($estado != 'Hecho' & 
                      $estado != 'No Hecho' &
                      $estado != 'Atrasado') {
                $errors['Estado'] = 'El campo Estado tiene un formato incorrecto';
            }
        }

        if (empty($errors)) {
            try{
                $rec = [
                    'IdRecordatorio' => $idRecordatorio,
                    'Estado' => $estado
                ];

                $recAct = Recordatorio::editRecordatorioEstado($rec);
                
                if($recAct){
                    $response['code'] = 200;
                }
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }else{
            $response['code'] = 400;
            $msgError = [];
            
            if(isset($errors['IdRecordatorio'])){
                $msgError[] = [
                    'campo' => 'IdRecordatorio',
                    'error' => $errors['IdRecordatorio']
                ];
            };
            if(isset($errors['Estado'])){
                $msgError[] = [
                    'campo' => 'Estado',
                    'error' => $errors['Estado']
                ];
            };
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($recAct);
    }
    
?>