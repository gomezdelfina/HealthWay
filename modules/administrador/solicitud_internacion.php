<?php
require_once(__DIR__ . '/../../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'internaciones';
        require_once($dirBaseFile . '/includes/html/head.php');
        echo '<script src="' . $dirBaseUrl . '/script/script_camas.js" defer></script>';
        echo '<script src="' . $dirBaseUrl . '/script/script_internaciones.js" defer></script>'; 
    ?>
</head>
<body>
    <header class="container-fluid">
        <?php
        require($dirBaseFile . '/includes/html/navbar.php');
        ?>
    </header>
    <main class="container-fluid">
        <div class="background mt-3 row">
            <h1 class="mb-4 dashboard-title">Gestión de Internaciones y Solicitudes</h1>

            <div class="row mb-4 align-items-center">
                <?php if (userTienePermiso(7, $idUser)) {  ?>
                    <div class="col-md-6">
                        <!-- Nuevo Botón para Crear Solicitud de Internacion -->
                        <button type="button" class="btn btn-info btn-lg me-3" data-bs-toggle="modal" data-bs-target="#addSolicitudModal" id="btnCrearSolicitud">
                            <i class="bi bi-file-earmark-plus me-2"></i>Crear Solicitud de Internación
                        </button>
                        
                       
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addInternacionModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Añadir Nueva Internacion
                        </button>
                    </div>
                <?php } ?>
                <div class="col-md-6 text-end">
                    <input type="text" class="form-control" placeholder="Buscar Cama/Paciente..." id="buscarInput">
                </div>
            </div>

            
            <div id="contenedorCamas" class="row">
               
            </div>
            
            <div id="resultado" class="mt-3"></div>

        
          
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Completar Nueva Internacion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           
                            <form id="registerform">
                                
                                <div class="row mb-3">
                                    <label class="form-label" for="paciente">Paciente</label>
                                    <select class="form-select" id="paciente" name="paciente">
                                       
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label" for="habitacionPac">Tipo de Habitacion</label>
                                    <select class="form-select" id="habitacionPac" name="habitacionPac">
                                        <option value="" disabled selected>Tipo de Habitacion</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Compartida">Compartida</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                
                                <div class="row mb-3" id="DivInd" style="display:none;">
                                    <label class="form-label" for="camaIndPac">Cama Individual</label>
                                    <select class="form-select" id="camaIndPac" name="camaIndPac">
                                        
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3" id="DivComp" style="display:none;">
                                    <label class="form-label" for="camaComPac">Habitacion Compartida</label>
                                    <select class="form-select" id="camaComPac" name="camaComPac">
                                       
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="row mb-3" id="DivCama" style="display:none;">
                                    <label class="form-label" for="camaFinal">Cama Compartida</label>
                                    <select class="form-select" id="camaFinal" name="camaFinal">
                                       
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <label class="form-label" for="fechaInicio">Fecha de Inicio</label>
                                    <input type="datetime-local" class="form-control" id="fechaInicio" name="fechaInicio">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label" for="fechaFin">Fecha de Fin</label>
                                    <input type="datetime-local" class="form-control" id="fechaFin" name="fechaFin">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"> Completar Internacion </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
           
            <div class="modal fade" id="addSolicitudModal" tabindex="-1" aria-labelledby="addSolicitudModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="addSolicitudModalLabel">Crear Solicitud de Internación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="solicitudForm">
                                <input type="hidden" id="idUsuarioSolicitante" name="idUsuarioSolicitante" value="<?php echo $idUser; ?>">

                                <div class="row mb-3">
                                    <label class="form-label fw-bold" for="pacienteSolicitud">Paciente (Solo disponibles)</label>
                                    <select class="form-select" id="pacienteSolicitud" name="IdPaciente" required>
                                        <option value="" disabled selected>Seleccione un paciente...</option>
                                   
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label fw-bold" for="tipoHabitacionSolicitud">Tipo de Habitación Deseada</label>
                                    <select class="form-select" id="tipoHabitacionSolicitud" name="TipoHabitacion" required>
                                        <option value="" disabled selected>Seleccione un tipo...</option>
                                        <option value="Individual">Individual</option>
                                        <option value="Compartida">Compartida</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="modal-footer mt-4">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-info" id="btnGuardarSolicitud">
                                        <i class="bi bi-save me-2"></i>Guardar Solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>