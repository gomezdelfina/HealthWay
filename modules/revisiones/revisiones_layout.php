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
                <?php if (userTienePermiso(8, $idUser)) { ?>
                    <div id="createRev" class="col-md-2">
                        <button id="btnCrearRev" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#modalRevision">
                            <i class="bi bi bi-card-list me-2"></i>Crear Revision
                        </button>
                    </div>
                <?php } ?>
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
                        Lista de Revisiones
                    </div>
                    <div>
                        <div id="divTablaRevs" class="card-body"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Revisión -->
            <?php
                require_once($dirBaseFile . '/revisiones/revision.php');
            ?>

            <!-- Live Toast -->
            <?php
                require_once($dirBaseFile . '/includes/html/notification.php');
            ?>
    </main>

</body>
</html>