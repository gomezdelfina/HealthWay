<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

    $response = [];
    $errors = [];

    $idPaciente = '';
    $tipoRev = '';
    $fechaCreacion = date('Y-m-d');
    $horaCreacion = date('H:i');
    $fechaInicio = '';
    $horaInicio = '';
    $fechaFin = '';
    $frecuencia = '';
    $frecuenciaHoras = '';
    $frecuenciaDias = '';
    $frecuenciaSemanas = '';
    $recordatorioLunes = '';
    $recordatorioMartes = '';
    $recordatorioMiercoles = '';
    $recordatorioJueves = '';
    $recordatorioViernes = '';
    $recordatorioSabado = '';
    $recordatorioDomingo = '';
    $recAct = '';
    $observaciones = '';

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
            $errors['IdPaciente'] = 'El campo Paciente no puede faltar en la petición';
        } else {
            $idPaciente = trim($data['IdPaciente']);

            if ($idPaciente == '') {
                $errors['IdPaciente'] = 'El campo Paciente no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $idPaciente)) {
                $errors['IdPaciente'] = 'El campo Paciente no tiene el formato correcto';
            } else {
                $inter = Internaciones::getInternacionActivaByPaciente($idPaciente);

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
            $userId = trim($_SESSION['usuario']);

            if ($userId == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            }
        }

        //ID TIPO REVISION
        if (!isset($data['TipoRev'])) {
            $errors['TipoRev'] = 'El campo TipoRev no puede faltar en la petición';
        } else {
            $tipoRev = trim($data['TipoRev']);

            if ($tipoRev == '') {
                $errors['TipoRev'] = 'El campo TipoRev no puede estar vacío';
            } elseif (!preg_match('/^[0-9]+$/', $tipoRev)) {
                $errors['TipoRev'] = 'El campo TipoRev tiene un formato incorrecto';
            }
        }

        //FECHA CREACION
        if ($fechaCreacion == '') {
            $errors['FechaCreacion'] = 'El campo FechaCreacion no puede estar vacío';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaCreacion)) {
            $errors['FechaCreacion'] = 'El campo FechaCreacion tiene un formato incorrecto';
        }

        //HORA CREACION
        if ($horaCreacion == '') {
            $errors['HoraCreacion'] = 'El campo HoraCreacion no puede estar vacío';
        } elseif (!preg_match('/^\d{2}:\d{2}$/', $horaCreacion)) {
            $errors['HoraCreacion'] = 'El campo HoraCreacion tiene un formato incorrecto';
        }

        //FECHA INICIO
        if (!isset($data['FechaInicio'])) {
            $errors['FechaInicio'] = 'El campo FechaInicio no puede faltar en la petición';
        } else {
           $fechaInicio = trim($data['FechaInicio']);

            if ($fechaCreacion == '') {
                $errors['FechaInicio'] = 'El campo FechaInicio no puede estar vacío';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
                $errors['FechaInicio'] = 'El campo FechaInicio tiene un formato incorrecto';
            }
        }

        //HORA INICIO
        if (!isset($data['HoraInicio'])) {
            $errors['HoraInicio'] = 'El campo HoraInicio no puede faltar en la petición';
        } else {
            $horaInicio = trim($data['HoraInicio']);

            if ($horaInicio == '') {
                $errors['HoraInicio'] = 'El campo HoraInicio no puede estar vacío';
            } elseif (!preg_match('/^\d{2}:\d{2}$/', $horaInicio)) {
                $errors['HoraInicio'] = 'El campo HoraInicio tiene un formato incorrecto';
            }
        }

        //FECHA FIN
        if (isset($data['FechaFin'])) {
            $fechaFin = trim($data['FechaFin']);

            if ($fechaFin != '') {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                    $errors['FechaFin'] = 'El campo FechaFin tiene un formato incorrecto';
                }
            }
        }

        //FRECUENCIA
        if (!isset($data['Frecuencia'])) {
            $errors['Frecuencia'] = 'El campo Frecuencia no puede faltar en la petición';
        } else {
            $frecuencia = trim($data['Frecuencia']);

            if ($frecuencia == '') {
                $errors['Frecuencia'] = 'El campo Frecuencia no puede estar vacío';
            } elseif ($frecuencia != 'Diaria' &
                      $frecuencia != 'Semanal' &
                      $frecuencia != 'Unica Vez' &
                      $frecuencia != 'Horas') {
                $errors['Frecuencia'] = 'El campo Frecuencia tiene un formato incorrecto';
            }
        }

        //FRECUENCIA SEGUN TIPO
        if($frecuencia == 'Horas'){
            //FRECUENCIA HORAS
            if (!isset($data['FrecuenciaHoras'])) {
                $errors['FrecuenciaHoras'] = 'El campo FrecuenciaHoras no puede faltar en la petición';
            } else {
                $frecuenciaHoras = trim($data['FrecuenciaHoras']);

                if ($frecuenciaHoras == '') {
                    $errors['FrecuenciaHoras'] = 'El campo FrecuenciaHoras no puede estar vacío';
                } elseif (!preg_match('/^[0-9]+$/', $frecuenciaHoras)) {
                    $errors['FrecuenciaHoras'] = 'El campo FrecuenciaHoras tiene un formato incorrecto';
                }
            }
        } else if ($frecuencia == 'Diaria'){
            //FRECUENCIA DIARIA
            if (!isset($data['FrecuenciaDias'])) {
                $errors['FrecuenciaDias'] = 'El campo FrecuenciaDias no puede faltar en la petición';
            } else {
                $frecuenciaDias = trim($data['FrecuenciaDias']);

                if ($frecuenciaDias == '') {
                    $errors['FrecuenciaDias'] = 'El campo FrecuenciaDias no puede estar vacío';
                } elseif (!preg_match('/^[0-9]+$/', $frecuenciaDias)) {
                    $errors['FrecuenciaDias'] = 'El campo FrecuenciaDias tiene un formato incorrecto';
                }
            }
        } else if ($frecuencia == 'Semanal'){
            //FRECUENCIA SEMANAL
            if (!isset($data['FrecuenciaSem'])) {
                $errors['FrecuenciaSem'] = 'El campo FrecuenciaSem no puede faltar en la petición';
            } else {
                $frecuenciaSemanas = trim($data['FrecuenciaSem']);

                if ($frecuenciaSemanas == '') {
                    $errors['FrecuenciaSem'] = 'El campo FrecuenciaSem no puede estar vacío';
                } elseif (!preg_match('/^[0-9]+$/', $frecuenciaSemanas)) {
                    $errors['FrecuenciaSem'] = 'El campo FrecuenciaSem tiene un formato incorrecto';
                }
            }

            if(!isset($data['RepetirLunes']) &
               !isset($data['RepetirMartes']) &
               !isset($data['RepetirMiercoles']) &
               !isset($data['RepetirJueves']) &
               !isset($data['RepetirViernes']) &
               !isset($data['RepetirSabado']) &
               !isset($data['RepetirDomingo']) ){
                    $errors['FrecuenciaSem'] = 'Los campos de repeticion no pueden faltar en la petición';
            } else {
                $recordatorioLunes = trim($data['RepetirLunes']);
                $recordatorioMartes = trim($data['RepetirMartes']);
                $recordatorioMiercoles = trim($data['RepetirMiercoles']);
                $recordatorioJueves = trim($data['RepetirJueves']);
                $recordatorioViernes = trim($data['RepetirViernes']);
                $recordatorioSabado = trim($data['RepetirSabado']);
                $recordatorioDomingo = trim($data['RepetirDomingo']);

                if($recordatorioLunes == '' &
                   $recordatorioMartes == '' &
                   $recordatorioMiercoles == '' &
                   $recordatorioJueves == '' &
                   $recordatorioViernes == '' &
                   $recordatorioSabado == '' &
                   $recordatorioDomingo == '' ){
                        $errors['FrecuenciaSem'] = 'Debe existir seleccionado al menos un día de repetición';
                   }else if(!preg_match('/^[0-1]$/', $recordatorioLunes) &
                            !preg_match('/^[0-1]$/', $recordatorioMartes) &
                            !preg_match('/^[0-1]$/', $recordatorioMiercoles) &
                            !preg_match('/^[0-1]$/', $recordatorioJueves) &
                            !preg_match('/^[0-1]$/', $recordatorioViernes) &
                            !preg_match('/^[0-1]$/', $recordatorioSabado) &
                            !preg_match('/^[0-1]$/', $recordatorioDomingo)){
                                $errors['FrecuenciaSem'] = 'Los campos de dia de repeticion tienen un formato incorrecto';
                    }
            }

        }
        
        //CAMPO ACTIVO
        if(!isset($data['Activo'])){
            $errors['Activo'] = 'El campo Activo no puede faltar en la petición';
        } else {
            $recAct = trim($data['Activo']);

            if($recAct == ''){
                $errors['Activo'] = 'El campo Activo no puede estar vacío';
            }else if(!preg_match('/^[0-1]$/', $recAct)){
                $errors['Activo'] = 'El campo Activo tiene un formato incorrecto';
            }
        }

       // OBSERVACIONES
        if (!isset($data['Observaciones'])) {
            $errors['Observaciones'] = 'El campo Observaciones no puede faltar en la petición';
        } else {
            $observaciones = trim($data['Observaciones']);
        }

        if (empty($errors)) {
            try{
                $dtCreacion = $fechaCreacion . ' ' . $horaCreacion . ':00';

                $dtInicio = $fechaInicio . ' ' . $horaInicio . ':00';

                $dtFin = $fechaFin != '' ? $fechaFin : null;

                $rec = [
                    'IdInternacion' => $idInter,
                    'IdUsuario' => $userId,
                    'TipoRevision' => $tipoRev,
                    'FechaCreacion' => $dtCreacion,
                    'Estado' => 'No Hecho',
                    'FechaInicioRec' => $dtInicio,
                    'FechaFinRec' => empty($dtFin) ? null : $dtFin,
                    'Frecuencia' => $frecuencia,
                    'FrecuenciaHoras' => empty($frecuenciaHoras) ? null : $frecuenciaHoras,
                    'FrecuenciaDias' => empty($frecuenciaDias) ? null : $frecuenciaDias,
                    'FrecuenciaSem' => empty($frecuenciaSemanas) ? null : $frecuenciaSemanas,
                    'RepetirLunes' => empty($recordatorioLunes) ? 0 : 1, 
                    'RepetirMartes' => empty($recordatorioMartes) ? 0 : 1, 
                    'RepetirMiercoles' => empty($recordatorioMiercoles) ? 0 : 1, 
                    'RepetirJueves' => empty($recordatorioJueves) ? 0 : 1, 
                    'RepetirViernes' => empty($recordatorioViernes) ? 0 : 1, 
                    'RepetirSabado' => empty($recordatorioSabado) ? 0 : 1, 
                    'RepetirDomingo' => empty($recordatorioDomingo) ? 0 : 1, 
                    'Observaciones' => empty($observaciones) ? null : $observaciones,
                    'activo' => empty($recAct) ? 0 : 1
                ];

                $recGenerado = Recordatorio::createRecordatorio($rec);

                $response['code'] = 200;
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
            if(isset($errors['usuario'])){
                $msgError[] = [
                    'campo' => 'usuario',
                    'error' => $errors['usuario']
                ];
            };
            if(isset($errors['TipoRev'])){
                $msgError[] = [
                    'campo' => 'TipoRev',
                    'error' => $errors['TipoRev']
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
            if(isset($errors['FechaInicio'])){
                $msgError[] = [
                    'campo' => 'FechaInicio',
                    'error' => $errors['FechaInicio']
                ];
            };
            if(isset($errors['HoraInicio'])){
                $msgError[] = [
                    'campo' => 'HoraInicio',
                    'error' => $errors['HoraInicio']
                ];
            };
            if(isset($errors['FechaFin'])){
                $msgError[] = [
                    'campo' => 'FechaFin',
                    'error' => $errors['FechaFin']
                ];
            };
            if(isset($errors['Frecuencia'])){
                $msgError[] = [
                    'campo' => 'Frecuencia',
                    'error' => $errors['Frecuencia']
                ];
            };
            if(isset($errors['FrecuenciaHoras'])){
                $msgError[] = [
                    'campo' => 'FrecuenciaHoras',
                    'error' => $errors['FrecuenciaHoras']
                ];
            };
            if(isset($errors['FrecuenciaDias'])){
                $msgError[] = [
                    'campo' => 'FrecuenciaDias',
                    'error' => $errors['FrecuenciaDias']
                ];
            };
            if(isset($errors['RepetirLunes'])){
                $msgError[] = [
                    'campo' => 'RepetirLunes',
                    'error' => $errors['RepetirLunes']
                ];
            };
            if(isset($errors['RepetirMartes'])){
                $msgError[] = [
                    'campo' => 'RepetirMartes',
                    'error' => $errors['RepetirMartes']
                ];
            };
            if(isset($errors['RepetirMiercoles'])){
                $msgError[] = [
                    'campo' => 'RepetirMiercoles',
                    'error' => $errors['RepetirMiercoles']
                ];
            };
            if(isset($errors['RepetirJueves'])){
                $msgError[] = [
                    'campo' => 'RepetirJueves',
                    'error' => $errors['RepetirJueves']
                ];
            };
            if(isset($errors['RepetirViernes'])){
                $msgError[] = [
                    'campo' => 'RepetirViernes',
                    'error' => $errors['RepetirViernes']
                ];
            };
            if(isset($errors['RepetirSabado'])){
                $msgError[] = [
                    'campo' => 'RepetirSabado',
                    'error' => $errors['RepetirSabado']
                ];
            };
            if(isset($errors['RepetirDomingo'])){
                $msgError[] = [
                    'campo' => 'RepetirDomingo',
                    'error' => $errors['RepetirDomingo']
                ];
            };
            if(isset($errors['Observaciones'])){
                $msgError[] = [
                    'campo' => 'Observaciones',
                    'error' => $errors['Observaciones']
                ];
            };
            if(isset($errors['Activo'])){
                $msgError[] = [
                    'campo' => 'Activo',
                    'error' => $errors['Activo']
                ];
            };

            
            $response['code'] = 400;
            $response['msg'] = $msgError;
        }
    }
    
    header('Content-Type: application/json');
    http_response_code($response['code']);
    if($response['code'] != 200){
        echo json_encode([
            'error' => $response['msg'], 
        ]);
    }else{
        echo json_encode($recGenerado);
    }
    
?>