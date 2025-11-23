<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/internaciones.php');

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
    $data = $resultado["data"];

    // Mostrás los datos como quieras:
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <title>Datos de la Internación</title>
    </head>
    <body>
        <div class="container mt-4">

            <form id="InternacionForm">

                <div class="row">

                    <div class="col-4">
                        <label class="form-label">N. Internación</label>
                        <input class="form-control" id="interNumber" readonly
                            value="<?= $data['IdInternacion'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" id="nombrePac" readonly
                            value="<?= $data['NombrePaciente'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Cama</label>
                        <input class="form-control" id="camaInter" readonly
                            value="<?= $data['IdCama'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Habitación</label>
                        <input class="form-control" id="habInter" readonly
                            value="<?= $data['IdHabitacion'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Estado</label>
                        <input class="form-control" id="estadoInter" readonly
                            value="<?= $data['EstadoInternacion'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Fecha Inicio</label>
                        <input class="form-control" id="fechaInter" readonly
                            value="<?= $data['FechaInicio'] ?>">
                    </div>

                    <div class="col-4">
                        <label class="form-label">Fecha Fin</label>
                        <input class="form-control" id="fechaFinInter" readonly
                            value="<?= $data['FechaFin'] ?>">
                    </div>

                    <div class="col-6">
                        <label class="form-label">Notas</label>
                        <input class="form-control" id="notasInter" readonly
                            value="<?= $data['Notas'] ?? '' ?>">
                    </div>

                </div>
            </form>
        </div>
    </body>
</html>