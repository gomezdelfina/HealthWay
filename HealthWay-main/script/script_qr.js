let qrResultData = '';
let currentCameraId = null;

// -- Inicialización
document.addEventListener('DOMContentLoaded', function() { 
    // Abre el modal de escaneo
    document.getElementById('btnEscanearQR').addEventListener('click', abrirEscanerQR);

    // Cerrar el modal
    document.getElementById('modalQRScanner').addEventListener('hidden.bs.modal', cerrarEscanerQR);
});

// Abrir el escáner QR
async function abrirEscanerQR() {
    try {
        // Verificar si el navegador soporta getUserMedia
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showOkMsg(false, "Tu navegador no soporta acceso a la cámara");
        }else{
            // Solicitar permiso de cámara
            let stream = await navigator.mediaDevices.getUserMedia({
                video: true 
            });
            stream.getTracks().forEach(track => track.stop()); // Detener el stream de prueba

            // Abrir el modal
            let modal = new bootstrap.Modal(document.getElementById('modalQRScanner'));
            modal.show();

            // Esperar a que el modal esté completamente visible
            setTimeout(() => {
                iniciarEscaner();
            }, 300);
        }

    } catch (error) {
            console.error('Error al acceder a la cámara:', error);
            
            if (error.name === 'NotAllowedError') {
                showOkMsg(false,'Permiso de cámara denegado. Por favor, habilita el acceso a la cámara en la configuración de tu navegador.');
            } else if (error.name === 'NotFoundError') {
                showOkMsg(false,'No se encontró ninguna cámara en este dispositivo.');
            } else {
                showOkMsg(false,'Error al acceder a la cámara.');
            }
    }
    
}

// Iniciar el escáner
function iniciarEscaner() {
    //Genera QR box
    let config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true
    };

    let html5QrcodeScanner = new Html5Qrcode("qr-reader");

    // Obtener cámaras disponibles
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length > 0) {
            currentCameraId = cameras[cameras.length - 1].id;
            
            // Iniciar escáner
            html5QrcodeScanner.start(
                currentCameraId,
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error('Error al iniciar escáner:', err);
                showOkMsg(false, "No se pudo iniciar el escáner");
            });
        }
    }).catch(err => {
        console.error('Error al obtener cámaras:', err);
        showOkMsg(false, "No se pudieron detectar las cámaras del dispositivo");
    });
}

// Cerrar el escáner
function cerrarEscanerQR() {
    detenerEscaner();
    
    // Resetear variables
    qrResultData = '';
    currentCameraId = null;
    
    // Limpiar interfaz
    document.getElementById('qr-result').classList.add('d-none');
    document.getElementById('qr-result-text').textContent = '';
    document.getElementById('btnCopiarQR').disabled = true;
    document.getElementById('qr-camera-selector').classList.add('d-none');
    
    // Restaurar mensaje de estado
    actualizarEstadoQR('info', 'Posiciona el código QR frente a la cámara');
}

// Detener el escáner
function detenerEscaner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            console.log('Escáner detenido');
        }).catch(err => {
            console.error('Error al detener escáner:', err);
        });
    }
}

// Escaneo exitoso
function onScanSuccess(decodedText) {
    qrResultData = decodedText;
    
    // Mostrar resultado
    document.getElementById('qr-result').classList.remove('d-none');
    document.getElementById('qr-result-text').textContent = decodedText;
    
    // Actualizar estado
    actualizarEstadoQR('success', '¡Código QR escaneado correctamente!');
    
    // Habilitar botón de copiar
    document.getElementById('btnCopiarQR').disabled = false;
    
    // Detener el escáner
    detenerEscaner();
    
    // Procesar el código QR según tu lógica de negocio
    procesarCodigoQR(decodedText);
}

// Error de escaneo
function onScanError(errorMessage) {
    if (!errorMessage.includes('NotFoundException')) {
        console.log('Error de escaneo:', errorMessage);
    }
}

// Procesar código QR
function procesarCodigoQR(codigoQR) {
    if (codigoQR.startsWith('http://') || codigoQR.startsWith('https://')) {
        if (confirm('¿Deseas abrir este enlace?\n\n' + codigoQR)) {
            window.open(codigoQR, '_blank');
        }
    }
}