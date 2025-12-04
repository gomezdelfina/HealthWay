<?php
;    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');
    require_once($dirBaseFile . '/dataAccess/recordatorios.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/notificaciones.php');

    $response = [];
    $errors = [];
    $userId = '';

    try{
        // -- VALIDACIONES
        //ID USER
        if (!isset($_SESSION['usuario'])) {
            $response['code'] = 401;
            $response['msg'] = 'El Usuario no esta logeado en el sistema';
        } elseif(!Permisos::tienePermiso(12, $_SESSION['usuario'])){
            $response['code'] = 401;
            $response['msg'] = 'El usuario no tiene permiso para la peticion';
        } else {
            $userId = $_SESSION['usuario'];

            if (trim($userId) == '') {
                $errors['usuario'] = 'El Usuario no esta logeado en el sistema';
            } elseif (!preg_match('/^[0-9]+$/', $userId)) {
                $errors['usuario'] = 'El campo Usuario no tiene el formato correcto';
            }

            if(empty($errors)){
                $recs = Recordatorio::getRecordatoriosActivosByUser($userId);

                $response['code'] = 200;
                $response['msg'] = $recs;

                $ahora = new DateTime();
                $ahora = $ahora->format('Y-m-d H:i');
                foreach ($recs as $rec) {
                    if($rec['ProximaEjecucion'] != null){
                        $proxEjec = $rec['ProximaEjecucion'];
                            
                        // Si debe ejecutarse, crear notificación
                        if ($ahora == $proxEjec) {
                            $tipoRev = $rec['TipoRevision'];
                            $permiso = 0;

                            switch($tipoRev){
                                case 1: 
                                    $permiso = 15;
                                    break;
                                case 2:
                                    $permiso = 16;
                                    break;
                                case 3:
                                    $permiso = 17;
                                    break;
                                case 4:
                                    $permiso = 18;
                                    break;
                                case 5:
                                    $permiso = 19;
                                    break;
                                case 6:
                                    $permiso = 20;
                                    break;
                                case 7:
                                    $permiso = 27;
                                    break;
                            }

                            //Datos de Notificacion
                            $inter = $rec['IdInternacion'];
                            $roles = Permisos::getRolByPermiso($permiso);
                            $inter = internaciones::ObtenerInternacion($inter);
                            $pac = $inter['data']['NombrePaciente'];

                            //Creacion de notificacion para cada rol
                            foreach($roles as $rol){
                                $notifCreada = Notificaciones::crear($rol['DescRol'],'Recordatorio de ' . $tipoRev, 
                                'Existe una revisión pendiente de tipo ' . $tipoRev . 'en paciente ' . $pac);
                            }

                            //Cambio de estado de recordatorio
                            $recordatorio = [
                                'IdRecordatorio' => $rec['IdRecordatorio'],
                                'Estado' => 'Atrasado'
                            ];

                            $editRec = Recordatorio::editRecordatorioEstado($recordatorio);
                        }else {
                            if ($rec['Estado'] == 'Hecho'){
                                //Cambio de estado de recordatorio
                                $recordatorio = [
                                    'IdRecordatorio' => $rec['IdRecordatorio'],
                                    'Estado' => 'Pendiente'
                                ];

                                $editRec = Recordatorio::editRecordatorioEstado($recordatorio);
                            }
                        }
                    }
                }
            }else{
                $msgError = [];

                if(isset($errors['usuario'])){
                    $msgError[] = [
                        'campo' => 'usuario',
                        'error' => $errors['usuario']
                    ];
                };

                $response['code'] = 400;
                $response['msg'] = $msgError;
            }
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['msg'] = 'Error interno de aplicacion';
    }

    header('Content-Type: application/json');
    http_response_code($response['code']);
    echo json_encode($response['msg']);

?>