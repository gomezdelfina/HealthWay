<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $page = 'gestionHistoriasClinicas';
        require('../includes/head.php');
        // javascript
        echo "<script defer src=\"./scriptHistoriasClinicas.js\"></script>";
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
                <h1 class="mb-4 dashboard-title">Gestion de Historias Clinicas</h1>

                <div class="row mb-4 align-items-center">
                    <div class="col-md-4">
                        <button class="btn btn-success btn-lg">
                            <i class="bi bi bi-qr-code-scan me-2"></i>Escanear historia Clinica
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar por Paciente, Internacion">
                            <button class="btn btn-outline-primary" type="button"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header text-white card-header-color">
                            <i class="bi bi bi-card-list me-2"></i>Lista de Historias clinicas
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>N. Internacion</th>
                                        <th>Paciente</th>
                                        <th>Estado</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Diego Cesari</td>
                                        <th>Internado</th>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-download me-2"></i>Descargar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Marcos Pérez</td>
                                        <th>Internado</th>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-download me-2"></i>Descargar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Ana Gómez</td>
                                        <th>Internado</th>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-download me-2"></i>Descargar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Lucia Mendez</td>
                                        <th>De alta</th>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-eye-fill me-2"></i>Ver</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-sm btn-outline-dark me-2"><i class="bi bi-download me-2"></i>Descargar</button>
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
                    
                </div>
            </div>
        </main>
    </div>
</body>
</html>
