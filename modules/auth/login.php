<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    if (isset($_SESSION['usuario']) && Permisos::tienePermiso(43,$_SESSION['usuario'])) {
        header('Location: ' . $dirBaseUrl . '/modules/dashboards/dashboard_layout.php');
    }
?>
<div class="img-background background">
    <div class="login-container col-sm-8 col-lg-5">
        <div class="hospital-logo">
            <img src="<?php echo $dirBaseUrl ?>/imgs/logoSolo.png" alt="logo">
        </div>
        <div class="w-100">
            <h3 class="login-title">Iniciar Sesion</h3>
            <p class="login-subtitle">Accede a HealthWay</p>
            <form id="loginForm" method="POST" novalidate>
                <div class="input-group mb-2">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control" name="inputUser" id="inputUser" placeholder="Usuario" pattern="[a-zA-Z0-9]" required>
                    <div id="user-error" class="invalid-feedback"></div>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" name="inputPass" id="inputPass" placeholder="Contraseña" pattern="[a-zA-Z0-9]" required>
                    <button id="btnShowPsw" class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                    <div id="clave-error" class="invalid-feedback"></div>
                </div>
                <div id="validUser"></div>
                <div class="d-flex justify-content-end my-3">
                    <button type="button" class="btn-forgotPsw" id="forgotPswBtn" data-bs-toggle="modal" data-bs-target="#modalLogin">
                        ¿Olvidaste tu contraseña?
                    </button>
                </div>
                <button type="submit" class="btn-login" name="btnLoginForm">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                </button>
            </form>
        </div>
    </div>
</div>