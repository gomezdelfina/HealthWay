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
                    <div class="d-flex flex-column overflow-hidden w-100 h-100">
                        <!-- Sección General -->
                        <div class="mb-4">
                            <input type="hidden" id="idRevision" name="idRevision" value="">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle me-2"></i>Información General
                            </h6>

                            <div class="row">
                                <!-- MUESTRA PACIENTES DE INTERNACIONES ACTIVAS (CLAVE: ID INTERNACION / VALOR: PACIENTE CORRESP)-->
                                <div class="col-4 mb-3">
                                    <label class="form-label" for="revPac">Paciente</label>
                                    <select class="form-select" id="revPac" name="revPac">
                                        <option value='-1' selected="true">Seleccionar un paciente</option>
                                    </select>
                                    <div id="valPac" class="invalid-feedback">
                                        Debe seleccionar un paciente
                                    </div>
                                </div>
                        
                                <!-- MUESTRA FECHA ACTUAL -->
                                <div class="col-4 mb-3">
                                    <label class="form-label" for="fechaRevis">Fecha revisión</label>
                                    <input class="form-control" type="date" id="fechaRevis" name="fechaRevis" value="<?php echo ($fechaRevis); ?>" disabled>
                                </div>

                                <!-- MUESTRA HORA ACTUAL -->
                                <div class="col-4 mb-3">
                                    <label class="form-label" for="horaRevis">Hora</label>
                                    <input class="form-control" type="time" id="horaRevis" name="horaRevis" value="<?php echo ($horaRevis); ?>" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <!-- MUESTRA TIPOS DE REVISIONES SEGUN ROL-->
                                <div class="col-6 mt-1">
                                    <label class="form-label" for="tipoRevis">Tipo</label>
                                    <select class="form-select" id="tipoRevis" name="tipoRevis">
                                        <option value='-1' selected="true">Seleccionar tipo de revisión</option>
                                    </select>
                                    <div id="valTipoR" class="invalid-feedback">
                                        Debe seleccionar un tipo de revisión
                                    </div>
                                </div>

                                <!-- MUESTRA ESTADOS DE REVISIONES SEGUN ROL-->
                                <div class="col-6 mt-1">
                                    <label class="form-label" for="estadoRevis">Estado</label>
                                    <select class="form-select" id="estadoRevis" name="estadoRevis">
                                        <option value='-1' selected="true">Seleccionar estado de revisión</option>
                                    </select>
                                    <div id="valEstR" class="invalid-feedback">
                                        Debe seleccionar un estado de revisión
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección Descripcion -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-clipboard-pulse me-2"></i>Descripción de revisión
                            </h6>

                            <div class="row">
                                <div class="col-12 mt-1">
                                    <label class="form-label" for="sintomaRevi">Sintomas</label>
                                    <textarea class="form-control" id="sintomaRevi" name="sintomaRevi"></textarea>
                                    <div id="valSint" class="invalid-feedback">
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
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancelEmail" form="revisionForm" id="btnCancelRevisionForm" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn-submit" form="revisionForm" id="btnGuardarRevision">
                    Crear
                </button>
                <button type="submit" class="btn-submit" form="revisionForm" id="btnActRevision">
                    Actualizar
                </button>
            </div>
        </div>
    </div>
</div>