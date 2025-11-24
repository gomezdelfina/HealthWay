// -- Inicialización
document.addEventListener('DOMContentLoaded', function(){
    //Carga inicial de datos
    loadSelectEstadosRev();
    loadSelectTiposRev();
    loadSelectPacientesRev();

    renderizarTableRev();

    //Validacion datos formulario 
    document.getElementById('revisionForm').addEventListener('submit', validarCamposRevision);

    //Cancelacion de formulario
    document.getElementById('btnCancelRevisionForm').addEventListener('click', function() {
        resetFormRecordatorio();
    });

    // Event listener para resetear modal al cerrar
    let modalElement = document.getElementById('modalRevision');
    modalElement.addEventListener('hidden.bs.modal', function() {
        resetFormRecordatorio();
    });

    // Event listener para mostrar el boton crear
    document.getElementById("createRev").addEventListener('click', (event) => {
        document.getElementById("btnGuardarRevision").classList.remove("visibility-remove");
        document.getElementById("btnGuardarRevision").classList.add("visibility-show");

        document.getElementById("btnActRevision").classList.remove("visibility-show");
        document.getElementById("btnActRevision").classList.add("visibility-remove");

        activarDatosModal('crear');
    });

    let btnBusqueda = document.getElementById('btnBuscarRevs');
        btnBusqueda.addEventListener('click', (event) => {
        buscarRevision();
    });

    let inputBusqueda = document.getElementById('buscadorRevs');
    inputBusqueda.addEventListener('keyup', (event) => {
        buscarRevision();
    });
});

// -- AJAX
//Consulta revisiones a la BD
async function getRevisiones(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/getRevisiones.php', {
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

//Consulta revision a la BD segun ID
async function getRevision(idRev){
    const baseUrl = window.location.origin;
    
     try {
        data = {
            'idRevision': idRev
        }

        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/getRevisionById.php', {
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

//Consulta pacientes habilitados de internaciones activas a la BD
async function getPacientes(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/HealthWay/api/pacientes/getPacientesInterAct.php', {
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

//Consulta tipos de revision a la BD según el usuario
async function getTiposRev(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/getTiposRevByUser.php', {
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

//Consulta estados de revision a la BD segun el usuario
async function getEstadosRev(){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/getEstadosRevByUser.php', {
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

//Genera revision en la BD
async function createRevision(){
    const baseUrl = window.location.origin;
    
    let data = {
            'IdPaciente': document.getElementById("revPac").value,
            'FechaCreacion': document.getElementById("fechaRevis").value,
            'HoraCreacion': document.getElementById("horaRevis").value,
            'TipoRev': document.getElementById("tipoRevis").value,
            'EstadoRev': document.getElementById("estadoRevis").value,
            'Sintomas': document.getElementById("sintomaRevi").value,
            'Diagnostico': document.getElementById("diagRevi").value,
            'Tratamiento': document.getElementById("tratamRevi").value,
            'Notas': document.getElementById("notasRevi").value
        };

    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/revisiones/createRevision.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
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

//Actualiza revision en la BD
async function editRevision(idRev){
    const baseUrl = window.location.origin;
    
    let data = {
            'IdRevision': idRev,
            'TipoRev': document.getElementById("tipoRevis").value,
            'EstadoRev': document.getElementById("estadoRevis").value,
            'Sintomas': document.getElementById("sintomaRevi").value,
            'Diagnostico': document.getElementById("diagRevi").value,
            'Tratamiento': document.getElementById("tratamRevi").value,
            'Notas': document.getElementById("notasRevi").value
        };

    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/revisiones/editRevision.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
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
//--

// -- Utils
//Habilita y deshabilita la edicion de los elementos del modal
function activarDatosModal(type){
    if(type == 'crear'){
        document.getElementById("revPac").removeAttribute('disabled');
        document.getElementById("tipoRevis").removeAttribute('disabled');
        document.getElementById("estadoRevis").removeAttribute('disabled');
        document.getElementById("sintomaRevi").removeAttribute('disabled');
        document.getElementById("diagRevi").removeAttribute('disabled');
        document.getElementById("tratamRevi").removeAttribute('disabled');
        document.getElementById("notasRevi").removeAttribute('disabled');
    }else if (type == 'ver'){
        document.getElementById("revPac").setAttribute('disabled','');
        document.getElementById("tipoRevis").setAttribute('disabled','');
        document.getElementById("estadoRevis").setAttribute('disabled','');
        document.getElementById("sintomaRevi").setAttribute('disabled','');
        document.getElementById("diagRevi").setAttribute('disabled','');
        document.getElementById("tratamRevi").setAttribute('disabled','');
        document.getElementById("notasRevi").setAttribute('disabled','');
    }else if (type == 'editar'){
        document.getElementById("revPac").setAttribute('disabled','');
        document.getElementById("tipoRevis").removeAttribute('disabled');
        document.getElementById("estadoRevis").removeAttribute('disabled');
        document.getElementById("sintomaRevi").removeAttribute('disabled');
        document.getElementById("diagRevi").removeAttribute('disabled');
        document.getElementById("tratamRevi").removeAttribute('disabled');
        document.getElementById("notasRevi").removeAttribute('disabled');
    }
}

function showOkMsg(msgOk){
    resultMsgElement = document.getElementById('resultMsg');
    toast = new bootstrap.Toast(resultMsg);

    resultMsgHeader = resultMsgElement.querySelector('.toast-header strong');
    resultMsgBody = resultMsgElement.querySelector('.toast-body');

    if(msgOk){
        resultMsgHeader.textContent = 'Felicitaciones!';
        resultMsgBody.textContent = 'Se cargó una nueva revisión correctamente.';
        resultMsgElement.classList.remove('text-bg-danger');
        resultMsgElement.classList.add('text-bg-success'); 
    }else{
        resultMsgHeader.textContent = 'Error!';
        resultMsgBody.textContent = 'Problemas al ingresar la revisión.';
        resultMsgElement.classList.add('text-bg-danger');
        resultMsgElement.classList.remove('text-bg-success'); 
    }

    toast.show();
}

async function validarCamposRevision(event) {
    prevenirSubmit(event);
    let formIsValid = 0;

    if(!validarOpSelect(document.getElementById("revPac"))){
        document.getElementById("valPac").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valPac").classList.add("visibility-hidden");
    };
     
    if(!validarOpSelect(document.getElementById("tipoRevis"))){
        document.getElementById("valTipoR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTipoR").classList.add("visibility-hidden");
    };

    if(!validarOpSelect(document.getElementById("estadoRevis"))){
        document.getElementById("valEstR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valEstR").classList.add("visibility-hidden");
    };

    if(!validarValorVacio(document.getElementById("sintomaRevi"))){
        document.getElementById("valSint").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valSint").classList.add("visibility-hidden");
    };
    
    if(!validarValorVacio(document.getElementById("diagRevi"))){
        document.getElementById("valDiag").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valDiag").classList.add("visibility-hidden");
    };
    
    if(!validarValorVacio(document.getElementById("tratamRevi"))){
        document.getElementById("valTratam").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTratam").classList.add("visibility-hidden");
    };

    if (formIsValid === 0) {
        try{
            btnElement = event.submitter;

            if(btnElement.innerText == 'Crear'){
                $result = await createRevision();

                document.getElementById("btnCancelRevisionForm").click();
                showOkMsg(true);
            }else if(btnElement.innerText == 'Actualizar'){
                idRevision = event.currentTarget.parentNode.dataset.id;
                $result = await editRevision(idRevision);

                document.getElementById("btnCancelRevisionForm").click();
                showOkMsg(true);
            } 
        }catch(error){
            console.log("Problemas al crear la Revision: " + error.message);
            showOkMsg(false);
        }
    }
};

// --Carga elementos
//Carga select Tipo de Modal Revision
async function loadSelectTiposRev(){    
    try{
        response = await getTiposRev();

        if(response.length > 0){
            selectRevTipos = document.getElementById("tipoRevis");
            response.forEach (tipoRev => {
                option = document.createElement('option');
                option.value = tipoRev.IdTipoRevision;
                option.textContent = tipoRev.DescTipoRevision;
                selectRevTipos.appendChild(option);
            });
        }
    } catch(error){
        console.log("Problemas al cargar el campo Tipos de Revision: " + error.message);  
    }
    
}

//Carga select Estado de Modal Revision
async function loadSelectEstadosRev(){  
    try{
        response = await getEstadosRev();

       if(response.length > 0){
            selectRevEstados = document.getElementById("estadoRevis");
            response.forEach (estadoRev => {
                option = document.createElement('option');
                option.value = estadoRev.IdEstadoRev;
                option.textContent = estadoRev.DescEstadoRev;
                selectRevEstados.appendChild(option);
            });
        }
            
    } catch(error){
        console.log("Problemas al cargar el campo Estados de Revision: " + error.message);  
    }
}

//Carga select Pacientes de Modal Revision
async function loadSelectPacientesRev(){
    try{
        response = await getPacientes();
    
        selectRevPac = document.getElementById("revPac");
        response.forEach (paciente => {
            option = document.createElement('option');
            option.value = paciente.IdPaciente;
            option.textContent = paciente.Nombre + ' ' + paciente.Apellido;
            selectRevPac.appendChild(option);
        });
    }catch(error){
        console.log("Problemas al cargar el campo Pacientes: " + error.message); 
    }
    
}

//Carga la tabla Revisiones
async function renderizarTableRev(){   
    let container = document.getElementById('divTablaRevs');

    try{
        let datos = await getRevisiones();

        if(datos.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay revisiones cargadas</h5>
                    <p class="text-muted">Crea una revision para comenzar</p>
                </div>
            `;
        }else{
            let tableHTML = `
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Habitacion</th>
                                        <th>Cama</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>`;

            datos.forEach(rev => {
                dtRev = new Date(rev.FechaCreacion)
                let fecha = dtRev.toLocaleDateString();
                let hora = dtRev.toLocaleTimeString();

                tableHTML += `<tr data-id="${rev.IdRevisiones}">
                                <td>${rev.Nombre + ' ' + rev.Apellido}</td>
                                <td>${rev.NumeroHabitacion}</td>
                                <td>${rev.NumeroCama}</td>
                                <td>${rev.TipoRevision}</td>
                                <td>${rev.EstadoRevision}</td>
                                <td>${fecha}</td>
                                <td>${hora}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button id="btnVerRev" class="btn btn-sm btn-outline-dark me-2 btn-ver-rev" data-bs-toggle="modal" data-bs-target="#modalRevision" data-id="${rev.IdRevisiones}">
                                            <i class="bi bi-eye-fill me-2"></i>Ver
                                        </button>
                                        <button id="btnEditarRev" class="btn btn-sm btn-outline-dark me-2 btn-editar-rev" data-bs-toggle="modal" data-bs-target="#modalRevision" data-id="${rev.IdRevisiones}">
                                            <i class="bi bi-pen me-2"></i>Editar
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
            });

            tableHTML += `</tbody>
                    </table>
                </div>

                <div id="msgSinResultados" class="text-center py-5 visibility-remove">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No se encontraron coincidencias</h5>
                    <p class="text-muted">Intenta buscar con otros términos</p>
                </div>`;

            divTablaRevs.innerHTML = tableHTML;

            let botonesVer = document.querySelectorAll(".btn-ver-rev");
            botonesVer.forEach(btn => {
                btn.addEventListener('click', (event) => {
                    idRec = event.currentTarget.dataset.id;
                    document.getElementById("idRevision").value = idRec;

                    document.getElementById("btnGuardarRevision").classList.add("visibility-remove");
                    document.getElementById("btnGuardarRevision").classList.remove("visibility-show");
                    document.getElementById("btnActRevision").classList.add("visibility-remove");
                    document.getElementById("btnActRevision").classList.remove("visibility-show");

                    activarDatosModal('ver');
                    verRevision(idRev);
                });
            });

            divTablaRevs.querySelectorAll('button[data-bs-target="#modalRevision"]').forEach(button => {
                button.addEventListener('click', (event) => {
                    idRevision = event.currentTarget.dataset.id;
                    document.getElementById("btnCreateRev").classList.add("visibility-remove");
                    document.getElementById("btnCreateRev").classList.remove("visibility-show");

                    document.getElementById("btnActualizarRev").classList.add("visibility-remove");
                    document.getElementById("btnActualizarRev").classList.remove("visibility-show");
                    
                    document.getElementById("btnEditarRev").classList.add("visibility-show");
                    document.getElementById("btnEditarRev").classList.remove("visibility-hidden");

                    activarDatosModal(false);
                    verRevision(idRev);
                });
            });
        }
    }catch(error){
        console.log("Problemas al cargar la tabla de Revisiones: " + error.message);  
    }
}

//Carga los datos principales del modulo
function loadRevData(){
    
}

//Habilita y deshabilita la edicion de los elementos del modal
function activarDatosModal(valid){
    if(valid){
        document.getElementById("revPac").removeAttribute('disabled');
        document.getElementById("tipoRevis").removeAttribute('disabled');
        document.getElementById("estadoRevis").removeAttribute('disabled');
        document.getElementById("sintomaRevi").removeAttribute('disabled');
        document.getElementById("diagRevi").removeAttribute('disabled');
        document.getElementById("tratamRevi").removeAttribute('disabled');
        document.getElementById("notasRevi").removeAttribute('disabled');
    }else{
        document.getElementById("revPac").setAttribute('disabled','');
        document.getElementById("tipoRevis").setAttribute('disabled','');
        document.getElementById("estadoRevis").setAttribute('disabled','');
        document.getElementById("sintomaRevi").setAttribute('disabled','');
        document.getElementById("diagRevi").setAttribute('disabled','');
        document.getElementById("tratamRevi").setAttribute('disabled','');
        document.getElementById("notasRevi").setAttribute('disabled','');
    }
}

// --Transacciones
//Carga datos de Modal Revision con Revision
async function verRevision(idRev){
    try{
        response = await getRevision(idRev);

        if(response.length > 0){
            document.getElementById("revPac").value = response[0].IdPaciente;
            document.getElementById("fechaRevis").value = response[0].FechaCreacion;
            document.getElementById("horaRevis").value = response[0].HoraCreacion;
            document.getElementById("tipoRevis").value = response[0].TipoRevision;
            document.getElementById("estadoRevis").value = response[0].EstadoRevision;
            document.getElementById("sintomaRevi").value = response[0].Sintomas;
            document.getElementById("diagRevi").value = response[0].Diagnostico;
            document.getElementById("tratamRevi").value = response[0].Tratamiento;
            document.getElementById("notasRevi").value = response[0].Notas;
        }
    } catch(error){
        console.log("Problemas al cargar los datos de la Revision: " + error.message);  
    }
}

// --
