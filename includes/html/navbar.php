<?php
    
    $idUser = '';
    $user = '';
    $nombreUser = '';
    $apellidoUser = '';
    $emailUser = '';
    $telUser = '';

    if (isset($_SESSION['usuario'])) {
        
        $idUser = $_SESSION['usuario'];

        require_once($dirBaseFile . '/dataAccess/usuarios.php');

        try {
            $data = Usuarios::getUsuarioById($idUser);

            if (!$data){
                throw new Exception();
            } else {
                foreach($data as $row){
                    $user = $row['Usuario'];
                    $nombreUser = $row['Nombre'];
                    $apellidoUser = $row['Apellido'];
                    $emailUser = $row['Email'];
                    $telUser = $row['Telefono'];
                }
            }
        } catch (Exception) {
            $errors['process'] = "Problemas para ingresar al sistema";

            header('Location: ' . $dirBaseUrl . '/auth/logout.php');
        }
    }else{
        $errors['process'] = "Problemas para ingresar al sistema";

        header('Location: ' . $dirBaseUrl . '/auth/logout.php');
    }

    function userTienePermiso($permiso, $user)
    {
        global $dirBaseFile, $dirBaseUrl;
        require_once($dirBaseFile . '/dataAccess/permisos.php');

        try {
            $dataPermiso = Permisos::tienePermiso($permiso, $user);

            if (!$dataPermiso){
                return false;
            } else {
                return true;
            }
        } catch (Exception) {
            $errors['process'] = "Problemas para verificar los permisos";

            header('Location: ' . $dirBaseUrl . '/auth/logout.php');
        }
    }
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <a class="navbar-brand" href="#">HealthWay</a>
    <div class="collapse navbar-collapse " id="navbarNav">
        <ul class="navbar-nav menu-3 me-auto mb-2 mb-lg-0 ">
            <li class="nav-item">
                <a class="nav-link <?php if ($module == 'dashboards') {echo 'active';} //Visualizar dashboard personal medico?>" 
                    href="<?php echo $dirBaseUrl ?>/dashboards/dashboard_layout.php" 
                    id="gestionDash">Inicio</a>
            </li>
            <?php if (userTienePermiso(2, $idUser)) { //Visualizar internaciones?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'internaciones') {echo 'active';} ?>" 
                        href="<?php echo $dirBaseUrl ?>/internaciones/internaciones.php" 
                        id="gestionInter">Internaciones</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(3, $idUser)) { //Visualizar revisiones?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'revisiones') {echo 'active';} ?>" 
                        href="<?php echo $dirBaseUrl ?>/revisiones/revisiones_layout.php" 
                        id="gestionRevis">Revisiones</a>
                </li>
            <?php } ?>
            <?php if (userTienePermiso(42, $idUser)) { //Visualizar Recordatorios de revisiones ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($module == 'recordatorios') {echo 'active';} ?>" 
                        href="<?php echo $dirBaseUrl ?>/recordatorios/recordatorios_layout.php" 
                        id="gestionHC">Recordatorios</a>
                </li>
            <?php } ?>
        </ul>

        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary mx-2" type="button" id="btnEscanearQR" title="Escanear código QR">
                <i class="bi bi-qr-code-scan"></i>
                <span class="d-none d-md-inline ms-2">Escanear QR</span>
            </button>
            <span class="span-notif"><i class="bi bi-bell-fill mx-1 me-3"></i></span>
            <span class="span-user">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary mx-1 me-3" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill"></i>
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
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
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="<?php echo $dirBaseUrl ?>/auth/logout.php">
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

<!-- Modal QR -->
<?php
    require_once($dirBaseFile . '/includes/html/qr.php');
?>