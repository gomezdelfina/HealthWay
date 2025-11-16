<?php
    require_once(__DIR__ . '/../includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'dashboards';
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
        <div id="dashboardAdmin" class="visibility-remove">
            <?php require($dirBaseFile . '/dashboards/dashboardAdmin.php');?>
        </div>    
        <div id="dashboardJefeInternaciones" class="visibility-remove">
            <?php require($dirBaseFile . '/dashboards/dashboardJefeInternaciones.php');?>
        </div>
        <div id="dashboardPaciente" class="visibility-remove">
            <?php require($dirBaseFile . '/dashboards/dashboardPaciente.php');?>
        </div>
        <div id="dashboardPersMedico" class="visibility-remove">
            <?php require($dirBaseFile . '/dashboards/dashboardPersMedico.php');?>
        </div>        
    </main>
</body>
</html>