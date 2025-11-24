    <div class="container-fluid">
        <button type="button" id="btnNotificaciones" class="btn btn-light position-relative" title="notificacion">

            <!-- Contador de notificaciones -->
            <span id="notifCount"
                class="badge rounded-pill bg-danger me-2">
                0
            </span>

            <i class="bi bi-bell"></i>

        </button>

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
    </div>