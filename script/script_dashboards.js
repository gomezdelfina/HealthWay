// -- Inicializacion
document.addEventListener('DOMContentLoaded', function(){
    loadAdminDash();
    loadJefeDash();
    loadPMedicoDash();
    loadPacienteDash();
});

// -- AJAX
//Consulta internaciones activas a la BD
async function getInternacionesActivas(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/internaciones/verInternacionesActivas.php', {
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

//Consulta recordatorios pendientes a la bd
async function getRecordatoriosPendientes(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/recordatorios/getRecordatoriosPendientes.php', {
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

//Consulta recordatorios atrasados a la bd
async function getRecordatoriosAtrasados(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/recordatorios/getRecordatoriosAtrasados.php', {
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

//Actualiza estado de recordatorio en la BD
async function updateRecordatorioEstado(idrec){
    const baseUrl = window.location.origin;

    let rec = {
        'IdRecordatorio' : idrec,
        'Estado' : 'Hecho'
    };

    try {
        let response = await fetch(baseUrl + '/HealthWay/api/recordatorios/editRecordatorioEstado.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(rec),
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

function loadAdminDash(){

}

function loadJefeDash(){

}

// -- Pers. Medico
function loadPMedicoDash(){
    verInternacionesActivas();
    verRecordatoriosPend();
    verRecordatoriosAtras();
}

async function verInternacionesActivas(){
    try{
        let result = await getInternacionesActivas();

        document.getElementById("ocupadasValue").value = result.length;
        
    }catch(error){
        console.log("Problemas al obtener las internaciones activas");
    }
}

async function verRecordatoriosPend(){
    try{
        let result = await getRecordatoriosPendientes();

        document.getElementById("recPendValue").value = result.length;
        
    }catch(error){
        console.log("Problemas al obtener los recordatorios pendientes");
    }
}

async function verRecordatoriosAtras(){
    try{
        let result = await getRecordatoriosAtrasados();

        document.getElementById("recAtrasValue").value = result.length;
        
    }catch(error){
        console.log("Problemas al obtener los recordatorios pendientes");
    }
}

async function marcarRecordatorioHecho(id){
    try{
        let result = await updateRecordatorioEstado(id);
    }catch(error){
        console.log("Problemas al actualizar estado de recordatorio");
        showOkMsg(false, 'Problemas al actualizar el recordatorio');
    }
}

// -- Paciente
function loadPacienteDash(){
    cargarDatosPaciente();
}

function cargarDatosPaciente(){
    const baseUrl = window.location.origin;

    const idPaciente = document.getElementById("idUser").value;

    fetch(baseUrl + "/HealthWay/api/pacientes/obtener_internacion.php?idPaciente=" + idPaciente)
        .then(res => res.json())
        .then(data => {
        if (data.error) {
            alert("Debe iniciar sesión para acceder.");
            window.location.href = "index.html";
            return;
        }
        mostrarInternaciones(data);
        })
        .catch(err => console.error("Error al cargar internaciones:", err));

    // Botones navegación
    const btnHoras = document.getElementById("btnHoras");
    const btnRevisiones = document.getElementById("btnRevisiones");
    const tablaHoras = document.getElementById("tablaInternacion");
    const tablaRevisiones = document.getElementById("tablaRevisiones");

    btnHoras.addEventListener("click", () => {
        tablaHoras.classList.remove("d-none");
        tablaRevisiones.classList.add("d-none");
    });

    btnRevisiones.addEventListener("click", () => {
        tablaRevisiones.classList.remove("d-none");
        tablaHoras.classList.add("d-none");

        // ⬅️ PEDIMOS LAS REVISIONES (IdInternacion = 1 por ahora)
        fetchRevisiones(7);
    });
}

function mostrarInternaciones(lista) {
    const contenedor = document.getElementById("contenedorInternaciones");
    contenedor.innerHTML = "";

    // Normalizar array
    if (!Array.isArray(lista)) {
        lista = lista ? [lista] : [];
    }

    if (!lista.length) {
        contenedor.innerHTML = `
        <p class="text-secondary text-center">No hay internaciones registradas.</p>
        `;
        return;
    }

    lista.forEach(p => {

        const fechaInicio = new Date(p.FechaInicio);
        const fechaFin = new Date(p.FechaFin);
        const horasTotales = calcularHoras(fechaInicio, fechaFin);

        contenedor.innerHTML += `
        <div class="border rounded p-3 mb-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-primary">
                <i class="bi bi-clipboard2-pulse"></i>Internación #${p.IdInternacion}
            </h5>
            <span class="badge bg-info">${p.EstadoInternacion}</span>
            </div>

            <small class="text-muted">
            <i class="bi bi-calendar-event me-1"></i>Desde: ${p.FechaInicio}  
            <br>
            <i class="bi bi-calendar-check me-1"></i>Hasta: ${p.FechaFin}
            </small>

            <div class="mt-3">
            <p><strong>Paciente:</strong> ${p.Nombre} ${p.Apellido}</p>
            <p><strong>DNI:</strong> ${p.DNI}</p>
            <p><strong>Horas Totales:</strong> ${horasTotales}</p>
            </div>
        </div>
        `;
    });
}

function calcularHoras(inicio, fin) {
    const diferenciaMS = fin - inicio;
    const horas = diferenciaMS / (1000 * 60 * 60);
    return horas.toFixed(0);
}

function fetchRevisiones(idInternacion) {
    const baseUrl = window.location.origin;

    idInternacion = document.getElementById("idUser").value;

    fetch(baseUrl + `/HealthWay/api/pacientes/obtener_revisiones.php?idInternacion=${idInternacion}`)
        .then(res => res.json())
        .then(data => mostrarRevisiones(data))
        .catch(err => console.error("Error al cargar revisiones:", err));
}

function mostrarRevisiones(lista) {
    const contenedor = document.querySelector("#tablaRevisiones .card-body");
    contenedor.innerHTML = "";
    // Normaliza la respuesta: siempre será un array
    if (!Array.isArray(lista)) {
    lista = lista ? [lista] : [];
    }

    if (!lista.length) {
        contenedor.innerHTML = `<p class="text-secondary">No hay revisiones registradas.</p>`;
        return;
    }

    lista.forEach(r => {
        contenedor.innerHTML += `
        <div class="border rounded p-3 mb-3 shadow-sm">
            <div class="d-flex justify-content-between">
            <h5 class="text-primary">
                <i class="bi bi-search me-2"></i>${r.TipoRevision}
            </h5>
            <span class="badge bg-info">${r.EstadoRevision}</span>
            </div>

            <small class="text-muted">${r.FechaCreacion}</small>

            <div class="mt-3">
            <p><strong>Síntomas:</strong> ${r.Sintomas}</p>
            <p><strong>Diagnóstico:</strong> ${r.Diagnostico}</p>
            <p><strong>Tratamiento:</strong> ${r.Tratamiento}</p>
            <p><strong>Observaciones:</strong> ${r.Observaciones ?? "Sin observaciones"}</p>
            </div>
        </div>
        `;
    });
}