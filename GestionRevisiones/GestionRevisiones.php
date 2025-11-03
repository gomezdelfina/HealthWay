<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $page = 'gestionRevisiones';
        require('../includes/head.php');

        echo "<script defer src=\"./scriptGestionRevisiones.js\"></script>";
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
            <h1 class="mb-4 dashboard-title">Gestion de Revisiones</h1>

            <div class="row mb-4 align-items-center">
                <div class="col-md-2">
                    <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#modalRevision">
                        <i class="bi bi bi-pencil-square me-2"></i>Añadir Nueva Revision
                    </button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#modalRecordatorio">
                        <i class="bi bi-calendar-event me-2"></i>Crear Recordatorio
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
                        <i class="bi bi bi-card-list me-2"></i>Lista de Revisiones
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Habitacion</th>
                                    <td>Cama</td>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Diego Cesari</td>
                                    <td>101</td>
                                    <td>1</td>
                                    <td>Revision medicacion</td>
                                    <td>De rutina</td>
                                    <td>27/09/2025</td>
                                    <td>23:00</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalRevision"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Marcos Pérez</td>
                                    <td>101</td>
                                    <td>2</td>
                                    <td>Revision medicacion</td>
                                    <td>De rutina</td>
                                    <td>27/09/2025</td>
                                    <td>23:30</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalRevision"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ana Gómez</td>
                                    <td>103</td>
                                    <td>4</td>
                                    <td>Revision medicacion</td>
                                    <td>De rutina</td>
                                    <td>27/09/2025</td>
                                    <td>14:45</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalRevision"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lucas Hernández</td>
                                    <td>105</td>
                                    <td>6</td>
                                    <td>Revision signos vitales</td>
                                    <td>De rutina</td>
                                    <td>26/09/2025</td>
                                    <td>03:00</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-outline-dark me-2"  data-bs-toggle="modal" data-bs-target="#modalRevision"><i class="bi bi-eye-fill me-2"></i>Ver</button>
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

            <!-- Modal Recordatorio -->
            <?php
                require('./Recordatorio.php');
            ?>

            <!-- Modal Revisión -->
            <?php
                require('./Revision.php');
            ?>

            <!-- Toast Notificaciones -->
            <?php
                require('../includes/notification.php');
            ?>
        </main>
    </div>
</body>
</html>
