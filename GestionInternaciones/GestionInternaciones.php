<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $page = 'gestionInternaciones';
        require('../includes/head.php');
        // javascript
        echo "<script defer src=\"./scriptInternaciones.js\"></script>";
    ?>
</head>
<body>
    <div>
        <header class="container-fluid">
            <?php
                require('../includes/navbar.php');
            ?>
        </header>

        <main class="container-fluid">
            <div class="background mt-3 row">
                <h1 class="mb-4 dashboard-title">Gestion de Internaciones</h1>

                <div class="row mb-4 align-items-center">
                    <div class="col-md-4">
                        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Añadir Nueva Internacion
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar por Paciente, Cama o Habitacion">
                            <button class="btn btn-outline-primary" type="button"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header text-white card-header-color">
                            <i class="bi bi bi-card-list me-2"></i>Lista de Internaciones
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>N. Internacion</th>
                                        <th>Paciente</th>
                                        <th>Habitacion</th>
                                        <th>Cama</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Diego Cesari</td>
                                        <td>101</td>
                                        <td>1</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalInternacion"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Marcos Pérez</td>
                                        <td>101</td>
                                        <td>2</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalInternacion"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Ana Gómez</td>
                                        <td>103</td>
                                        <td>4</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalInternacion"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Lucas Hernández</td>
                                        <td>105</td>
                                        <td>6</td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalInternacion"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                                </ul>
                            </nav>
                        </div>
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
                                            <label class="form-label" for="nInter">N. Internacion</label>
                                            <input class="form-control" type="number" id="inputNumber" name="nInter" disabled>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="nombrePac">Nombre</label>
                                            <input class="form-control" type="text" id="nombrePac" name="nombrePac" disabled>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="apelPac">Apellido</label>
                                            <input class="form-control" type="text" id="apelPac" name="apelPac" disabled>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="camaInter">Cama</label>
                                            <input class="form-control" type="number" id="camaInter" name="camaInter" value="" disabled>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="habInter">Habitacion</label>
                                            <input class="form-control" type="number" id="habInter" name="habInter" disabled>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label" for="fechaInter">Fecha de Internacion</label>
                                            <input class="form-control" type="date" id="fechaInter" name="fechaInter" disabled>
                                        </div>
                                        <div class="col">
                                            <label class="form-label" for="notasInter">Notas</label>
                                            <input class="form-control" type="text" id="notasInter" name="notasInter" disabled>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn-cancelEmail" form="pswRecoveryForm" id="btnPswRecovery" data-bs-dismiss="modal">Cancelar</button>
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
                                <form id="registerform">
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label"> Motivo de la Internacion </label>
                                        <input type="text" class="form-control" id="descripcion" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paciente" class="form-label"> Paciente a Internar </label>
                                        <input type="text" class="form-control" id="paciente" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado" class="form-label"> Estado de Internacion </label>
                                        <select class="form-select" id="estado" required>
                                            <option selected> Seleccione un Estado </option>
                                            <option> Urgente </option>
                                            <option> Programada </option>
                                            <option> Reprogramada </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cama" class="form-label"> Cama para Internacion </label>
                                        <input type="number" class="form-control" id="cama" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary"> Completar Internacion </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
