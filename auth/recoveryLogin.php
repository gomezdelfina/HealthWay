<?php

$emailRecovery = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnRecoveryForm'])) {

    // VALIDACIONES
    //-- MAIL
    if (!isset($_POST['inputEmail'])) {
        $errors['email'] = 'Error al enviar email';
    } else {
        $emailRecovery = $_POST['inputEmail'];

        if (trim($emailRecovery) == 'nombre@ejemplo.com') {
            $errors['email'] = 'El email no puede ser vacío';
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$/', $emailRecovery)) {
            $errors['email'] = 'El campo email no tiene el formato correcto';
        }
    }
}else{
    $errors = [];
}

?>

<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header card-header-color">
                <h4 class="modal-title h4-modal-header" id="modalLabel">Olvide mi contraseña</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body div-modal-body">
                <form id="pswRecoveryForm" action="" method="POST" novalidate>
                    <div class="row">
                        <div class="col">
                            <p>Ingrese un email valido y se enviara un correo de reestablecimiento de contraseña.</p>
                            <label class="form-label" for="inputEmail" required>Correo electrónico</label>
                            <input class="form-control <?php if (isset($errors['email'])) {echo "is-invalid";} ?>" 
                                type="email" id="inputEmail" name="inputEmail" placeholder="nombre@ejemplo.com" 
                                value="<?php if (isset($errors['email'])) { echo $emailRecovery;} ?>" pattern=/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{3,}$ />
                            <div id="email-error" class="invalid-feedback">
                                <?php if (isset($errors['email'])) {echo $errors['email'];} ?>
                            </div>
                            <div id="invalidEmail" class="alert alert-danger alert-dismissible fade show mt-3 visibility-remove">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Error!</strong> <?php echo $errors['process']; ?>
                            </div>
                            <div id="validEmail" class="alert alert-success alert-dismissible fade show mt-3 visibility-remove">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Felicidades!</strong> Email enviado correctamente.
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancelEmail" form="pswRecoveryForm" id="btnPswRecovery" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-sendEmail" form="pswRecoveryForm" name="btnRecoveryForm">Enviar</button>
            </div>
        </div>
    </div>
</div>