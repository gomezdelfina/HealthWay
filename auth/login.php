<?php

$userLogin = '';
$claveLogin = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnLoginForm'])) {
    
    // VALIDACIONES
    //-- USUARIO
    if (!isset($_POST['inputUser'])) {
        $errors['user'] = 'Error al logearse';
    } else {
        $userLogin = $_POST['inputUser'];

        if (trim($userLogin) == '') {
            $errors['user'] = 'El campo usuario no puede ser vacío';
        }
    }

    //-- CLAVE
    if (!isset($_POST['inputPass'])) {
        $errors['clave'] = 'Error al logearse';
    } else {
        $claveLogin = $_POST['inputPass'];

        if (trim($claveLogin) == '') {
            $errors['clave'] = 'El campo clave no puede ser vacío';
        }
    }

    // PROCESAR
    if (empty($errors)) {
        require_once( $dirBaseFile . '/conexiones/conectorMySQL.php');

        try {
            ConexionDb::connect();

            $query = "SELECT IdUsuario FROM usuarios WHERE Usuario = :user AND Clave = :psw AND Habilitado = 1";
            $params = [
                ["clave" => ":user", "valor" => $userLogin],
                ["clave" => ":psw", "valor" => $claveLogin]
            ];

            $data = ConexionDb::consult($query, $params);
            
            ConexionDb::disconnect();

            if (!$data){
                $errors['process'] = "Usuario o clave incorrecta";
            } else {
                foreach($data as $row){
                    $_SESSION['usuario'] = $row['IdUsuario'];
                }
                
                header('Location: ' . $dirBaseUrl . '/dashboards/dashboard_layout.php');
            }
        } catch (Exception $e) {
            $errors['process'] = "Problemas para ingresar al sistema";
        }
    }
}else{
    $errors = [];
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
                    <input type="text" class="form-control <?php if (isset($errors['user'])) {
                                                                echo "is-invalid";
                                                            } ?>" name="inputUser" id="inputUser" placeholder="Usuario" pattern="[a-zA-Z0-9]" value="<?php echo $userLogin; ?>" required>
                    <div id="user-error" class="invalid-feedback">
                        <?php if (isset($errors['user'])) { echo $errors['user'];} ?>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control <?php if (isset($errors['clave'])) {
                                                                    echo "is-invalid";
                                                                } ?>" name="inputPass" id="inputPass" placeholder="Contraseña" pattern="[a-zA-Z0-9]" value="<?php echo $claveLogin; ?>" required>
                    <button id="btnShowPsw" class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                    <div id="clave-error" class="invalid-feedback">
                        <?php if (isset($errors['clave'])) {echo $errors['clave'];} ?>
                    </div>
                </div>
                <?php if (isset($errors['process'])) { ?>
                    <div id="invalidUser" class="alert alert-danger alert-dismissible fade show mt-3">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Error!</strong> <?php echo $errors['process']; ?>
                    </div>
                <?php } ?>
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
<!--<script src="path/to/qrcode.min.js"></script> 
esa libreria genera qr-->