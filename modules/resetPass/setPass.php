<?php
    require_once(__DIR__ . '/../../includes/globals.php');
    require_once($dirBaseFile . '/dataAccess/usuarios.php');
    require_once($dirBaseFile . '/dataAccess/permisos.php');

    $idUser = '';
    $token = '';
   
    try{
        $token = isset($_GET['token']) ? $_GET['token'] : '';

        $result = Usuarios::getUsuarioByToken($token);

        if(!empty($result)){
            $idUser = $result[0]["IdUsuario"];

            if(!Permisos::tienePermiso(49,$idUser)){
                throw new Exception();
            }
        }else{
            throw new Exception();
        }
    }catch(Exception $e){
        echo "Problemas al recuperar la contraseña. Contáctese con el Centro de Soporte de Healthway.";
        die;
    }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'resetPass';
        require_once($dirBaseFile . '/includes/html/head.php'); 
    ?>
</head>
<body>
    <div class="img-background background">
        <div class="login-container col-sm-8 col-lg-5">
            
            <div class="hospital-logo">
                <img src="<?php echo $dirBaseUrl ?>/imgs/logoSolo.png" alt="logo">
            </div>

            <div class="w-100">
                <h3 class="login-title">Reestablecer contraseña</h3>
                <p class="login-subtitle">Ingresa tu nueva clave a continuación</p>

                <form id="resetPasswordForm" method="POST" novalidate>
                    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
                    <div class="mb-1">
                        <label for="newPass" class="form-label text-start w-100">Nueva contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="newPass" id="newPass" pattern="[a-zA-Z0-9]" required>
                            <button class="btn btn-outline-secondary" type="button" id="btnShowPswNewPass">
                                <i class="bi bi-eye" id="iconNew"></i>
                            </button>
                        </div>
                        <div id="newPass-error" class="text-danger"></div>
                    </div>

                    <div class="mb-2">
                        <label for="confirmPass" class="form-label text-start w-100">Confirmar contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                            <input type="password" class="form-control" name="confirmPass" id="confirmPass" pattern="[a-zA-Z0-9]" required>
                             <button class="btn btn-outline-secondary" type="button" id="btnShowPswConfirmPass">
                                <i class="bi bi-eye" id="iconConfirm"></i>
                            </button>
                        </div>
                        <div id="confirmPass-error" class="text-danger"></div>
                    </div>

                    <div id="validPass"></div>

                    <button type="submit" class="btn-login mt-2">
                        <i class="bi bi-check-circle me-2"></i>Cambiar contraseña
                    </button>

                    <div class="mt-2 text-center">
                        <a href="<?php echo $dirBaseUrl ?>/index.php" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Volver al inicio de sesión
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>