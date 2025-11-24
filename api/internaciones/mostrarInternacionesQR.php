<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');
    require_once($dirBaseFile . '/dataAccess/revisiones.php');
    require_once($dirBaseFile . '/dataAccess/pacientes.php');

    $id = $_GET["id"] ?? null;

    if (!$id) {
        exit("ID no proporcionado");
    }

    $resultado = internaciones::ObtenerInternacion($id);

    if ($resultado["status"] === "error") {
        echo $resultado["mensaje"];
        exit;
    }

    // Datos de la internación
    $internacionActual = $resultado["data"];

    $idPaciente = $internacionActual['IdPaciente'] ?? 0;
    $idInternacion = $internacionActual['IdInternacion'] ?? 0;

    try{
        $revisiones = Revisiones::getRevisionByInter($idInternacion);
    }catch(Exception $e){
        echo $e;
        exit;
    }
    
    try{
        $paciente = Pacientes::getPacienteById($idPaciente);
    }catch(Exception $e){
        echo $e;
        exit;
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
    <title>Healthway - Datos de la Internación</title>
</head>

<body>
    <div class="container">
        <div class="doc-container">

            <!-- HEADER: INFORMACIÓN DEL PACIENTE -->
            <div class="row mb-4">
                <div class="col-12 text-center mb-4">
                    <h2 class="fw-bold">Historial Médico Digital</h2>
                    <p class="text-muted">Reporte generado automáticamente</p>
                </div>

                <div class="col-12">
                    <h4 class="section-title"><i class="bi bi-person-vcard"></i> Datos del Paciente</h4>
                </div>

                <!-- Fila 1 Paciente -->
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

                <!-- Fila 2 Paciente -->
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

            <!-- SECCIÓN: DETALLES DE LA INTERNACIÓN -->
            <div class="row mb-5">
                <div class="col-12">
                    <h4 class="section-title"><i class="bi bi-hospital"></i> Detalle de Internación N° <?= $internacionActual['IdInternacion'] ?></h4>
                </div>

                <div class="col-md-3">
                    <div class="label-text">Ubicación</div>
                    <div class="value-text">
                        Hab: <?= $internacionActual['IdHabitacion'] ?> - Cama: <?= $internacionActual['IdCama'] ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="label-text">Estado</div>
                    <div class="value-text">
                        <?php
                        $badgeClass = ($internacionActual['EstadoInternacion'] == 'Activa') ? 'bg-success' : 'bg-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $internacionActual['EstadoInternacion'] ?></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="label-text">Fecha Ingreso</div>
                    <div class="value-text"><?= $internacionActual['FechaInicio'] ?></div>
                </div>
                <div class="col-md-3">
                    <div class="label-text">Fecha Alta</div>
                    <div class="value-text"><?= $internacionActual['FechaFin'] ?: '-' ?></div>
                </div>
                <div class="col-12">
                    <div class="label-text">Notas de Ingreso</div>
                    <div class="value-text bg-light p-3 rounded border">
                        <?= $internacionActual['Notas'] ?? 'Sin notas registradas.' ?>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: REVISIONES MEDICAS (TIMELINE) -->
            <div class="row">
                <div class="col-12">
                    <h4 class="section-title"><i class="bi bi-journal-medical"></i> Bitácora de Revisiones</h4>
                </div>

                <div class="col-12">
                    <?php if (count($revisiones) > 0): ?>
                        <?php foreach ($revisiones as $rev): ?>
                            <div class="card revision-card">
                                <div class="revision-header">
                                    <div>
                                        <strong class="text-primary"><?= $rev['Tipo'] ?></strong>
                                        <span class="text-muted ms-2 small"><i class="bi bi-clock"></i> <?= $rev['FechaCreacion'] ?></span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?= $rev['Estado'] ?></span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <strong class="d-block small text-muted">SÍNTOMAS</strong>
                                            <?= $rev['Sintomas'] ?>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong class="d-block small text-muted">DIAGNÓSTICO</strong>
                                            <?= $rev['Diagnostico'] ?>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <strong class="d-block small text-muted">TRATAMIENTO</strong>
                                            <?= $rev['Tratamiento'] ?>
                                        </div>
                                        <div class="col-md-12">
                                            <strong class="d-block small text-muted">OBSERVACIONES</strong>
                                            <p class="mb-0 fst-italic"><?= $rev['Observaciones'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end text-muted small bg-white">
                                    <i class="bi bi-person-circle"></i> Profesional: <?= $rev['UsuarioCreador'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            No hay revisiones registradas para esta internación.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer del documento -->
            <div class="text-center mt-5 pt-3 border-top text-muted small">
                <p>Healthway SA - Documento de Consulta Exclusiva</p>
            </div>

        </div>
    </div>
</body>
</html>