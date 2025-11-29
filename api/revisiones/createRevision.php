<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];
    $userId = '';
    
    $pac = '';
    $idInter = '';
    $fecha = '';
    $hora = '';
    $tipoRevis = '';
    $estadoRevis = '';
    $sintomaRevi = '';
    $diagRevi = '';
    $tratamRevi = '';
    $notasRevi = '';

    if (!isset($_SESSION['usuario'])) {
        $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
    } else {
        $userId = $_SESSION['usuario'];

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
                } elseif (!preg_match('/^[0-9]+$/', $pac)) {
                    $errors['IdPaciente'] = 'El campo Paciente no tiene el formato correcto';
                } elseif (!Permisos::tienePermiso(6, $userId)) {
                    $errors['IdPaciente'] = 'El usuario no tiene permiso para la visualizacion de internaciones';
                }else{
                    $inter = Internaciones::VerInternacionActivaByPac($pac);

                    if(empty($inter)){
                        $errors['IdPaciente'] = 'El Paciente no tiene internaciones activas';
                    }elseif($inter["status"] != "error"){
                        $idInter = $inter["data"]['IdInternacion'];
                    }
                }
            }

            //ID USER
            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            } elseif(!Permisos::tienePermiso(10, $userId)){
                $errors['usuario'] = 'El usuario no tiene permiso para la peticion';
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

            //TRATAMIENTO
            if (!isset($data['Tratamiento'])) {
                $errors['Tratamiento'] = 'El campo Tratamiento no puede estar vacío';
            } else {
                $tratamRevi = $data['Tratamiento'];

                if (trim($tratamRevi) == '') {
                    $errors['Tratamiento'] = 'El campoTratamiento no puede estar vacío';
                }
            }

            //NOTAS
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
                    $response['msg'] = $revGenerada;
                }catch(Exception $e){
                    $response['code'] = 500;
                    $response['msg'] = 'Error interno de aplicacion';
                }
            }else{
                $msgError = [];

                if(isset($errors['IdPaciente'])){
                     $msgError[] = [
                        'campo' => 'IdPaciente',
                        'error' => $errors['IdPaciente']
                    ];
                };

                if(isset($errors['FechaCreacion'])){
                     $msgError[] = [
                        'campo' => 'FechaCreacion',
                        'error' => $errors['FechaCreacion']
                    ];
                };

                if(isset($errors['HoraCreacion'])){
                     $msgError[] = [
                        'campo' => 'HoraCreacion',
                        'error' => $errors['HoraCreacion']
                    ];
                };

                if(isset($errors['TipoRev'])){
                     $msgError[] = [
                        'campo' => 'TipoRev',
                        'error' => $errors['TipoRev']
                    ];
                };

                if(isset($errors['usuario'])){
                     $msgError[] = [
                        'campo' => 'usuario',
                        'error' => $errors['usuario']
                    ];
                };

                if(isset($errors['Tratamiento'])){
                     $msgError[] = [
                        'campo' => 'Tratamiento',
                        'error' => $errors['Tratamiento']
                    ];
                };

                if(isset($errors['Diagnostico'])){
                     $msgError[] = [
                        'campo' => 'Diagnostico',
                        'error' => $errors['Diagnostico']
                    ];
                };

                if(isset($errors['Sintomas'])){
                     $msgError[] = [
                        'campo' => 'Sintomas',
                        'error' => $errors['Sintomas']
                    ];
                };

                if(isset($errors['EstadoRev'])){
                     $msgError[] = [
                        'campo' => 'EstadoRev',
                        'error' => $errors['EstadoRev']
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