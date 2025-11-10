<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
$errors = [];
$process = false;

$interPac = '';
$fechaRevis = date('Y-m-d');
$horaRevis = date('H:i');
$tipoRevis = '';
$estadoRevis = '';
$sintomaRevi = '';
$diagRevi = '';
$tratamRevi = '';
$notasRevi = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // VALIDACIONES
    //-- PACIENTE
    if (!isset($_POST['interPac'])) {
        $errors['interPac'] = 'Error al ingresar la revisión.';
    } else {
        $pac = $_POST['interPac'];

        if (trim($pac) == '-1') {
            $errors['interPac'] = 'Debe seleccionar un paciente.';
        }
    }

    //-- FECHA
    if (!isset($_POST['fechaRevis'])) {
        $errors['fechaRevis'] = 'Error al ingresar la revisión.';
    }

    //-- HORA
    if (!isset($_POST['horaRevis'])) {
        $errors['horaRevis'] = 'Error al ingresar la revisión.';
    }

    //-- TIPO REV.
    if (!isset($_POST['tipoRevis'])) {
        $errors['tipoRevis'] = 'Error al ingresar la revisión.';
    } else {
        $tipoRevis = $_POST['tipoRevis'];

        if (trim($tipoRevis) == '-1') {
            $errors['tipoRevis'] = 'Debe seleccionar un tipo de revisión.';
        }
    }

    //-- ESTADO REV.
    if (!isset($_POST['estadoRevis'])) {
        $errors['estadoRevis'] = 'Error al ingresar la revisión.';
    } else {
        $estadoRevis = $_POST['estadoRevis'];

        if (trim($estadoRevis) == '-1') {
            $errors['estadoRevis'] = 'Debe seleccionar un estado de revisión.';
        }
    }

    //-- SINTOMAS
    if (!isset($_POST['sintomaRevi'])) {
        $errors['sintomaRevi'] = 'Error al ingresar la revisión.';
    } else {
        $sintomaRevi = $_POST['sintomaRevi'];

        if (trim($sintomaRevi) == '') {
            $errors['sintomaRevi'] = 'El campo síntomas no puede estar vacío.';
        }
    }

    //-- DIAGNOSTICO
    if (!isset($_POST['diagRevi'])) {
        $errors['diagRevi'] = 'Error al ingresar la revisión.';
    } else {
        $diagRevi = $_POST['diagRevi'];

        if (trim($diagRevi) == '') {
            $errors['diagRevi'] = 'El campo diagnóstico no puede estar vacío.';
        }
    }

    //-- TRATAMIENTO
    if (!isset($_POST['tratamRevi'])) {
        $errors['tratamRevi'] = 'Error al ingresar la revisión.';
    } else {
        $tratamRevi = $_POST['tratamRevi'];

        if (trim($tratamRevi) == '') {
            $errors['tratamRevi'] = 'El campo tratamiento no puede estar vacío.';
        }
    }

    //-- NOTAS
    if (!isset($_POST['notasRevi'])) {
        $errors['notasRevi'] = 'Error al ingresar la revisión.';
    }

    // PROCESAR
    if (empty($errors)) {
        $process = true;
    }
} else {
    $errors = [];
}
?>
<div class="modal fade" id="modalRevision" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h4-modal-header" id="modalLabel">Revision</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body div-modal-body">
                <form id="revisionForm" action="" method="POST">
                    <div class="row">
                        <div class="col-4 mt-1">
                            <!-- MUESTRA PACIENTES DE INTERNACIONES ACTIVAS (CLAVE: ID INTERNACION / VALOR: PACIENTE CORRESP)-->
                            <label class="form-label" for="interPac">Paciente</label>
                            <select class="form-select" id="interPac" name="interPac">
                                <option value='-1' selected="true">Seleccionar un paciente</option>
                                <option value='1'>Diego Cesari</option>
                                <option value='2'>Marcos Perez</option>
                                <option value='3'>Ana Gómez</option>
                                <option value='0'>Lucas Hernandez</option>
                            </select>
                            <div id="valPac" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un paciente
                            </div>
                        </div>
                        <div class="col-4 mt-1">
                            <!-- MUESTRA FECHA ACTUAL -->
                            <label class="form-label" for="fechaRevis">Fecha revisión</label>
                            <input class="form-control" type="date" id="fechaRevis" name="fechaRevis" value="<?php echo ($fechaRevis); ?>" disabled>
                        </div>
                        <div class="col-4 mt-1">
                            <!-- MUESTRA HORA ACTUAL -->
                            <label class="form-label" for="horaRevis">Hora</label>
                            <input class="form-control" type="time" id="horaRevis" name="horaRevis" value="<?php echo ($horaRevis); ?>" disabled>
                        </div>
                        <div class="col-6 mt-1">
                            <!-- MUESTRA TIPOS DE REVISIONES -->
                            <label class="form-label" for="tipoRevis">Tipo</label>
                            <select class="form-select" id="tipoRevis" name="tipoRevis">
                                <option value='-1' selected="true">Seleccionar tipo de revisión</option>
                                <?php if ($userActive == "enfermera") { ?>
                                    <option value='1'>Signos vitales</option>
                                    <option value='2'>Alimentacion</option>
                                    <option value='3'>Higienizacion</option>
                                    <option value='4'>Medicacion</option>
                                <?php } elseif ($userActive == "medico") { ?>
                                    <option value='1'>Signos vitales</option>
                                    <option value='4'>Medicacion</option>
                                    <option value='5'>Intervención</option>
                                    <option value='6'>Intervención quirurgica</option>
                                <?php } elseif ($userActive == "medicoEsp") { ?>
                                    <option value='5'>Intervención</option>
                                    <option value='6'>Intervención quirurgica</option>
                                <?php } ?>
                                <option value='7'>Otro</option>
                            </select>
                            <div id="valTipoR" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un tipo de revisión
                            </div>
                        </div>
                        <div class="col-6 mt-1">
                            <!-- MUESTRA ESTADOS DE REVISIONES -->
                            <label class="form-label" for="estadoRevis">Estado</label>
                            <select class="form-select" id="estadoRevis" name="estadoRevis">
                                <option value='-1' selected="true">Seleccionar estado de revisión</option>
                                <?php if ($userActive == "enfermera") { ?>
                                    <option value='1'>Rutina</option>
                                    <option value='3'>Programada</option>
                                <?php } elseif ($userActive == "medico") { ?>
                                    <option value='1'>Rutina</option>
                                    <option value='2'>Urgencia</option>
                                    <option value='3'>Programada</option>
                                    <option value='4'>Alta</option>
                                    <option value='5'>Fallecimiento</option>
                                <?php } elseif ($userActive == "medicoEsp") { ?>
                                    <option value='1'>Rutina</option>
                                    <option value='2'>Urgencia</option>
                                    <option value='3'>Programada</option>
                                    <option value='4'>Alta</option>
                                    <option value='5'>Fallecimiento</option>
                                <?php } ?>
                                <option value='6'>Otra</option>
                            </select>
                            <div id="valEstR" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un estado de revisión
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <label class="form-label" for="sintomaRevi">Sintomas</label>
                            <textarea class="form-control" id="sintomaRevi" name="sintomaRevi"></textarea>
                            <div id="valSint" class="invalid-feedback visibility-hidden">
                                El campo síntomas no puede estar vacío
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <label class="form-label" for="diagRevi">Diagnostico</label>
                            <textarea class="form-control" id="diagRevi" name="diagRevi"></textarea>
                            <div id="valDiag" class="invalid-feedback">
                                El campo diagnóstico no puede estar vacío
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <label class="form-label" for="tratamRevi">Tratamiento</label>
                            <textarea class="form-control" id="tratamRevi" name="tratamRevi"></textarea>
                            <div id="valTratam" class="invalid-feedback">
                                El campo tratamiento no puede estar vacío
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <label class="form-label" for="notasRevi">Notas</label>
                            <textarea class="form-control" id="notasRevi" name="notasRevi"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancelEmail" form="revisionForm" id="btnCancelRevisionForm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-submit" form="revisionForm" id="btnCreateRev">Crear</button>
            </div>
        </div>
    </div>
</div>