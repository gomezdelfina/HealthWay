<!-- Modal Recordatorio -->
<div class="modal fade" id="modalRecordatorio" tabindex="-1" aria-labelledby="modalRecordatorioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header card-header-color text-white">
                <h5 class="modal-title" id="modalRecordatorioLabel">
                    <i class="bi bi-calendar-event me-2"></i>
                    <span id="tituloModalRecordatorio">Recordatorio</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body div-modal-body">
                <form id="recordatorioForm" method="POST" novalidate>
                    <div class="d-flex flex-column overflow-hidden w-100 h-100">
                        <!-- Sección General -->
                        <div class="mb-4">
                            <input type="hidden" id="idRecordatorio" name="idRecordatorio" value="">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle me-2"></i>Información General
                            </h6>

                            <div class="row">
                                <!-- MUESTRA PACIENTES DE INTERNACIONES ACTIVAS (CLAVE: ID INTERNACION / VALOR: PACIENTE CORRESP)-->
                                <div class="col-md-6 mb-3">
                                    <label for="recPac" class="form-label">Paciente <span class="text-danger">*</span></label>
                                    <select class="form-select" id="recPac" name="recPac" required>
                                        <option value='-1' selected="true">Seleccionar un paciente</option>
                                    </select>
                                    <div id="valPacRec" class="invalid-feedback visibility-hidden">
                                        Debe seleccionar un paciente
                                    </div>
                                </div>

                                <!-- MUESTRA TIPOS DE REVISIONES SEGUN ROL-->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="tipoRevisRec">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipoRevisRec" name="tipoRevisRec" required>
                                        <option value='-1' selected="true">Seleccionar tipo de revisión</option>
                                    </select>
                                    <div id="valTipoRevisRec" class="invalid-feedback visibility-hidden">
                                        Debe seleccionar un tipo de revisión
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección Desencadenador -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-alarm me-2"></i>Configuración de Tiempo
                            </h6>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tipo de Recordatorio <span class="text-danger">*</span></label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="op" id="opUnaVez" checked>
                                        <label class="btn btn-outline-secondary" for="opUnaVez">
                                            <i class="bi bi-calendar-check me-1"></i>Una vez
                                        </label>

                                        <input type="radio" class="btn-check" name="op" id="opPorHoras">
                                        <label class="btn btn-outline-secondary" for="opPorHoras">
                                            <i class="bi bi-clock-history me-1"></i>Cada X horas
                                        </label>

                                        <input type="radio" class="btn-check" name="op" id="opDiariamente">
                                        <label class="btn btn-outline-secondary" for="opDiariamente">
                                            <i class="bi bi-calendar-day me-1"></i>Diariamente
                                        </label>

                                        <input type="radio" class="btn-check" name="op" id="opSemanalmente">
                                        <label class="btn btn-outline-secondary" for="opSemanalmente">
                                            <i class="bi bi-calendar-week me-1"></i>Semanalmente
                                        </label>
                                    </div>
                                </div>

                                <!-- Sin frecuencia -->
                                <div class="col-md-6 mb-3">
                                    <label for="fechaRecordatorio" class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fechaRecordatorio" name="fechaRecordatorio" required>
                                    <div id="valfechaRecordatorio" class="invalid-feedback visibility-hidden">
                                        Debe seleccionar una fecha de inicio valida
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="horaRecordatorio" class="form-label">Hora <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="horaRecordatorio" name="horaRecordatorio" value="00:00" required>
                                    <div id="valhoraRecordatorio" class="invalid-feedback visibility-hidden">
                                        Debe seleccionar una hora de inicio valida
                                    </div>
                                </div>

                                <!-- Frecuencia x Horas -->
                                <div id="divFrecHorasRep" class="col-md-12 mb-3 visibility-remove">
                                    <label for="frecHorasRep" class="form-label">Repetir cada (horas)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="frecHorasRep" name="frecHorasRep"
                                            min="1" max="23" value="1" placeholder="1">
                                        <span class="input-group-text">hora(s)</span>
                                    </div>
                                    <small class="text-muted">Ej: 1 = a cada hora, 2 = cada dos horas</small>
                                    <div id="valfrecHorasRep" class="text-danger visibility-hidden">
                                        Debe seleccionar un valor valido para la frecuencia por horas
                                    </div>
                                </div>

                                <!-- Frecuencia Diaria -->
                                <div id="divFrecDiasRep" class="col-md-12 mb-3 visibility-remove">
                                    <label for="frecDiasRep" class="form-label">Repetir cada (días)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="frecDiasRep" name="frecDiasRep"
                                            min="1" max="30" value="1" placeholder="1">
                                        <span class="input-group-text">día(s)</span>
                                    </div>
                                    <small class="text-muted">Ej: 1 = todos los días, 2 = día por medio</small>
                                    <div id="valfrecDiasRep" class="text-danger visibility-hidden">
                                        Debe seleccionar un valor valido para la frecuencia diaria
                                    </div>
                                </div>

                                <!-- Frecuencia Semanal -->
                                <div id="divFrecSemRep" class="col-md-12 mb-3 visibility-remove">
                                    <label for="frecSemRep" class="form-label">Repetir cada (semanas)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="frecSemRep" name="frecSemRep"
                                            min="1" max="12" value="1" placeholder="1">
                                        <span class="input-group-text">semana(s)</span>
                                    </div>
                                    <small class="text-muted">Ej: 1 = todos las semanas, 2 = semana por medio</small>
                                    <div id="valfrecSemRep" class="text-danger visibility-hidden">
                                        Debe seleccionar un valor valido para la frecuencia semanal
                                    </div>
                                </div>

                                <!-- Días de la Semana -->
                                <div id="divDiasCheck" class="col-md-12 mb-3 visibility-remove">
                                    <label class="form-label">Días de la semana</label>
                                    <div class="d-flex flex-wrap gap-2 justify-content-start">
                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="2" id="diaLunes">
                                        <label class="form-check-label" for="diaLunes">Lunes</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="3" id="diaMartes">
                                        <label class="form-check-label" for="diaMartes">Martes</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="4" id="diaMiercoles">
                                        <label class="form-check-label" for="diaMiercoles">Miércoles</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="5" id="diaJueves">
                                        <label class="form-check-label" for="diaJueves">Jueves</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="6" id="diaViernes">
                                        <label class="form-check-label" for="diaViernes">Viernes</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="7" id="diaSabado">
                                        <label class="form-check-label" for="diaSabado">Sábado</label>

                                        <input class="form-check-input" type="checkbox" name="diasSemana" value="1" id="diaDomingo">
                                        <label class="form-check-label" for="diaDomingo">Domingo</label>

                                    </div>
                                    <div id="valDiasCheck" class="text-danger visibility-hidden">
                                        Debe seleccionar al menos un dia de la semana
                                    </div>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch d-flex align-items-center ps-0 py-1">
                                        <input class="form-check-input ms-0 mt-0 me-3" type="checkbox" id="fechaFinActivo"
                                            name="recordatorioActivo">
                                        <label class="form-check-label" for="fechaFinActivo">
                                            <strong>Definir fecha de finalización</strong>
                                        </label>
                                    </div>
                                </div>
                                <div id="divFrecFin" class="col-md-6 mb-3 visibility-remove">
                                    <label for="fechaFinRecordatorio" class="form-label">Fecha de finalización </label>
                                    <input type="date" class="form-control" id="fechaFinRecordatorio" name="fechaFinRecordatorio">
                                    <div id="valfechaFinRecordatorio" class="invalid-feedback visibility-hidden">
                                        Debe seleccionar una fecha de finalizacion valida
                                    </div>
                                </div>

                                <div class="border-bottom pb-2">
                                    <div class="col-md-12 mb-3">
                                        <label for="recObs" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="recObs" name="recObs"
                                            rows="2" placeholder="Observaciones del recordatorio" maxlength="255">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch d-flex align-items-center ps-0 py-1">
                                    <input class="form-check-input ms-0 mt-0 me-3" type="checkbox" id="recordatorioActivo"
                                        name="recordatorioActivo" checked>
                                    <label class="form-check-label" for="recordatorioActivo">
                                        <strong>Recordatorio activo</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancelEmail" id="btnCancelRecordatorioForm" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-submit" id="btnGuardarRecordatorio">
                            Crear
                        </button>
                        <button type="submit" class="btn-submit" id="btnActRecordatorio">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>