<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');

    $response = [];
    $errors = [];

    $pac = '';
    $idInter = '';
    $userId = '';
    $fecha = '';
    $hora = '';
    $tipoRevis = '';
    $estadoRevis = '';
    $sintomaRevi = '';
    $diagRevi = '';
    $tratamRevi = '';
    $notasRevi = '';

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
        //VALIDACIONES
        //ID REVISION
        if (!isset($data['IdRevision'])) {
            $errors['IdRevision'] = 'El campo IdRevision no puede estar vacío';
        } else {
            $IdRevision= $data['IdRevision'];

            if (trim($IdRevision) == '') {
                $errors['IdRevision'] = 'El campo IdRevision no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $IdRevision)) {
                $errors['IdRevision'] = 'El campo IdRevision tiene un formato incorrecto';
            }
        }

        //ID TIPO REVISION
        if (!isset($data['TipoRev'])) {
            $errors['TipoRev'] = 'El campo TipoRev no puede estar vacío';
        } else {
            $tipoRevis = $data['TipoRev'];

            if (trim($tipoRevis) == '') {
                $errors['TipoRev'] = 'El campo TipoRev no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $tipoRevis)) {
                $errors['TipoRev'] = 'El campo TipoRev tiene un formato incorrecto';
            }
        }

        //ID ESTADO REVISION
        if (!isset($data['EstadoRev'])) {
            $errors['EstadoRev'] = 'El campo EstadoRev no puede estar vacío';
        } else {
            $estadoRevis = $data['EstadoRev'];

            if (trim($estadoRevis) == '') {
                $errors['EstadoRev'] = 'El campo EstadoRev no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $estadoRevis)) {
                $errors['EstadoRev'] = 'El campo EstadoRev no puede estar vacío';
            }
        }

        //SINTOMAS
        if (!isset($data['Sintomas'])) {
            $errors['Sintomas'] = 'El campo Sintomas no puede estar vacío';
        } else {
            $sintomaRevi = $data['Sintomas'];

            if (trim($sintomaRevi) == '') {
                $errors['Sintomas'] = 'El campo Sintomas no puede estar vacío';
            }
        }

        //DIAGNOSTICO
        if (!isset($data['Diagnostico'])) {
            $errors['Diagnostico'] = 'El campo Diagnostico no puede estar vacío';
        } else {
            $diagRevi = $data['Diagnostico'];

            if (trim($diagRevi) == '') {
                $errors['Diagnostico'] = 'El campo Diagnostico no puede estar vacío';
            }
        }

        //-- TRATAMIENTO
        if (!isset($data['Tratamiento'])) {
            $errors['Tratamiento'] = 'El campo Tratamiento no puede estar vacío';
        } else {
            $tratamRevi = $data['Tratamiento'];

            if (trim($tratamRevi) == '') {
                $errors['Tratamiento'] = 'El campoTratamiento no puede estar vacío';
            }
        }

        //-- NOTAS
        if (!isset($data['Notas'])) {
            $notasRevi = '';
        } else {
            $notasRevi = $data['Notas'];
        }

        if (empty($errors)) {
            try{
                
                $revision = [
                    "IdRevision" => $IdRevision,
                    "TipoRevision" => $tipoRevis,
                    "EstadoRevision" => $estadoRevis,
                    "Sintomas" => $sintomaRevi,
                    "Diagnostico" => $diagRevi,
                    "Tratamiento" => $tratamRevi,
                    "Observaciones" => $notasRevi
                ];

                $revEditada = Revisiones::editRevision($revision);

                $response['code'] = 200;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }else{
            $response['code'] = 400;
            $response['msg'] = 'Problemas al validar el formato de los campos: ' .
                (isset($errors['IdRevision']) ? $errors['IdRevision'] : '') . 
                (isset($errors['TipoRev']) ? $errors['TipoRev'] : '') . 
                (isset($errors['EstadoRev']) ? $errors['EstadoRev'] : '') . 
                (isset($errors['Sintomas']) ? $errors['Sintomas'] : '') . 
                (isset($errors['Diagnostico']) ? $errors['Diagnostico'] : '') . 
                (isset($errors['Tratamiento']) ? $errors['Tratamiento'] : '');
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($revEditada);
    }
    
?>