<?php
    require_once(__DIR__ . '/../../includes/globals.php');
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
        <?php if (userTienePermiso(1, $idUser)) { //Visualizar dashboard administrador ?> 
            <div id="dashboardAdmin">
                <?php require($dirBaseFile . '/modules/dashboards/dashboardAdmin.php');?>
            </div>    
        <?php } ?>
        <?php if (userTienePermiso(3, $idUser)) { //Visualizar dashboard jefe internaciones?>
            <div id="dashboardJefeInternaciones">
                <?php require($dirBaseFile . '/modules/dashboards/dashboardJefeInternaciones.php');?>
            </div>
        <?php } ?>
        <?php if (userTienePermiso(2, $idUser)) { //Visualizar dashboard paciente?>
            <div id="dashboardPaciente">
                <?php require($dirBaseFile . '/modules/dashboards/dashboardPaciente.php');?>
            </div>
        <?php } ?>
        <?php if (userTienePermiso(4, $idUser)) { //Visualizar dashboard personal medico?>
            <div id="dashboardPersMedico">
                <?php require($dirBaseFile . '/modules/dashboards/dashboardPersMedico.php');?>
            </div>    
        <?php } ?>   
    </main>
</body>
</html>