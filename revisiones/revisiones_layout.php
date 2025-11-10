<?php
    require_once(__DIR__ . '/../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'revisiones';
        require_once($dirBaseFile . '/includes/html/head.php');
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
            <h1 class="mb-4 dashboard-title">Gestion de Revisiones</h1>

            <div class="row mb-4 align-items-center">
                <div id="createRev" class="col-md-2 visibility-remove">
                    <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#modalRevision">
                        <i class="bi bi bi-pencil-square me-2"></i>Añadir Nueva Revision
                    </button>
                </div>
                <div id="createRec" class="col-md-2 visibility-remove">
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
                    <div>
                        <div id="tablaRevs" class="card-body"></div>
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
                require_once($dirBaseFile . '/revisiones/recordatorio.php');
            ?>

            <!-- Modal Revisión -->
            <?php
                require_once($dirBaseFile . '/revisiones/revision.php');
            ?>
    </main>
</body>
</html>