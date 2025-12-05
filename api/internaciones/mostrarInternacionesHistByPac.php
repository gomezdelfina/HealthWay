<?php

    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/pacientes.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $response = [];
    $errors = [];
    $idPaciente = '';
    $internacionesHist = [];

    if (!isset($_SESSION['usuario'])) {
        $response['code'] = 401;
        $response['msg'] = 'El Usuario no esta logeado en el sistema';
    } elseif(!Permisos::tienePermiso(48, $_SESSION['usuario'])){
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
            //ID PACIENTE
            if(!isset($data['idPaciente'])){
                $errors['idPaciente'] = 'El campo idPaciente no puede estar vacío';
            }else{
                $idPaciente = trim($data['idPaciente']);

                if($idPaciente == ''){
                    $errors['idPaciente'] = 'El campo idPaciente no puede estar vacío';
                }else if(!preg_match('/^[0-9]+$/', $idPaciente)){
                    $errors['idPaciente'] = 'El campo idPaciente no contiene un formato correcto';
                }
            }

            if(empty($errors)){
                try{
                    $paciente = Pacientes::getPacienteById($idPaciente);

                    $internaciones = internaciones::VerInternacionActivaByPac($idPaciente);

                    $internacionesHist = $internaciones;
                    foreach ($internacionesHist as $key => $row) {
                        $revisiones = Revisiones::getRevisionByInter($internacionesHist[$key]['IdInternacion']);
                        $internacionesHist[$key]['Revisiones'] = $revisiones;
                    }

                    $response['code'] = 200;
                    $response['msg'] = $internaciones;
                }catch(Exception $e){
                    $response['code'] = 500;
                    $response['msg'] = 'Error interno de aplicacion';
                }
            }else{
                $msgError = [];

                if(isset($errors['idPaciente'])){
                    $msgError[] = [
                        'campo' => 'idPaciente',
                        'error' => $errors['idPaciente']
                    ];
                };

                $response['code'] = 400;
                $response['msg'] = $msgError;
            }
        }
    }

    if($response['code'] != 200){
        header('Content-Type: application/json');
        http_response_code($response['code']);
        echo json_encode($response['msg']);
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css -->
    <link href="<?php echo $dirBaseUrl ?>/styles/styles.css" rel="stylesheet">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <title>Healthway - Historia Clinica</title>
</head>

<body>
    <div class="container">
        <div class="doc-container">

            <!-- INFORMACIÓN DEL PACIENTE -->
            <div class="row mb-4">
                <div class="col-12 text-center mb-4">
                    <h2 class="fw-bold">Historial Médico Digital</h2>
                    <p class="text-muted">Reporte generado automáticamente</p>
                </div>

                <div class="col-12">
                    <h4 class="section-title"><i class="bi bi-person-vcard"></i> Datos del Paciente</h4>
                </div>

                <div class="col-md-4">
                    <div class="label-text">Paciente</div>
                    <div class="value-text"><?php if(isset($paciente[0]['Nombre'])) { echo $paciente[0]['Nombre']; } ?></div>
                </div>
                <div class="col-md-4">
                    <div class="label-text">DNI</div>
                    <div class="value-text"><?php if(isset($paciente[0]['DNI'])) { echo $paciente[0]['DNI']; } ?></div>
                </div>
                <div class="col-md-4">
                    <div class="label-text">Fecha Nacimiento / Edad</div>
                    <div class="value-text">
                        <?php if(isset($paciente[0]['FechaNacimiento'])) { echo $paciente[0]['FechaNacimiento'];} ?>
                        <!-- Calculo simple de edad opcional -->
                        <small class="text-muted">(<?php
                            if(isset($paciente[0]['FechaNacimiento'])) { 
                                date_diff(date_create($paciente[0]['FechaNacimiento']), date_create('today'))->y ; 
                            } ?> años)</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="label-text">Género</div>
                    <div class="value-text"><?php if(isset($paciente[0]['Genero'])) { echo $paciente[0]['Genero'];} ?></div>
                </div>
                <div class="col-md-4">
                    <div class="label-text">Obra Social</div>
                    <div class="value-text">
                        <?php if(isset($paciente[0]['ObraSocial'])) { echo $paciente[0]['ObraSocial'];} ?> 
                        <span class="badge bg-secondary"><?php if(isset($paciente[0]['Plan'])) { echo $paciente[0]['Plan'];} ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="label-text">Dirección</div>
                    <div class="value-text"><?php if(isset($paciente[0]['Direccion'])) { echo $paciente[0]['Direccion'];} ?></div>
                </div>
            </div>

            <?php if (count($internacionesHist) > 0){ ?>
                <?php foreach ($internacionesHist as $internacionActual){ ?>
                    <!-- SECCIÓN: DETALLES DE INTERNACION -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="section-title"><i class="bi bi-hospital"></i> Detalle de Internación N° <?php if(isset($internacionActual['IdInternacion'])) { echo $internacionActual['IdInternacion'];} ?></h4>
                        </div>

                        <div class="col-md-3">
                            <div class="label-text">Ubicación</div>
                            <div class="value-text">
                                Hab: <?php if(isset($internacionActual['IdHabitacion'])) { echo $internacionActual['IdHabitacion']; } ?> - Cama: <?php if(isset($internacionActual['IdCama'])) { echo $internacionActual['IdCama']; } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-text">Estado</div>
                            <div class="value-text">
                                <span class="badge"><?php if(isset($internacionActual['EstadoInternacion'])) {echo $internacionActual['EstadoInternacion'];} ?></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-text">Fecha Ingreso</div>
                            <div class="value-text"><?php if(isset($internacionActual['FechaInicio'])) { echo $internacionActual['FechaInicio'];}  ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="label-text">Fecha Finalizacion</div>
                            <div class="value-text"><?php if(isset($internacionActual['FechaFin'])) {echo $internacionActual['FechaFin'];} ?></div>
                        </div>
                        <div class="col-12">
                            <div class="label-text">Notas de Ingreso</div>
                            <div class="value-text bg-light p-3 rounded border">
                                <?php if(isset($internacionActual['Notas'])) { echo $internacionActual['Notas'];} else { echo 'Sin notas registradas.' ;} ?>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN: REVISIONES MEDICAS -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="section-title"><i class="bi bi-journal-medical"></i> Revisiones</h4>
                        </div>

                        <div class="col-12">
                            <?php if (count($revisiones) > 0){ ?>
                                <?php foreach ($revisiones as $rev){ ?>
                                    <div class="card revision-card">
                                        <div class="revision-header">
                                            <div>
                                                <strong class="text-primary"><?php if(isset($rev['Tipo'])) { echo $rev['Tipo'];} ?></strong>
                                                <span class="text-muted ms-2 small"><i class="bi bi-clock"></i> <?php if(isset($rev['FechaCreacion'])) { echo $rev['FechaCreacion'];} ?></span>
                                            </div>
                                            <span class="badge bg-primary rounded-pill"><?php if(isset($rev['Estado'])) { echo $rev['Estado'];} ?></span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <strong class="d-block small text-muted">SÍNTOMAS</strong>
                                                    <?php if(isset($rev['Sintomas'])) { echo $rev['Sintomas'];} ?>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong class="d-block small text-muted">DIAGNÓSTICO</strong>
                                                    <?php if(isset($rev['Diagnostico'])) { echo $rev['Diagnostico'];} ?>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <strong class="d-block small text-muted">TRATAMIENTO</strong>
                                                    <?php if(isset($rev['Tratamiento'])) { echo $rev['Tratamiento'];} ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <strong class="d-block small text-muted">OBSERVACIONES</strong>
                                                    <p class="mb-0 fst-italic"><?php if(isset($rev['Observaciones'])) { echo $rev['Observaciones'];} ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-end text-muted small bg-white">
                                            <i class="bi bi-person-circle"></i> Profesional: <?php if(isset($rev['UsuarioCreador'])) {echo $rev['UsuarioCreador'];}  ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="alert alert-info text-center">
                                    <?php echo 'No hay revisiones registradas para esta internación.'; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="alert alert-info text-center">
                    <?php echo 'No hay internaciones registradas.'; ?>
                </div>
            <?php } ?>

            <!-- Footer del documento -->
            <div class="text-center mt-5 pt-3 border-top text-muted small">
                <p>Healthway SA - Documento de Consulta Exclusiva</p>
            </div>

        </div>
    </div>
</body>
</html>