<?php
require_once(__DIR__ . '/../../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'internaciones';
        require_once($dirBaseFile . '/includes/html/head.php');
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
            <h1 class="mb-4 dashboard-title">Gestion de Internaciones</h1>

            <div class="row mb-4 align-items-center">
                <?php if (userTienePermiso(7, $idUser)) { //Crear internaciones?>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Añadir Nueva Internacion
                        </button>
                    </div>
                <?php } ?>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" id="buscarInput" class="form-control" placeholder="Buscar por Paciente, Cama o Habitación" title="buscar">
                        <button class="btn btn-outline-primary" id="btnBuscar" type="button">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header text-white card-header-color">
                        <i class="bi bi bi-card-list bi-hospital me-2"></i>Lista de Internaciones Activas
                    </div>
                    <div class="card-body">

                        <div id="tablaInternaciones">

                            <div class="container mt-4">

                                <div class="row" id="contenedorCamas">

                                    <!-- Acá se insertarán automáticamente las 150 camas -->

                                </div>

                            </div>

                        </div>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end"></ul>
                    </nav>
                </div>
            </div>

            <div class="modal fade" id="modalInternacion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title h4-modal-header" id="modalLabel">Internacion</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body div-modal-body">
                            <form id="InternacionForm">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label" for="interNumber">N. Internacion</label>
                                        <input class="form-control" id="interNumber" name="nInter" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="nombrePac">Nombre</label>
                                        <input class="form-control" id="nombrePac" name="nombrePac" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="camaInter">Cama</label>
                                        <input class="form-control" id="camaInter" name="camaInter" value="" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="habInter">Habitacion</label>
                                        <input class="form-control" id="habInter" name="habInter" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="estadoInter">Estado</label>
                                        <input class="form-control" id="estadoInter" name="estadobInter" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="fechaInter">Fecha de Internacion</label>
                                        <input class="form-control" id="fechaInter" name="fechaInter" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="fechaFinInter">Fecha Final de Internacion</label>
                                        <input class="form-control" id="fechaFinInter" name="fechaFinInter" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="notasInter">Notas</label>
                                        <input class="form-control" id="notasInter" name="notasInter" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" form="pswRecoveryForm" id="btnPswRecovery" data-bs-dismiss="modal"> Cancelar </button>
                            <?php if (userTienePermiso(48, $idUser)) { //Crear internaciones?>
                                <a id="historia" class="btn btn-info" href="#"> Historial Clinico </a>
                            <?php } ?>
                            <?php if (userTienePermiso(8, $idUser)) { //Crear internaciones?>
                                <button type="button" class="btn btn-primary" id="modificar"> Modificar </button>
                            <?php } ?>
                            <?php if (userTienePermiso(46, $idUser)) { //Crear internaciones?>
                                <button type="button" class="btn btn-success" id="finalizar" data-bs-dismiss="modal">Finalizar</button>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel"> Crear Nueva Internacion </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            <form id="registerform" action="../api/internaciones/RegistrarInternaciones.php" method="post">

                                <div class="row mb-3">

                                    <label for="paciente" class="form-label">Seleccionar Paciente</label>
                                    <select class="form-select" id="paciente" name="paciente">
                                        <option value=""> Seleccionar un Paciente </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>

                                <div class="row mb-3">

                                    <label for="solicitud" class="form-label"> Solicitud de Internacion </label>
                                    <select class="form-select" id="solicitud" name="solicitud">
                                        <option value=""> Seleccionar una Solicitud </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>

                                <div class="row mb-3">

                                    <label for="estado" class="form-label"> Estado de Internacion </label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option selected> Seleccione un Estado </option>
                                        <option value="Activa"> Activa </option>
                                        <option value="Trasladada"> Trasladada </option>
                                        <option value="Reprogramada"> Reprogramada </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>


                                <div class="row mb-3">

                                    <label class="form-label" for="habitacionPac">Habitacion</label>
                                    <select class="form-select" id="habitacionPac" name="habitacionPac">
                                        <option value=""> Seleccione Un Tipo de Habitacion </option>
                                        <option value="Compartida"> Compartida </option>
                                        <option value="Individual"> Individual </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>

                                <div id="DivComp" class="row mb-3">

                                    <label class="form-label" for="camaComPac"> Tipo de Habitacion</label>
                                    <select class="form-select" id="camaComPac" name="camaComPac">
                                        <option value=""> Seleccione Primero Un Tipo de Habitacion </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>

                                <div id="DivInd" class="row mb-3">

                                    <label class="form-label" for="camaIndPac">Habitacion</label>
                                    <select class="form-select" id="camaIndPac" name="camaIndPac">
                                        <option value=""> Seleccione Primero Un Tipo de Habitacion </option>
                                    </select>

                                    <div class="invalid-feedback"></div>

                                </div>

                                <div id="DivCama" class="row mb-3">

                                    <label class="form-label" for="camaPac">Cama</label>
                                    <select class="form-select" id="camaPac" name="camaPac">
                                        <option value=""> Seleccione Primero Una Habitacion </option>
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
        </div>
    </main>
    <?php
        echo '<script src="' . $dirBaseUrl . '/script/script_camas.js"></script>';
    ?>
</body>
</html>