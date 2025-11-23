<?php
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $fechaRevis = date('Y-m-d');
    $horaRevis = date('H:i');
?>
<!-- Modal Revisiones -->
<div class="modal fade" id="modalRevision" tabindex="-1" aria-labelledby="modalRevisionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header card-header-color text-white">
                <h5 class="modal-title" id="modalRevisionLabel">
                    <i class="bi bi bi-card-list me-2"></i>
                    <span id="tituloModalRevision">Nueva Revision</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body div-modal-body">
                <form id="revisionForm" method="POST">
                    <div class="row">
                        <div class="col-4 mt-1">
                            <!-- MUESTRA PACIENTES DE INTERNACIONES ACTIVAS (CLAVE: ID INTERNACION / VALOR: PACIENTE CORRESP)-->
                            <label class="form-label" for="revPac">Paciente</label>
                            <select class="form-select" id="revPac" name="revPac">
                                <option value='-1' selected="true">Seleccionar un paciente</option>
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
                            <!-- MUESTRA TIPOS DE REVISIONES SEGUN ROL-->
                            <label class="form-label" for="tipoRevis">Tipo</label>
                            <select class="form-select" id="tipoRevis" name="tipoRevis">
                                <option value='-1' selected="true">Seleccionar tipo de revisión</option>
                            </select>
                            <div id="valTipoR" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un tipo de revisión
                            </div>
                        </div>
                        <div class="col-6 mt-1">
                            <!-- MUESTRA ESTADOS DE REVISIONES SEGUN ROL-->
                            <label class="form-label" for="estadoRevis">Estado</label>
                            <select class="form-select" id="estadoRevis" name="estadoRevis">
                                <option value='-1' selected="true">Seleccionar estado de revisión</option>
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
                <?php if (userTienePermiso(9, $idUser)) { ?>
                    <button type="button" class="btn btn-sm btn-outline-dark me-2" id="btnEditarRev">
                        <i class="bi bi-pen"></i>
                    </button>
                <?php } ?>
                <button type="button" class="btn-cancelEmail" form="revisionForm" id="btnCancelRevisionForm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-submit" form="revisionForm" id="btnCreateRev">Crear</button>
                <button type="submit" class="btn-submit" form="revisionForm" id="btnActualizarRev">Actualizar</button>
            </div>
        </div>
    </div>
</div>