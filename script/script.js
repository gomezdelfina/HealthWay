// -- AJAX
async function getPermisos(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/permisos/getPermisosByUser.php', {
            method: 'get',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            result = await response.json(); 

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
}
// --

// -- Utils
//Formatea fecha a lo necesitado en la BD
function formatearFecha(fecha) {
    let anio = fecha.getFullYear();
    let mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Enero es 0
    let dia = String(fecha.getDate()).padStart(2, '0');

    let fechaFormatoInput = `${anio}-${mes}-${dia}`;

    return fechaFormatoInput;
}

//Formatea hora a lo necesitado en la BD
function formatearHora(hora) {
    let horas = String(hora.getHours()).padStart(2, '0');
    let minutos = String(hora.getMinutes()).padStart(2, '0');

    let horaFormatoInput = `${horas}:${minutos}`;    // "09:30"

    return horaFormatoInput;
}

//Formatea datetime a lo necesitado
function formatearDT(fecha) {
    let anio = fecha.getFullYear();
    let mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Enero es 0
    let dia = String(fecha.getDate()).padStart(2, '0');
 
    let horas = String(fecha.getHours()).padStart(2, '0');
    let minutos = String(fecha.getMinutes()).padStart(2, '0');

    let horaFormatoInput = `${horas}:${minutos}`;    // "09:30"
    let fechaFormatoInput = `${anio}-${mes}-${dia}`;

    return {date: fechaFormatoInput, hora: horaFormatoInput};
}

//Previene submit de formularios
function prevenirSubmit(event){
    event.preventDefault();
    event.stopPropagation();
}

//Valida si el valor de un string supera su maximo permitido y agrega clase "is-invalid" en caso de error
function validarMaxLength(element) {
    if (element.value.trim().length > element.maxLength) {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Valida si el campo fecha contiene valor y agrega clase "is-invalid" en caso de error
function validarFormatoFecha(element) {
    if (element.value.trim() != 'dd-mm-aaaa') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Valida si el campo hora contiene valor y agrega clase "is-invalid" en caso de error
function validarFormatoHora(element) {
    if (element.value.trim() != '--:--') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Valida si el campo numerico se encuentra en el rango deseado y agrega clase "is-invalid" en caso de error
function validarRangoNumerico(element) {
    try{
        if (parseInt(element.value.trim()) < parseInt(element.min) || parseInt(element.value.trim()) > parseInt(element.max)) {
            element.classList.add("is-invalid");
            return false;
        } else {
            element.classList.remove("is-invalid");
            return true;
        }
    }catch(error){
        element.classList.add("is-invalid");
        return false;
    }
}

//Valida si el valor de un elemento esta vacio y agrega clase "is-invalid" en caso de error
function validarValorVacio(element) {
    if (element.value.trim() === '') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Valida si el valor de un elemento se corresponde con el expresion regular y agrega clase "is-invalid" en caso de error
function validarString(element, regexp) {
    let regex = new RegExp(regexp);

    if (!regex.test(element.value.trim())) {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Valida que se selccione un valor en select
function validarOpSelect(element) {
    if (element.value.trim() === '-1') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

//Cierra la instancia de modal recibida por parámetro
function cerrarModal(modalElement){
    let modalInstance = bootstrap.Modal.getInstance(modalElement);
        
    if (modalInstance) {
        modalInstance.hide();
    }
}

//Muestra un toast con mensaje de status
function showOkMsg(msgOk, msg){
    resultMsgElement = document.getElementById('resultMsg');
    toast = new bootstrap.Toast(resultMsg);

    resultMsgHeader = resultMsgElement.querySelector('.toast-header strong');
    resultMsgBody = resultMsgElement.querySelector('.toast-body');

    if(msgOk){
        resultMsgHeader.textContent = 'Felicitaciones!';
        resultMsgBody.textContent = msg;
        resultMsgElement.classList.remove('text-bg-danger');
        resultMsgElement.classList.add('text-bg-success'); 
    }else{
        resultMsgHeader.textContent = 'Error!';
        resultMsgBody.textContent = msg;
        resultMsgElement.classList.add('text-bg-danger');
        resultMsgElement.classList.remove('text-bg-success'); 
    }

    toast.show();
}

// Esconde/muestra la clave puesta
function showPassword(element){
    inputPass = element;

    if(inputPass.type === "password"){
        inputPass.type = "text"
    }else{
        inputPass.type = "password"
    }
}

// Actualizar estado del escáner QR
function actualizarEstadoQR(tipo, mensaje) {
    let statusDiv = document.getElementById('qr-status');
    let statusText = document.getElementById('qr-status-text');
    
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
// --