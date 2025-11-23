// Variables globales para el escáner QR
let html5QrcodeScanner = null;
let qrResultData = '';
let currentCameraId = null;

// Inicializar el escáner QR
document.addEventListener('DOMContentLoaded', function() {
    
    // Event listener para abrir el modal
    const btnEscanearQR = document.getElementById('btnEscanearQR');
    if (btnEscanearQR) {
        btnEscanearQR.addEventListener('click', abrirEscanerQR);
    }

    // Event listener para cerrar el modal
    const modalQR = document.getElementById('modalQRScanner');
    if (modalQR) {
        modalQR.addEventListener('hidden.bs.modal', cerrarEscanerQR);
    }

    // Event listener para copiar resultado
    const btnCopiar = document.getElementById('btnCopiarQR');
    if (btnCopiar) {
        btnCopiar.addEventListener('click', copiarResultadoQR);
    }

    // Event listener para cambio de cámara
    const selectCamera = document.getElementById('qr-camera-select');
    if (selectCamera) {
        selectCamera.addEventListener('change', cambiarCamara);
    }
});

// Abrir el escáner QR
async function abrirEscanerQR() {
    try {
        // Verificar si el navegador soporta getUserMedia
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            mostrarErrorQR('Tu navegador no soporta acceso a la cámara');
            return;
        }

        // Solicitar permiso de cámara
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        stream.getTracks().forEach(track => track.stop()); // Detener el stream de prueba

        // Abrir el modal
        const modal = new bootstrap.Modal(document.getElementById('modalQRScanner'));
        modal.show();

        // Esperar a que el modal esté completamente visible
        setTimeout(() => {
            iniciarEscaner();
        }, 300);

    } catch (error) {
        console.error('Error al acceder a la cámara:', error);
        
        if (error.name === 'NotAllowedError') {
            mostrarErrorQR('Permiso de cámara denegado. Por favor, habilita el acceso a la cámara en la configuración de tu navegador.');
        } else if (error.name === 'NotFoundError') {
            mostrarErrorQR('No se encontró ninguna cámara en este dispositivo.');
        } else {
            mostrarErrorQR('Error al acceder a la cámara: ' + error.message);
        }
    }
}

// Iniciar el escáner
function iniciarEscaner() {
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true
    };

    html5QrcodeScanner = new Html5Qrcode("qr-reader");

    // Obtener cámaras disponibles
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length > 0) {
            // Si hay múltiples cámaras, mostrar selector
            if (cameras.length > 1) {
                mostrarSelectorCamaras(cameras);
            }

            // Intentar usar la cámara trasera en dispositivos móviles
            currentCameraId = cameras[cameras.length - 1].id;
            
            // Iniciar escáner
            html5QrcodeScanner.start(
                currentCameraId,
                config,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error('Error al iniciar escáner:', err);
                mostrarErrorQR('No se pudo iniciar el escáner: ' + err);
            });

            // Agregar línea de animación
            agregarLineaEscaneo();
        }
    }).catch(err => {
        console.error('Error al obtener cámaras:', err);
        mostrarErrorQR('No se pudieron detectar las cámaras del dispositivo');
    });
}

// Callback cuando se escanea exitosamente
function onScanSuccess(decodedText, decodedResult) {
    console.log('QR escaneado:', decodedText);
    
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
    
    // Emitir sonido de éxito (opcional)
    reproducirSonidoExito();
    
    // Procesar el código QR según tu lógica de negocio
    procesarCodigoQR(decodedText);
}

// Callback de error (no mostrar en consola continuamente)
function onScanError(errorMessage) {
    // No hacer nada, es normal que haya "errores" mientras busca el QR
    // Solo registrar errores importantes
    if (!errorMessage.includes('NotFoundException')) {
        console.warn('Error de escaneo:', errorMessage);
    }
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

// Mostrar selector de cámaras
function mostrarSelectorCamaras(cameras) {
    const selector = document.getElementById('qr-camera-selector');
    const select = document.getElementById('qr-camera-select');
    
    // Limpiar opciones anteriores
    select.innerHTML = '';
    
    // Agregar opciones
    cameras.forEach((camera, index) => {
        const option = document.createElement('option');
        option.value = camera.id;
        option.textContent = camera.label || `Cámara ${index + 1}`;
        select.appendChild(option);
    });
    
    // Seleccionar la última cámara (generalmente la trasera)
    select.value = cameras[cameras.length - 1].id;
    
    // Mostrar selector
    selector.classList.remove('d-none');
}

// Cambiar cámara
function cambiarCamara() {
    const select = document.getElementById('qr-camera-select');
    const nuevaCameraId = select.value;
    
    if (nuevaCameraId && nuevaCameraId !== currentCameraId) {
        detenerEscaner();
        
        setTimeout(() => {
            currentCameraId = nuevaCameraId;
            iniciarEscaner();
        }, 300);
    }
}

// Copiar resultado al portapapeles
function copiarResultadoQR() {
    if (qrResultData) {
        navigator.clipboard.writeText(qrResultData).then(() => {
            // Cambiar texto del botón temporalmente
            const btn = document.getElementById('btnCopiarQR');
            const textoOriginal = btn.innerHTML;
            
            btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Copiado!';
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = textoOriginal;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
            }, 2000);
            
        }).catch(err => {
            console.error('Error al copiar:', err);
            alert('No se pudo copiar al portapapeles');
        });
    }
}

// Actualizar estado del escáner
function actualizarEstadoQR(tipo, mensaje) {
    const statusDiv = document.getElementById('qr-status');
    const statusText = document.getElementById('qr-status-text');
    
    // Remover clases anteriores
    statusDiv.classList.remove('alert-info', 'alert-success', 'alert-danger', 'alert-warning');
    
    // Agregar nueva clase según tipo
    switch(tipo) {
        case 'success':
            statusDiv.classList.add('alert-success');
            statusText.innerHTML = '<i class="bi bi-check-circle me-2"></i>' + mensaje;
            break;
        case 'error':
            statusDiv.classList.add('alert-danger');
            statusText.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + mensaje;
            break;
        case 'warning':
            statusDiv.classList.add('alert-warning');
            statusText.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>' + mensaje;
            break;
        default:
            statusDiv.classList.add('alert-info');
            statusText.innerHTML = '<i class="bi bi-info-circle me-2"></i>' + mensaje;
    }
}

// Mostrar error
function mostrarErrorQR(mensaje) {
    actualizarEstadoQR('error', mensaje);
}

// Agregar línea de animación de escaneo
function agregarLineaEscaneo() {
    const reader = document.getElementById('qr-reader');
    if (reader && !reader.querySelector('.qr-scanner-line')) {
        const line = document.createElement('div');
        line.className = 'qr-scanner-line';
        reader.appendChild(line);
    }
}

// Reproducir sonido de éxito (opcional)
function reproducirSonidoExito() {
    // Crear un AudioContext
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        // Si falla, no hacer nada
        console.log('No se pudo reproducir sonido');
    }
}

// Procesar código QR según tu lógica de negocio
function procesarCodigoQR(codigoQR) {
    console.log('Procesando código QR:', codigoQR);
    
    // Aquí puedes agregar tu lógica personalizada
    // Por ejemplo:
    
    // 1. Si es un ID de paciente
    if (codigoQR.startsWith('PAC-')) {
        const idPaciente = codigoQR.replace('PAC-', '');
        console.log('ID de paciente detectado:', idPaciente);
        // Redirigir a la ficha del paciente
        // window.location.href = `/pacientes/ver.php?id=${idPaciente}`;
    }
    
    // 2. Si es un ID de internación
    else if (codigoQR.startsWith('INT-')) {
        const idInternacion = codigoQR.replace('INT-', '');
        console.log('ID de internación detectado:', idInternacion);
        // Redirigir a la internación
        // window.location.href = `/internaciones/ver.php?id=${idInternacion}`;
    }
    
    // 3. Si es una URL
    else if (codigoQR.startsWith('http://') || codigoQR.startsWith('https://')) {
        console.log('URL detectada:', codigoQR);
        // Preguntar si desea abrir el enlace
        if (confirm('¿Deseas abrir este enlace?\n\n' + codigoQR)) {
            window.open(codigoQR, '_blank');
        }
    }
    
    // 4. Cualquier otro código
    else {
        console.log('Código genérico:', codigoQR);
        // Puedes mostrar el código o hacer alguna acción
    }
}