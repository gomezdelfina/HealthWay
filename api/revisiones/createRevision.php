<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

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
        //ID PACIENTE
        if (!isset($data['IdPaciente'])) {
            $errors['IdPaciente'] = 'El campo Paciente no puede estar vacío';
        } else {
            $pac = $data['IdPaciente'];

            if (trim($pac) == '') {
                $errors['IdPaciente'] = 'El campo Paciente no puede estar vacío';
            } elseif (!preg_match('/^[1-9]+$/', $pac)) {
                $errors['IdPaciente'] = 'El campo Paciente no tiene el formato correcto';
            } else {
                $inter = Internaciones::getInternacionActivaByPaciente($pac);

                if(empty($inter)){
                    $errors['IdPaciente'] = 'El Paciente no tiene internaciones activas';
                }else{
                    $idInter = $inter[0]['IdInternacion'];
                }
            }
        }

        //ID USER
        if (!isset($_SESSION['usuario'])) {
            $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
        } else {
            $userId = $_SESSION['usuario'];

            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[1-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            }
        }

        //FECHA CREACION
        if (!isset($data['FechaCreacion'])) {
            $errors['FechaCreacion'] = 'El campo FechaCreacion no puede estar vacío';
        } else {
           $fecha = $data['FechaCreacion'];

            if (trim($fecha) == '') {
                $errors['FechaCreacion'] = 'El campo FechaCreacion no puede estar vacío';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                $errors['FechaCreacion'] = 'El campo FechaCreacion tiene un formato incorrecto';
            }
        }

        //HORA CREACION
        if (!isset($data['HoraCreacion'])) {
            $errors['HoraCreacion'] = 'El campo HoraCreacion no puede estar vacío';
        } else {
            $hora = $data['HoraCreacion'];

            if (trim($hora) == '') {
                $errors['HoraCreacion'] = 'El campo HoraCreacion no puede estar vacío';
            } elseif (!preg_match('/^\d{2}:\d{2}$/', $hora)) {
                $errors['HoraCreacion'] = 'El campo HoraCreacion tiene un formato incorrecto';
            }
        }

        //ID TIPO REVISION
        if (!isset($data['TipoRev'])) {
            $errors['TipoRev'] = 'El campo TipoRev no puede estar vacío';
        } else {
            $tipoRevis = $data['TipoRev'];

            if (trim($tipoRevis) == '') {
                $errors['TipoRev'] = 'El campo TipoRev no puede estar vacío';
            } elseif (!preg_match('/^[1-9]+$/', $tipoRevis)) {
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
            } elseif (!preg_match('/^[1-9]+$/', $estadoRevis)) {
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
                $dtString = $fecha . ' ' . $hora . ':00';
                $dtFormat = 'Y-m-d H:i:s';
                $dt = DateTime::createFromFormat($dtFormat, $dtString);

                $revision = [
                    "IdInternacion" => $idInter,
                    "IdUsuario" => $userId,
                    "FechaCreacion" => $dt->format('Y-m-d H:i:s'),
                    "TipoRevision" => $tipoRevis,
                    "EstadoRevision" => $estadoRevis,
                    "Sintomas" => $sintomaRevi,
                    "Diagnostico" => $diagRevi,
                    "Tratamiento" => $tratamRevi,
                    "Observaciones" => $notasRevi
                ];

                $revGenerada = Revisiones::createRevision($revision);

                $response['code'] = 200;
            }catch(Exception $e){
                $response['code'] = 500;
                $response['msg'] = 'Error interno de aplicacion';
            }
        }else{
            $response['code'] = 400;
            $response['msg'] = 'Problemas al validar el formato de los campos: ' .
                (isset($errors['IdPaciente']) ? $errors['IdPaciente'] : '') . 
                (isset($errors['usuario']) ? $errors['usuario'] : '') .
                (isset($errors['FechaCreacion']) ? $errors['FechaCreacion'] : '') . 
                (isset($errors['HoraCreacion'] ) ? $errors['HoraCreacion']  : '') . 
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
        echo json_encode($revGenerada);
    }
    
?>