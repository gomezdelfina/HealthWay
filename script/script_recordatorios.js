// -- Inicialización
document.addEventListener('DOMContentLoaded', function() {
    //Carga inicial de datos
    loadSelectTiposRev();
    loadSelectPacientes();
    renderizarTablaRec();

    //Logica sobre campos radio
    let radios = document.querySelectorAll('input[name="op"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            handleRadioChange(this.id);
        });
    });

    let checkFechaFin = document.getElementById("fechaFinActivo");
    checkFechaFin.addEventListener('change', function() {
        handleCheckBoxChange(this);
    });

    //Validacion datos formulario
    document.getElementById('recordatorioForm').addEventListener('submit', validarCamposRecordatorio);

    //Cancelacion de formulario
    document.getElementById('btnCancelRecordatorioForm').addEventListener('click', function() {
        resetFormRecordatorio();
    });

    // Event listener para resetear modal al cerrar
    let modalElement = document.getElementById('modalRecordatorio');
    modalElement.addEventListener('hidden.bs.modal', function() {
        resetFormRecordatorio();
    });

    // Event listener para mostrar el boton crear
    document.getElementById("createRec").addEventListener('click', (event) => {
        document.getElementById("btnGuardarRecordatorio").classList.remove("visibility-remove");
        document.getElementById("btnGuardarRecordatorio").classList.add("visibility-show");

        document.getElementById("btnActRecordatorio").classList.remove("visibility-show");
        document.getElementById("btnActRecordatorio").classList.add("visibility-remove");

        activarDatosModal('crear');
    });

    let btnBusqueda = document.getElementById('btnBuscarRecs');
        btnBusqueda.addEventListener('click', (event) => {
        buscarRecordatorio();
    });

    let inputBusqueda = document.getElementById('buscadorRecs');
    inputBusqueda.addEventListener('keyup', (event) => {
        buscarRecordatorio();
    });
});

// -- AJAX
//Consulta tipos de revision a la BD según el usuario
async function getTiposRev(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/revisiones/getTiposRevByUser.php', {
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

//Consulta pacientes habilitados de internaciones activas a la BD
async function getPacientes(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/pacientes/getPacientesInterAct.php', {
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

//Consulta recordatorio a la BD segun ID
async function getRecordatorio(idRec){
    const baseUrl = window.location.origin;
    
     try {
        data = {
            'idRecordatorio': idRec
        }

        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/recordatorios/getRecordatorioById.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
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

//Consulta recordatorios a la BD
async function getRecordatorios(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/recordatorios/getRecordatorios.php', {
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

//Genera recordatorio en la BD
async function createRecordatorio(rec){
    const baseUrl = window.location.origin;

    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/recordatorios/createRecordatorio.php', {
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

//Actualiza recordatorio en la BD
async function editRecordatorio(rec){
    const baseUrl = window.location.origin;

    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/recordatorios/editRecordatorio.php', {
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

/*function mostrarNotificacion(tipo, mensaje) {
    // Usar tu sistema de notificaciones existente
    // Ajusta según tu implementación actual
    if(typeof showNotification !== 'undefined') {
        showNotification(tipo, mensaje);
    } else {
        // Fallback simple
        const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
        const icon = tipo === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="bi ${icon} me-2"></i>${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}*/

// -- Utils
// Manejo de cambios en fecha fin de recordatorio
function handleCheckBoxChange(element) {
    let divFrecFin = document.getElementById("divFrecFin");
    
    // Ocultar todos primero
    divFrecFin.classList.add("visibility-remove");
    
    divFrecFin.classList.remove("visibility-show");
    
    // Mostrar según selección
    if(element.checked) {
        divFrecFin.classList.remove("visibility-remove");
        divFrecFin.classList.add("visibility-show");
    } 
}

// Manejo de cambios en tipo de recordatorio
function handleRadioChange(id) {
    let divFrecHorasRep = document.getElementById("divFrecHorasRep");
    let divFrecDiasRep = document.getElementById("divFrecDiasRep");
    let divFrecSemRep = document.getElementById("divFrecSemRep");
    let divDiasCheck = document.getElementById("divDiasCheck");
    
    // Ocultar todos primero
    divFrecHorasRep.classList.add("visibility-remove");
    divFrecDiasRep.classList.add("visibility-remove");
    divFrecSemRep.classList.add("visibility-remove");
    divDiasCheck.classList.add("visibility-remove");
    
    divFrecHorasRep.classList.remove("visibility-show");
    divFrecDiasRep.classList.remove("visibility-show");
    divFrecSemRep.classList.remove("visibility-show");
    divDiasCheck.classList.remove("visibility-show");
    
    // Mostrar según selección
    if(id === "opPorHoras") {
        divFrecHorasRep.classList.remove("visibility-remove");
        divFrecHorasRep.classList.add("visibility-show");
    } else if(id === "opDiariamente") {
        divFrecDiasRep.classList.remove("visibility-remove");
        divFrecDiasRep.classList.add("visibility-show");
    } else if(id === "opSemanalmente") {
        divFrecSemRep.classList.remove("visibility-remove");
        divFrecSemRep.classList.add("visibility-show");
        divDiasCheck.classList.remove("visibility-remove");
        divDiasCheck.classList.add("visibility-show");
    }
}

// Resetear formulario
function resetFormRecordatorio() {
    const form = document.getElementById('recordatorioForm');
    form.reset();

    divsError = document.querySelectorAll('div .invalid-feedback');
    divsError.forEach(div => {
        div.classList.remove("visibility-show");
        div.classList.add("visibility-hidden");
    });

    divsError2 = document.querySelectorAll('div .text-danger');
    divsError2.forEach(div => {
        div.classList.remove("visibility-show");
        div.classList.add("visibility-hidden");
    });

    elemsError = document.querySelectorAll('.is-invalid');
    elemsError.forEach(elem => {
        elem.classList.remove("is-invalid");
    });
    
    // Seleccionar "Una vez" por defecto
    document.getElementById('opUnaVez').checked = true;
    handleRadioChange('opUnaVez');
    
    // Desmarcar todos los checkboxes de días
    document.querySelectorAll('input[name="diasSemana"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    // Seleccionar sin fecha fin por defecto
    document.getElementById("fechaFinActivo").checked = false;
    handleCheckBoxChange(document.getElementById("fechaFinActivo"));
}

//Habilita y deshabilita la edicion de los elementos del modal
function activarDatosModal(type){
    if(type == 'crear'){
        document.getElementById("recObs").removeAttribute('disabled');
        document.getElementById("recPac").removeAttribute('disabled');
        document.getElementById("tipoRevisRec").removeAttribute('disabled');
        document.getElementById("fechaRecordatorio").removeAttribute('disabled');
        document.getElementById("horaRecordatorio").removeAttribute('disabled');

        radios = document.querySelectorAll('input[name="op"]');
        radios.forEach(radio => {
            radio.removeAttribute('disabled');
        });

        document.getElementById("frecDiasRep").removeAttribute('disabled');
        document.getElementById("frecSemRep").removeAttribute('disabled');

        radiosDias = document.querySelectorAll('input[name="diasSemana"]');
        radiosDias.forEach(radio => {
            radio.removeAttribute('disabled');
        });

        document.getElementById("recordatorioActivo").removeAttribute('disabled');
    }else if (type == 'ver'){
        document.getElementById("recObs").setAttribute('disabled','');
        document.getElementById("recPac").setAttribute('disabled','');
        document.getElementById("tipoRevisRec").setAttribute('disabled','');
        document.getElementById("fechaRecordatorio").setAttribute('disabled','');
        document.getElementById("horaRecordatorio").setAttribute('disabled','');

        radios = document.querySelectorAll('input[name="op"]');
        radios.forEach(radio => {
            radio.setAttribute('disabled','');
        });

        document.getElementById("frecDiasRep").setAttribute('disabled','');
        document.getElementById("frecSemRep").setAttribute('disabled','');

        radiosDias = document.querySelectorAll('input[name="diasSemana"]');
        radiosDias.forEach(radio => {
            radio.setAttribute('disabled','');
        });

        document.getElementById("recordatorioActivo").setAttribute('disabled','');
    }else if (type == 'editar'){
        document.getElementById("recPac").setAttribute('disabled','');
        document.getElementById("recObs").removeAttribute('disabled');
        document.getElementById("tipoRevisRec").removeAttribute('disabled');
        document.getElementById("fechaRecordatorio").removeAttribute('disabled');
        document.getElementById("horaRecordatorio").removeAttribute('disabled');

        radios = document.querySelectorAll('input[name="op"]');
        radios.forEach(radio => {
            radio.removeAttribute('disabled');
        });

        document.getElementById("frecDiasRep").removeAttribute('disabled');
        document.getElementById("frecSemRep").removeAttribute('disabled');

        radiosDias = document.querySelectorAll('input[name="diasSemana"]');
        radiosDias.forEach(radio => {
            radio.removeAttribute('disabled');
        });

        document.getElementById("recordatorioActivo").removeAttribute('disabled');
    }
}

// Validación de los campos del formualrio
async function validarCamposRecordatorio(event) {
    prevenirSubmit(event);
    let formIsInvalid = 0;
    
    let IdRecordatorio = '';
    let IdPaciente = '';
    let TipoRev = '';
    let FechaInicio = '';
    let HoraInicio = '';
    let FechaFin = '';
    let Frecuencia = '';
    let FrecuenciaHoras = '';
    let FrecuenciaDias = '';
    let FrecuenciaSemanas = '';
    let RecordatorioLunes = '';
    let RecordatorioMartes = '';
    let RecordatorioMiercoles = '';
    let RecordatorioJueves = '';
    let RecordatorioViernes = '';
    let RecordatorioSabado = '';
    let RecordatorioDomingo = '';
    let Observaciones = '';
    let RecAct = '';

    //Paciente
    if(!validarOpSelect(document.getElementById("recPac"))){
        document.getElementById("valPacRec").classList.add("visibility-show");
        document.getElementById("valPacRec").classList.remove("visibility-hidden");
        formIsInvalid++;
    }else{
        document.getElementById("valPacRec").classList.add("visibility-hidden");
        document.getElementById("valPacRec").classList.remove("visibility-show");
        IdPaciente = document.getElementById("recPac").value;
    };

    //Tipo Revision
    if(!validarOpSelect(document.getElementById("tipoRevisRec"))){
        document.getElementById("valTipoRevisRec").classList.add("visibility-show");
        document.getElementById("valTipoRevisRec").classList.remove("visibility-hidden");
        formIsInvalid++;
    }else{
        document.getElementById("valTipoRevisRec").classList.add("visibility-hidden");
        document.getElementById("valTipoRevisRec").classList.remove("visibility-show");
        TipoRev = document.getElementById("tipoRevisRec").value;
    };
 
    //Fecha recordatorio
    if(!validarValorVacio(document.getElementById("fechaRecordatorio"))){
        document.getElementById("valfechaRecordatorio").classList.add("visibility-show");
        document.getElementById("valfechaRecordatorio").classList.remove("visibility-hidden");
        formIsInvalid++;
    }else{
        document.getElementById("valfechaRecordatorio").classList.add("visibility-hidden");
        document.getElementById("valfechaRecordatorio").classList.remove("visibility-show");
        FechaInicio = document.getElementById("fechaRecordatorio").value;
    };

    //Hora recordatorio
    if(!validarValorVacio(document.getElementById("horaRecordatorio"))){
        document.getElementById("valhoraRecordatorio").classList.add("visibility-show");
        document.getElementById("valhoraRecordatorio").classList.remove("visibility-hidden");
        formIsInvalid++;
    }else{
        document.getElementById("valhoraRecordatorio").classList.add("visibility-hidden");
        document.getElementById("valhoraRecordatorio").classList.remove("visibility-show");
        HoraInicio = document.getElementById("horaRecordatorio").value;
    };

    //Tipo de Recordatorio
    let radioCheck = document.querySelectorAll('input[name="op"]:checked')[0].id;

    if(radioCheck === "opPorHoras"){
        //Repeticion de horas
        if(!validarString(document.getElementById("frecHorasRep"), '^[0-9]+$')){
            document.getElementById("valfrecHorasRep").classList.add("visibility-show");
            document.getElementById("valfrecHorasRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else if(!validarRangoNumerico(document.getElementById("frecHorasRep"))){
            document.getElementById("valfrecHorasRep").classList.add("visibility-show");
            document.getElementById("valfrecHorasRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else{
            document.getElementById("valfrecHorasRep").classList.add("visibility-hidden");
            document.getElementById("valfrecHorasRep").classList.remove("visibility-show");
            Frecuencia = 'Horas';
            FrecuenciaHoras = document.getElementById("frecHorasRep").value;
        };
    } else if(radioCheck === "opDiariamente") {
        //Repeticion de dias
        if(!validarString(document.getElementById("frecDiasRep"), '^[0-9]+$')){
            document.getElementById("valfrecDiasRep").classList.add("visibility-show");
            document.getElementById("valfrecDiasRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else if(!validarRangoNumerico(document.getElementById("frecDiasRep"))){
            document.getElementById("valfrecDiasRep").classList.add("visibility-show");
            document.getElementById("valfrecDiasRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else{
            document.getElementById("valfrecDiasRep").classList.add("visibility-hidden");
            document.getElementById("valfrecDiasRep").classList.remove("visibility-show");
            Frecuencia = 'Diaria';
            FrecuenciaDias = document.getElementById("frecDiasRep").value;
        };
    } else if(radioCheck === "opSemanalmente") {
        //Repeticion de semanas
        if(!validarString(document.getElementById("frecSemRep"), '^[0-9]+$')){
            document.getElementById("valfrecSemRep").classList.add("visibility-show");
            document.getElementById("valfrecSemRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else if(!validarRangoNumerico(document.getElementById("frecSemRep"))){
            document.getElementById("valfrecSemRep").classList.add("visibility-show");
            document.getElementById("valfrecSemRep").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else{
            document.getElementById("valfrecSemRep").classList.add("visibility-hidden");
            document.getElementById("valfrecSemRep").classList.remove("visibility-show");
            Frecuencia = 'Semanal';
            FrecuenciaSemanas = document.getElementById("frecSemRep").value;
        };

        if(document.querySelectorAll('input[name="diasSemana"]:checked').length === 0){
            document.getElementById("valDiasCheck").classList.add("visibility-show");
            document.getElementById("valDiasCheck").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else{
            document.getElementById("valDiasCheck").classList.add("visibility-hidden");
            document.getElementById("valDiasCheck").classList.remove("visibility-show");
            
            RecordatorioLunes = document.getElementById("diaLunes").checked;
            RecordatorioMartes = document.getElementById("diaMartes").checked;
            RecordatorioMiercoles = document.getElementById("diaMiercoles").checked;
            RecordatorioJueves = document.getElementById("diaJueves").checked;
            RecordatorioViernes = document.getElementById("diaViernes").checked;
            RecordatorioSabado = document.getElementById("diaSabado").checked;
            RecordatorioDomingo = document.getElementById("diaDomingo").checked;
        };
    }else{
        Frecuencia = 'Unica Vez';
    };

    //Fecha Fin
    if(document.getElementById("fechaFinActivo").checked){
        //Fecha recordatorio
        if(!validarValorVacio(document.getElementById("fechaFinRecordatorio"))){
            document.getElementById("valfechaFinRecordatorio").classList.add("visibility-show");
            document.getElementById("valfechaFinRecordatorio").classList.remove("visibility-hidden");
            formIsInvalid++;
        }else{
            document.getElementById("valfechaFinRecordatorio").classList.add("visibility-hidden");
            document.getElementById("valfechaFinRecordatorio").classList.remove("visibility-show");
            FechaFin = document.getElementById("fechaFinRecordatorio").value;
        };
    };

    if(document.getElementById("recordatorioActivo").checked){
        RecAct = 1;
    } else {
        RecAct = 0;
    };

    Observaciones = document.getElementById("recObs").value;

    if (formIsInvalid === 0) {
        if(event.submitter.id == 'btnGuardarRecordatorio'){
            let rec = {
                'IdPaciente': IdPaciente,
                'TipoRev': TipoRev,
                'FechaInicio': FechaInicio,
                'HoraInicio': HoraInicio,
                'FechaFin': FechaFin,
                'Frecuencia': Frecuencia,
                'FrecuenciaHoras': FrecuenciaHoras,
                'FrecuenciaDias': FrecuenciaDias,
                'FrecuenciaSem': FrecuenciaSemanas,
                'Observaciones': Observaciones,
                'RepetirLunes': RecordatorioLunes,
                'RepetirMartes': RecordatorioMartes,
                'RepetirMiercoles': RecordatorioMiercoles,
                'RepetirJueves': RecordatorioJueves,
                'RepetirViernes': RecordatorioViernes,
                'RepetirSabado': RecordatorioSabado,
                'RepetirDomingo': RecordatorioDomingo,
                'Activo': RecAct
            };
            createRecordatorio(rec);
        }else if(event.submitter.id == 'btnActRecordatorio'){
            IdRecordatorio = document.getElementById("idRecordatorio").value;

            let rec = {
                'IdRecordatorio': IdRecordatorio,
                'TipoRev': TipoRev,
                'FechaInicio': FechaInicio,
                'HoraInicio': HoraInicio,
                'FechaFin': FechaFin,
                'Frecuencia': Frecuencia,
                'FrecuenciaHoras': FrecuenciaHoras,
                'FrecuenciaDias': FrecuenciaDias,
                'FrecuenciaSem': FrecuenciaSemanas,
                'Observaciones': Observaciones,
                'RepetirLunes': RecordatorioLunes,
                'RepetirMartes': RecordatorioMartes,
                'RepetirMiercoles': RecordatorioMiercoles,
                'RepetirJueves': RecordatorioJueves,
                'RepetirViernes': RecordatorioViernes,
                'RepetirSabado': RecordatorioSabado,
                'RepetirDomingo': RecordatorioDomingo,
                'Activo': RecAct
            };
            editarRecordatorio(rec);
        }
    }
}
// --

// --Carga elementos
//Carga select Tipo de Modal Recordatorio
async function loadSelectTiposRev(){    
    try{
        response = await getTiposRev();

        if(response.length > 0){
            selectTipoRevisRec = document.getElementById("tipoRevisRec");
            response.forEach (tipoRev => {
                option = document.createElement('option');
                option.value = tipoRev.IdTipoRevision;
                option.textContent = tipoRev.DescTipoRevision;
                selectTipoRevisRec.appendChild(option);
            });
        }
    } catch(error){
        console.log("Problemas al cargar el campo Tipos de Revision: " + error.message);  
        showOkMsg(false, "Problemas al cargar el formulario");
    }
}

//Carga select Pacientes de Modal Recordatorio
async function loadSelectPacientes(){
    try{
        response = await getPacientes();
    
        selectRecPac = document.getElementById("recPac");
        response.forEach (paciente => {
            option = document.createElement('option');
            option.value = paciente.IdPaciente;
            option.textContent = paciente.Nombre + ' ' + paciente.Apellido;
            selectRecPac.appendChild(option);
        });
    }catch(error){
        console.log("Problemas al cargar el campo Pacientes: " + error.message); 
        showOkMsg(false, "Problemas al cargar el formulario");
    }
}

//Generacion de tabla con datos de recordatorios
async function renderizarTablaRec() {
    let container = document.getElementById('divTablaRecs');

    try{
        let recordatoriosData = await getRecordatorios();
    
        if(recordatoriosData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay recordatorios programados</h5>
                    <p class="text-muted">Crea un recordatorio para comenzar</p>
                </div>
            `;
        }else{
            let html = `
                <div class="table-responsive">
                    <table id="tablaRecs" class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Paciente</th>
                                <th>Habitacion</th>
                                <th>Cama</th>
                                <th>Tipo recordatorio</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            recordatoriosData.forEach(rec => {
                html += `
                    <tr data-id="${rec.IdRecordatorio}">
                        <td>${rec.Nombre + ' ' + rec.Apellido}</td>
                        <td>${rec.NumeroHabitacion}</td>
                        <td>${rec.NumeroCama}</td>
                        <td>${rec.DescTipoRevision}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button id="btnVerRec" class="btn btn-sm btn-outline-dark me-2 btn-ver-rec" data-bs-toggle="modal" data-bs-target="#modalRecordatorio" data-id="${rec.IdRecordatorio}">
                                    <i class="bi bi-eye-fill me-2"></i>Ver
                                </button>
                                <button id="btnEditarRec" class="btn btn-sm btn-outline-dark me-2 btn-editar-rec" data-bs-toggle="modal" data-bs-target="#modalRecordatorio" data-id="${rec.IdRecordatorio}">
                                    <i class="bi bi-pen me-2"></i>Editar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>

                <div id="msgSinResultados" class="text-center py-5 visibility-remove">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No se encontraron coincidencias</h5>
                    <p class="text-muted">Intenta buscar con otros términos</p>
                </div>
            `;
            
            container.innerHTML = html;

            let botonesVer = document.querySelectorAll(".btn-ver-rec");
            botonesVer.forEach(btn => {
                btn.addEventListener('click', (event) => {
                    idRec = event.currentTarget.dataset.id;
                    document.getElementById("idRecordatorio").value = idRec;

                    document.getElementById("btnGuardarRecordatorio").classList.add("visibility-remove");
                    document.getElementById("btnGuardarRecordatorio").classList.remove("visibility-show");
                    document.getElementById("btnActRecordatorio").classList.add("visibility-remove");
                    document.getElementById("btnActRecordatorio").classList.remove("visibility-show");

                    activarDatosModal('ver');
                    verRecordatorio(idRec);
                });
            });

            let botonesEditar = document.querySelectorAll(".btn-editar-rec");
            botonesEditar.forEach(btn => {
                btn.addEventListener('click', (event) => {
                    idRec = event.currentTarget.dataset.id;
                    document.getElementById("idRecordatorio").value = idRec;

                    document.getElementById("btnGuardarRecordatorio").classList.add("visibility-remove");
                    document.getElementById("btnGuardarRecordatorio").classList.remove("visibility-show");
                    document.getElementById("btnActRecordatorio").classList.add("visibility-show");
                    document.getElementById("btnActRecordatorio").classList.remove("visibility-remove");

                    activarDatosModal('editar');
                    verRecordatorio(idRec);
                });
            });
        }
    }catch(error){
        console.log("Problemas al cargar la tabla de Recordatorios: " + error.message);  
        showOkMsg(false, "Problemas al cargar la tabla de Recordatorios");
    }
}
// --

// --Transacciones
//Carga datos de Modal Recordatorio con Recordatorio
async function verRecordatorio(idRec){
    try{
        response = await getRecordatorio(idRec);

        if(response.length > 0){
            document.getElementById("recPac").value = response[0].IdPaciente;
            document.getElementById("tipoRevisRec").value = response[0].TipoRevision;

            let fechaCreacion = new Date(response[0].FechaInicioRec);

            document.getElementById("fechaRecordatorio").value = formatearDT(fechaCreacion).date;
            document.getElementById("horaRecordatorio").value = formatearDT(fechaCreacion).hora;

            if(response[0].Frecuencia == 'Unica Vez'){
                document.getElementById("opUnaVez").checked;
            }else if(response[0].Frecuencia == 'Horas'){
                document.getElementById("opPorHoras").checked;
                document.getElementById("frecHorasRep").value = response[0].FrecuenciaHoras;
            }else if(response[0].Frecuencia == 'Diaria'){;
                document.getElementById("opDiariamente").checked;
                document.getElementById("frecDiasRep").value = response[0].FrecuenciaDias;
            }else if(response[0].Frecuencia == 'Semanal'){
                document.getElementById("opSemanalmente").checked;
                document.getElementById("frecSemRep").value = response[0].FrecuenciaSem;

                document.getElementById("diaLunes").checked = (response[0].RepetirLunes === 1);
                document.getElementById("diaMartes").checked = (response[0].RepetiraMartes === 1);
                document.getElementById("diaMiercoles").checked = (response[0].RepetiraMiercoles === 1);
                document.getElementById("diaJueves").checked = (response[0].RepetiraJueves === 1);
                document.getElementById("diaViernes").checked = (response[0].RepetiraViernes === 1);
                document.getElementById("diaSabado").checked = (response[0].RepetiraSabado === 1);
                document.getElementById("diaDomingo").checked = (response[0].RepetiraDomingo === 1);
            };

            if(!response[0].FechaFinRec == null){
                let fechaFin = new Date(response[0].FechaFinRec);

                document.getElementById("fechaFinRecordatorio").value = formatearDT(fechaFin).date;
                document.getElementById("fechaFinActivo").checked;
            }

            document.getElementById("recObs").value = response[0].Observaciones;
            document.getElementById("recordatorioActivo").checked = (response[0].activo === 1)
        }else{
            showOkMsg(false, "Problemas al mostrar el recordatorio");

            cerrarModal(document.getElementById('modalRecordatorio'));
            renderizarTablaRec();
        }
    } catch(error){
        console.log("Problemas al cargar los datos del Recordatorio: " + error.message);  
        showOkMsg(false, "Problemas al mostrar el recordatorio");
    }
}

//Crear recordatorio
async function crearRecordatorio(rec){
    try{
        $result = await createRecordatorio(rec);
        showOkMsg(true, 'Se cargó un nuevo recordatorio correctamente');

        cerrarModal(document.getElementById('modalRecordatorio'));
        renderizarTablaRec();
    }catch(error){
        console.log("Problemas al crear el recordatorio: " + error.message);
        showOkMsg(false,  'Problemas al generar el recordatorio');
    }
}

// Editar recordatorio
async function editarRecordatorio(rec) {
    try{
        $result = await editRecordatorio(rec);

        showOkMsg(true, 'Se actualizó recordatorio correctamente');

        cerrarModal(document.getElementById('modalRecordatorio'));
        renderizarTablaRec();
    }catch(error){
        console.log("Problemas al actualizar el recordatorio: " + error.message);
        showOkMsg(false, 'Problemas al actualizar el recordatorio');
    }
}

function buscarRecordatorio(){
    let input = document.getElementById('buscadorRecs');
    let valor = input.value.toLowerCase();
                
    let tabla = document.getElementById('tablaRecs');
    
    let filas = tabla.querySelectorAll('tbody tr');
    let msgDiv = document.getElementById('msgSinResultados');
    let result = false;

    filas.forEach(fila => {
        let textoPaciente = fila.cells[0] ? fila.cells[0].textContent.toLowerCase() : '';
        let textoHab = fila.cells[1] ? fila.cells[1].textContent.toLowerCase() : '';
        let textoCama = fila.cells[2] ? fila.cells[2].textContent.toLowerCase() : '';

        // Comparamos
        if (textoPaciente.includes(valor) || textoHab.includes(valor) || textoCama.includes(valor)) {
            fila.classList.remove("visibility-remove");
            fila.classList.add("visibility-add");
            result = true;
        } else {
            fila.classList.add("visibility-remove");
            fila.classList.remove("visibility-add");
        }
    });

    // Mostrar u ocultar mensaje y tabla según resultados
    if (!result) {
        tabla.classList.add("visibility-remove");
        tabla.classList.remove("visibility-show");

        msgDiv.classList.remove('visibility-remove');
        msgDiv.classList.add("visibility-show");
    } else {
        tabla.classList.remove("visibility-remove");
        tabla.classList.add("visibility-show");

        msgDiv.classList.add('visibility-remove');
        msgDiv.classList.remove('visibility-show');
    }
}
// --