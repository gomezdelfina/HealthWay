<div class="modal fade" id="modalRecordatorio" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h4-modal-header" id="modalLabel">Recordatorio</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body div-modal-body">
                <form id="recordatorioForm">
                    <div class="row">
                        <!-- MUESTRA NOMBRE Y ROL DEL USUARIO LOGEADO -->
                        <div class="col-4 mt-1">
                            <label class="form-label" for="nombreSolRec">Nombre solicitante</label>
                            <input class="form-control" type="text" id="nombreSolRec" name="nombreSolRec" value="<?php echo ($userActiveName); ?>" disabled>
                        </div>
                        <div class="col-4 mt-1">
                            <label class="form-label" for="rolSolRec">Rol solicitante</label>
                            <input class="form-control" type="text" id="rolSolRec" name="rolSolRec" value="<?php echo ($userActiveRol); ?>" disabled>
                        </div>
                        <div class="col-4 mt-1">
                            <!-- MUESTRA PACIENTES DE INTERNACIONES ACTIVAS (CLAVE: ID INTERNACION / VALOR: PACIENTE CORRESP)-->
                            <label class="form-label" for="interPacRec">Paciente</label>
                            <select class="form-select" id="interPacRec" name="interPacRec">
                                <option value='-1' selected="true">Seleccionar un paciente</option>
                                <option value='1'>Diego Cesari</option>
                                <option value='2'>Marcos Perez</option>
                                <option value='3'>Ana Gómez</option>
                                <option value='0'>Lucas Hernandez</option>
                            </select>
                            <div id="valPacRec" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un paciente
                            </div>
                        </div>
                        <div class="col-6 mt-1">
                            <!-- MUESTRA TIPOS DE REVISIONES -->
                            <label class="form-label" for="tipoRecRev">Tipo</label>
                            <select class="form-select" id="tipoRecRev" name="tipoRecRev">
                                <option value='-1' selected="true">Seleccionar tipo de revisión</option>
                                <?php if ($userActiveRolId == 1) { ?>
                                    <option value='1'>Signos vitales</option>
                                    <option value='2'>Alimentacion</option>
                                    <option value='3'>Higienizacion</option>
                                    <option value='4'>Medicacion</option>
                                <?php } elseif ($userActiveRolId == 2) { ?>
                                    <option value='1'>Signos vitales</option>
                                    <option value='4'>Medicacion</option>
                                    <option value='5'>Intervención</option>
                                    <option value='6'>Intervención quirurgica</option>
                                <?php } elseif ($userActiveRolId == 3) { ?>
                                    <option value='5'>Intervención</option>
                                    <option value='6'>Intervención quirurgica</option>
                                <?php } ?>
                                <option value='7'>Otro</option>
                            </select>
                            <div id="valTipoRec" class="invalid-feedback visibility-hidden">
                                Debe seleccionar un tipo de revisión
                            </div>
                        </div>
                        <div class="col-6 mt-1"></div>
                        <div class="col-3 mt-1">
                            <label class="form-label" for="fechaRecor">Fecha inicio</label>
                            <input class="form-control" type="date" id="fechaRecor" name="fechaRecor">
                            <div id="valFechaRec" class="invalid-feedback visibility-hidden">
                                Debe definir un día para el inicio del recordatorio
                            </div>
                        </div>
                        <div class="col-3 mt-1">
                            <label class="form-label" for="horaRecor">Hora inicio</label>
                            <input class="form-control" type="time" id="horaRecor" name="horaRecor">
                            <div id="valHoraRec" class="invalid-feedback visibility-hidden">
                                Debe definir un horario para el inicio del recordatorio
                            </div>
                        </div>
                        <div class="col-3 mt-1">
                            <label class="form-label" for="fechaFinRecor">Fecha fin</label>
                            <input class="form-control" type="date" id="fechaFinRecor" name="fechaFinRecor">
                            <div id="valFechaRecFin" class="invalid-feedback visibility-hidden">
                                Debe definir un día para el fin del recordatorio
                            </div>
                        </div>
                        <div class="col-3 mt-1">
                            <label class="form-label" for="horaFinRecor">Hora fin</label>
                            <input class="form-control" type="time" id="horaFinRecor" name="horaFinRecor">
                            <div id="valHoraRecFin" class="invalid-feedback visibility-hidden">
                                Debe definir un horario para el fin del recordatorio
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <p>Repetir</p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="op" id="opUnaVez" checked>
                                <label class="form-check-label" for="opUnaVez">
                                    Una vez
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="op" id="opDiariamente">
                                <label class="form-check-label" for="opDiariamente">
                                    Diariamente
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="op" id="opSemanalmente">
                                <label class="form-check-label" for="opSemanalmente">
                                    Semanalmente
                                </label>
                            </div>
                        </div>
                        <div class="col-4 mt-3" id="divFrecHoraRep">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Repetir cada</span>
                                <input class="form-control" type="number" id="frecHoraRep" name="frecHoraRep" min="0" value="0">
                                <span class="input-group-text">horas</span>
                            </div>
                        </div>
                        <div class="col-4 mt-3" id="divFrecDiasRep">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Repetir cada</span>
                                <input class="form-control" type="number" id="frecDiasRep" name="frecDiasRep" min="0" value="0">
                                <span class="input-group-text">días</span>
                            </div>
                        </div>
                        <div class="col-4 mt-3" id="divFrecSemRep">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Repetir cada</span>
                                <input class="form-control" type="number" id="frecSemRep" name="frecSemRep" min="0" value="0">
                                <span class="input-group-text">semanas</span>
                            </div>
                        </div>
                        <div class="col-12 mt-3" id="divDiasCheck">
                            <p>Días</p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lunesCheck" value="lunesCheck">
                                <label class="form-check-label" for="lunesCheck">Lunes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="martesCheck" value="martesCheck">
                                <label class="form-check-label" for="martesCheck">Martes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="miercolesCheck" value="miercolesCheck">
                                <label class="form-check-label" for="miercolesCheck">Miercoles</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="juevesCheck" value="juevesCheck">
                                <label class="form-check-label" for="juevesCheck">Jueves</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="viernesCheck" value="viernesCheck">
                                <label class="form-check-label" for="viernesCheck">Viernes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="sabadoCheck" value="sabadoCheck">
                                <label class="form-check-label" for="sabadoCheck">Sabado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="domingoCheck" value="domingoCheck">
                                <label class="form-check-label" for="domingoCheck">Domingo</label>
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <label class="form-label" for="notasRecord">Notas</label>
                            <textarea class="form-control" id="notasRecor" name="notasRecord"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <p>Pendiente</p>
                <button type="button" class="btn-cancelEmail" form="RevisionForm" id="btnCancelRecordatorioForm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-submit" form="RevisionForm" id="btnCreateRev" data-bs-toggle="modal" data-bs-target="#resultModal">Crear</button>
            </div>
        </div>
    </div>
</div>