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
                            <input class="form-control" 
                                type="email" id="inputEmail" name="inputEmail" placeholder="nombre@ejemplo.com" pattern=/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{3,}$ />
                            <div id="email-error" class="invalid-feedback"></div>
                            <div id="validEmail"></div>
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