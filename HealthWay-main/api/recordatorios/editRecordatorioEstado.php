<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];

    $idRecordatorio = '';
    $estado = '';

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } elseif(!Permisos::tienePermiso(14, $_SESSION['usuario'])){
        $response['code'] = 401;
        $response['msg'] = 'El usuario no tiene permiso para la peticion';
    } else {
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
                } elseif ($estado != 'Pendiente' & 
                        $estado != 'Atrasado' & 
                        $estado != 'Hecho') {
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
                    
                    $response['code'] = 200;
                    $response['msg'] = $recAct;
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

                $response['msg'] = $msgError;
            }
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
?>