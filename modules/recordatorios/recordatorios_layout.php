<?php
    require_once(__DIR__ . '/../../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'recordatorios';
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
            <h1 class="mb-4 dashboard-title">Recordatorios de Revisiones</h1>

            <div class="row mb-4 align-items-center">
                <?php if (userTienePermiso(11, $idUser)) { ?>
                    <div id="createRec" class="col-md-2">
                        <button class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#modalRecordatorio">
                            <i class="bi bi-calendar-event me-2"></i>Crear Recordatorio
                        </button>
                    </div>
                <?php } ?>
                <div class="col-md-8">
                    <div class="input-group">
                        <input id="buscadorRecs" type="text" class="form-control" placeholder="Buscar por Paciente, Cama o Habitacion">
                        <button class="btn btn-outline-primary" id="btnBuscarRecs" type="button"><i class="bi bi-search"></i> Buscar</button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header text-white card-header-color">
                        Lista de Recordatorios
                    </div>
                    <div>
                        <div id="divTablaRecs" class="card-body"></div>
                    </div>
                </div>
            </div>

            <!-- Modal Revisión -->
            <?php
                require_once($dirBaseFile . '/recordatorios/recordatorio.php');
            ?>

            <!-- Live Toast -->
            <?php
                require_once($dirBaseFile . '/includes/html/notification.php');
            ?>
    </main>

</body>
</html>