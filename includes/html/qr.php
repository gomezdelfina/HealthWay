<div class="modal fade" id="modalQRScanner" tabindex="-1" aria-labelledby="modalQRScannerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalQRScannerLabel">
                    <i class="bi bi-qr-code-scan me-2"></i>Escanear Código QR
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Área del escáner -->
                <div id="qr-reader" class="mb-3"></div>
                
                <!-- Mensaje de estado -->
                <div id="qr-status" class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <span id="qr-status-text">Posiciona el código QR frente a la cámara</span>
                </div>
                
                <!-- Resultado del escaneo -->
                <div id="qr-result" class="d-none">
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading">
                            <i class="bi bi-check-circle me-2"></i>Código QR escaneado
                        </h6>
                        <hr>
                        <p class="mb-0"><strong>Contenido:</strong></p>
                        <p id="qr-result-text" class="mb-0 font-monospace bg-light p-2 rounded mt-2"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>