<?php

require_once($dirBaseFile . '/dataAccess/usuarios.php');

$idUser = '';
$user = '';
$nombreUser = '';
$apellidoUser = '';
$emailUser = '';
$telUser = '';

if (isset($_SESSION['usuario'])) {
    $idUser = $_SESSION['usuario'];

    try {
        $data = Usuarios::getUsuarioById($idUser);

        if (!$data) {
            throw new Exception("No se encontro el usuario en el sistema");
        } else {
            foreach ($data as $row) {
                $user = $row['Usuario'];
                $nombreUser = $row['Nombre'];
                $apellidoUser = $row['Apellido'];
                $emailUser = $row['Email'];
                $telUser = $row['Telefono'];
            }
        }
    } catch (Exception $e) {
        print("Problemas para ingresar al sistema: " + $e);

        header('Location: ' . $dirBaseUrl . '/modules/auth/logout.php');
    }
} else {
    print("Problemas para ingresar al sistema: " + $e);

    header('Location: ' . $dirBaseUrl . '/modules/auth/logout.php');
}

function userTienePermiso($permiso, $user)
{
    global $dirBaseFile, $dirBaseUrl;
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    try {
        $dataPermiso = Permisos::tienePermiso($permiso, $user);

        if (!$dataPermiso) {
            return false;
        } else {
            return true;
        }
    } catch (Exception) {
        $errors['process'] = "Problemas para verificar los permisos";

        header('Location: ' . $dirBaseUrl . '/modules/auth/logout.php');
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <a class="navbar-brand" href="#">HealthWay</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarNav">
        <ul class="navbar-nav menu-3 me-auto mb-2 mb-lg-0 ">
            <li class="nav-item">
                <a class="nav-link <?php if ($module == 'dashboards') {
                                        echo 'active';
                                    } ?>"
                    href="<?php echo $dirBaseUrl ?>/modules/dashboards/dashboard_layout.php"
                    id="gestionDash">Inicio</a>
            </li>
            <?php if (userTienePermiso(6, $idUser)) { //Visualizar internaciones
            ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'internaciones') {
                                            echo 'active';
                                        } ?>"
                        href="<?php echo $dirBaseUrl ?>/modules/internaciones/internaciones.php"
                        id="gestionInter">Internaciones</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(1, $idUser)) { //Visualizar dashboard administrador
            ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'administrador') {
                                            echo 'active';
                                        } ?>"
                        href="<?php echo $dirBaseUrl ?>/modules/administrador/gestionadminusuario.php"
                        id="gestionUsu">Gestion Usuarios</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(45, $idUser)) { //Visualizar dashboard gestion de pacientes
            ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'administrador') {
                                            echo 'active';
                                        } ?>"
                        href="<?php echo $dirBaseUrl ?>/modules/administrador/pacientes_web_app.php"
                        id="gestionUsu">Gestion Usuarios</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(9, $idUser)) { //Visualizar revisiones
            ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'revisiones') {
                                            echo 'active';
                                        } ?>"
                        href="<?php echo $dirBaseUrl ?>/modules/revisiones/revisiones_layout.php"
                        id="gestionRevis">Revisiones</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(12, $idUser)) { //Visualizar Recordatorios de revisiones 
            ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'recordatorios') {
                                            echo 'active';
                                        } ?>"
                        href="<?php echo $dirBaseUrl ?>/modules/recordatorios/recordatorios_layout.php"
                        id="gestionHC">Recordatorios</a>
                </li>
            <?php } ?>
        </ul>

        <hr class="d-lg-none my-3 text-muted">

        <div class="d-flex flex-row align-items-center gap-3">
            <?php if (userTienePermiso(5, $idUser)) { //Escanear QR 
            ?>
                <button class="btn btn-outline-secondary mx-2 order-3 order-lg-1" type="button" id="btnEscanearQR">
                    <i class="bi bi-qr-code-scan"></i>
                    <span class="d-none d-md-inline ms-2">Escanear QR</span>
                </button>
            <?php } ?>
            <?php if (userTienePermiso(47, $idUser)) { ?>
                <div class="d-flex align-items-center order-2 order-lg-2">
                    <button type="button" id="btnNotificaciones" class="btn btn-light position-relative" title="notificacion">
                        <!-- Contador de notificaciones -->
                        <span id="notifCount"
                            class="badge rounded-pill bg-danger me-2">
                            0
                        </span>
                        <i class="bi bi-bell"></i>
                    </button>
                </div>
            <?php } ?>
            <span class="span-user order-1 order-lg-3">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary mx-1 me-3" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill"></i>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end shadow">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-large me-3">
                                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo $user ?></h6>
                                </div>
                            </div>
                        </li>
                        <?php if (userTienePermiso(44, $idUser)) { //Visualizar informacion personal 
                        ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <div class="user-info-section mx-3 text-nowrap">
                                    <div class="text-start mb-2">
                                        <strong>Información del Usuario</strong>
                                        <hr class="dropdown-divider">
                                    </div>
                                    <div class="d-flex mb-1 align-items-start info-row">
                                        <p class="text-muted mb-0 me-2 info-label">Nombre:</p>
                                        <strong class="info-value"><?php echo $nombreUser ?></strong>
                                    </div>
                                    <div class="d-flex mb-1 align-items-start info-row">
                                        <p class="text-muted mb-0 me-2 info-label">Apellido:</p>
                                        <strong class="info-value"><?php echo $apellidoUser ?></strong>
                                    </div>
                                    <div class="d-flex mb-1 align-items-start info-row">
                                        <p class="text-muted mb-0 me-2 info-label">Email:</p>
                                        <strong class="info-value"><?php echo $emailUser ?></strong>
                                    </div>
                                    <div class="d-flex mb-1 align-items-start info-row">
                                        <p class="text-muted mb-0 me-2 info-label">Telefono:</p>
                                        <strong class="info-value"><?php echo $telUser ?></strong>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="<?php echo $dirBaseUrl ?>/modules/auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </span>
        </div>
    </div>
</nav>

<!-- Modal notificaciones -->
<div class="modal fade" id="modalNotificaciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notificaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="listaNotificaciones">
                Cargando notificaciones...
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR -->
<?php
require_once($dirBaseFile . '/includes/html/qr.php');
?>

<!-- Live Toast -->
<?php
require_once($dirBaseFile . '/includes/html/notification.php');
?>