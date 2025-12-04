<?php 
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];
    $idRec = '';

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } elseif(!Permisos::tienePermiso(12, $_SESSION['usuario'])){
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
                    $response['msg'] = $rec;
                }catch(Exception $e){
                    $response['code'] = 500;
                    $response['msg'] = 'Error interno de aplicacion';
                }
            }else{
                $msgError = [];

                if(isset($errors['idRec'])){
                    $msgError[] = [
                        'campo' => 'idRec',
                        'error' => $errors['idRec']
                    ];
                };

                $response['code'] = 400;
                $response['msg'] = $msgError;
            }
        }
    }

    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);
?>