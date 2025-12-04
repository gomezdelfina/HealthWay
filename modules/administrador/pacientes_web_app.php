<?php
    require_once(__DIR__ . '/../../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'administrador';
        require_once($dirBaseFile . '/includes/html/head.php');
        echo '<script src="' . $dirBaseUrl . '/script/pacientes.js" defer></script>';
    ?>
</head>
<body class="bg-light">
    <header>
        <?php
            require($dirBaseFile . '/includes/html/navbar.php');
        ?>
    </header>

    <!-- Contenedor principal -->
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Gestión de Pacientes</h2>
            <button class="btn btn-primary" onclick="openModal('crear')">
                <i class="bi bi-plus-circle"></i> Nuevo Paciente
            </button>
        </div>

        <!-- Buscador -->
        <div class="col-md-8">
                <div class="input-group">
                    <input type="text" id="buscarInput" class="form-control" placeholder="Buscar por Paciente, Cama o Habitación" title="buscar">
                    <button class="btn btn-outline-primary" id="btnBuscar" type="button">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>

        <!-- Mensajes Bootstrap Alerts -->
        <div id="messageContainer" class="mb-3"></div>

        <!-- Tabla -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>DNI</th>
                                <th>Fecha Nac</th>
                                <th>Obra Social</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="pacientesTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- ==========================
        MODAL BOOTSTRAP
    =========================== -->
    <div class="modal fade" id="pacienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">

                <div class="modal-header">
                    <h5 id="modalTitle" class="modal-title fw-bold"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form id="pacienteForm">

                        <input type="hidden" id="pacienteId">
                        <input type="hidden" id="formType">

                        <div class="row g-3">

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input id="nombre" type="text" class="form-control" required>
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input id="apellido" type="text" class="form-control" required>
                            </div>

                            <!-- DNI -->
                            <div class="col-md-6">
                                <label class="form-label">DNI</label>
                                <input id="dni" type="number" class="form-control" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input id="email" type="email" class="form-control" required>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input id="telefono" type="number" class="form-control" required>
                            </div>

                            <!-- FechaNacimiento -->
                            <div class="col-md-6">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input id="fechaNac" type="date" class="form-control" required>
                            </div>

                            <!-- Género -->
                            <div class="col-md-6">
                                <label class="form-label">Género</label>
                                <select id="genero" class="form-select" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <!-- Estado civil -->
                            <div class="col-md-6">
                                <label class="form-label">Estado Civil</label>
                                <select id="estadoCivil" class="form-select" required>
                                    <option>Soltero/a</option>
                                    <option>Casado/a</option>
                                    <option>Divorciado/a</option>
                                    <option>Viudo/a</option>
                                </select>
                            </div>

                            <!-- Obra Social -->
                            <div class="col-md-6">
                                <label class="form-label">Obra Social</label>
                                <select id="nombreOS" class="form-select" required></select>
                            </div>

                            <!-- Habilitado (solo en editar) -->
                            <div class="col-md-6 d-none" id="habilitadoGroup">
                                <label class="form-label">Estado</label>
                                <select id="habilitado" class="form-select">
                                    <option value="1">Habilitado</option>
                                    <option value="0">Inhabilitado</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="submitButton" class="btn btn-primary">Guardar</button>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
