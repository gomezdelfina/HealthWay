<?php
require_once(__DIR__ . '/../../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'solicitud_internacion'; // Nuevo módulo para cargar su script JS
        require_once($dirBaseFile . '/includes/html/head.php');
        
        // INYECCION DE RUTA BASE DE API PARA JAVASCRIPT
        $apiBaseUrl = $dirBaseUrl . '/api/solicitudes'; // Usamos un nuevo endpoint para solicitudes
        echo '<script>window.API_BASE_URL = "' . $apiBaseUrl . '";</script>';
        
        echo '<script src="' . $dirBaseUrl . '/script/script_solicitud_internacion.js" defer></script>';
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
            <h1 class="mb-4 dashboard-title">Gestion de Solicitudes de Internación</h1>

            <div class="row mb-4 align-items-center">
                <?php if (userTienePermiso(8, $idUser)) { // Permiso 8: Crear Solicitud ?>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#solicitudModal">
                            <i class="bi bi-file-earmark-plus me-2"></i>Crear Nueva Solicitud
                        </button>
                    </div>
                <?php } ?>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar por Paciente, Médico o Estado...">
                        <button class="btn btn-outline-secondary" type="button" onclick="loadSolicitudes(document.getElementById('searchInput').value)">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Solicitudes -->
            <div class="table-responsive bg-white p-3 rounded shadow-sm">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Médico Solicitante</th>
                            <th>Fecha Solicitud</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="solicitudesTableBody">
                        <!-- Las filas se cargarán con JavaScript -->
                        <tr>
                            <td colspan="7" class="text-center">Cargando solicitudes...</td>
                        </tr>
                    </tbody>
                </table>
                <p id="paginationInfo" class="text-muted text-center"></p>
            </div>


            <!-- Modal para Crear Solicitud -->
            <div class="modal fade" id="solicitudModal" tabindex="-1" aria-labelledby="solicitudModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalTitle">Crear Solicitud de Internación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="solicitudForm">
                                
                                <div class="row mb-3">
                                    <label class="form-label" for="pacienteId">Paciente</label>
                                    <select class="form-select" id="pacienteId" name="pacienteId" required>
                                        <option value="" disabled selected>Seleccione un paciente</option>
                                        <!-- Opciones cargadas por JS -->
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="row mb-3">
                                    <label class="form-label" for="medicoId">Médico Solicitante</label>
                                    <select class="form-select" id="medicoId" name="medicoId" required>
                                        <option value="" disabled selected>Seleccione un médico</option>
                                        <!-- Opciones cargadas por JS (Debe ser el usuario logeado si es médico, o una lista de médicos si es admin) -->
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label" for="motivo">Motivo de Internación</label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label" for="diagnostico">Diagnóstico Presuntivo</label>
                                    <textarea class="form-control" id="diagnostico" name="diagnostico" rows="2"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label" for="prioridad">Prioridad</label>
                                    <select class="form-select" id="prioridad" name="prioridad" required>
                                        <option value="Baja">Baja</option>
                                        <option value="Media" selected>Media</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Crítica">Crítica</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn"> Guardar Solicitud </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Cambiar Estado/Asignar Cama (se crea el modal aquí para su uso futuro) -->
            <div class="modal fade" id="estadoModal" tabindex="-1" aria-labelledby="estadoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title" id="estadoModalTitle">Gestionar Solicitud</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="estadoForm">
                                <input type="hidden" id="solicitudId" name="solicitudId">
                                
                                <div class="mb-3">
                                    <label for="nuevoEstado" class="form-label">Cambiar Estado</label>
                                    <select class="form-select" id="nuevoEstado" name="nuevoEstado" required>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="En espera de internación">En espera de internación</option>
                                        <option value="Cancelada">Cancelada</option>
                                    </select>
                                </div>
                                
                                <div class="alert alert-info" role="alert">
                                    El estado **"En espera de internación"** asigna el estado correspondiente al paciente (si el caso de uso lo requiere) y luego un administrador puede proceder a realizar la internación en el módulo principal.
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-warning" id="saveEstadoBtn"> Actualizar Estado </button>
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