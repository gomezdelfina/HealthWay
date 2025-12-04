<?php 
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];
    $userId = '';

    $idRev = '';

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } elseif(!Permisos::tienePermiso(9, $_SESSION['usuario'])){
        $response['code'] = 401;
        $response['msg'] = 'El usuario no tiene permiso para la peticion';
    } else {
        $userId = $_SESSION['usuario'];

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
            try{
                // -- VALIDACIONES
                // IDREVISION
                if(!isset($data['idRevision'])){
                    $errors['idRev'] = 'El campo idRevision no puede estar vacío';
                }else{
                    $idRev = trim($data['idRevision']);

                    if($idRev == ''){
                        $errors['idRev'] = 'El campo IdRev no puede estar vacío';
                    } else if(!preg_match('/^[0-9]+$/', $idRev)){
                        $errors['idRev'] = 'El campo IdRev no contiene un formato correcto';
                    }
                }

                if(empty($errors)){
                    $revision = Revisiones::getRevisionById($idRev);

                    $response['code'] = 200;
                    $response['msg'] = $revision;
                }else{
                    $msgError = [];

                    if(isset($errors['usuario'])){
                        $msgError[] = [
                            'campo' => 'usuario',
                            'error' => $errors['usuario']
                        ];
                    };

                    if(isset($errors['idRev'])){
                        $msgError[] = [
                            'campo' => 'idRev',
                            'error' => $errors['idRev']
                        ];
                    };

                    $response['code'] = 400;
                    $response['msg'] = $msgError;
                }
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