// -- Inicialización
document.addEventListener('DOMContentLoaded', function(){
    //Carga inicial de datos
    loadSelectEstadosRev();
    loadSelectTiposRev();
    loadSelectPacientesRev();
    renderizarTablaRev();

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

// --Carga elementos
//Carga select Tipo de Modal Revision
async function loadSelectTiposRev(){    
    try{
        let response = await getTiposRev();

        if(response.length > 0){
            let selectRevTipos = document.getElementById("tipoRevis");
            response.forEach (tipoRev => {
                let option = document.createElement('option');
                option.value = tipoRev.IdTipoRevision;
                option.textContent = tipoRev.DescTipoRevision;
                selectRevTipos.appendChild(option);
            });
        }
    } catch(error){
        console.log("Problemas al cargar el campo Tipos de Revision: " + error.message);  
        showOkMsg(false, "Problemas al cargar el formulario");
    }
}

//Carga select Estado de Modal Revision
async function loadSelectEstadosRev(){  
    try{
        let response = await getEstadosRev();

       if(response.length > 0){
            let selectRevEstados = document.getElementById("estadoRevis");
            response.forEach (estadoRev => {
                let option = document.createElement('option');
                option.value = estadoRev.IdEstadoRev;
                option.textContent = estadoRev.DescEstadoRev;
                selectRevEstados.appendChild(option);
            });
        }
            
    } catch(error){
        console.log("Problemas al cargar el campo Estados de Revision: " + error.message);  
        showOkMsg(false, "Problemas al cargar el formulario");
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
        showOkMsg(false, "Problemas al cargar el formulario");
    }
    
}

//Carga la tabla Revisiones
async function renderizarTablaRev(){   
    try{
        divTablaRevs = document.getElementById('divTablaRevs');

        datos = await getRevisiones();

        if(datos.length === 0) {
            divTablaRevs.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay revisiones creadas</h5>
                    <p class="text-muted">Crea una revision para comenzar</p>
                </div>
            `;
        }else{
            let tableHTML = `<table class="table table-hover table-striped">
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
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <button id="btnVerRev" class="btn btn-sm btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#modalRevision" data-id="${rev.IdRevisiones}">
                                            <i class="bi bi-eye-fill me-2"></i>Ver
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
            });

            tableHTML += `</tbody></table>`;
            divTablaRevs.innerHTML = tableHTML;

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
                    cargarDatosRev(idRevision);
                });
            });
        }
    }catch(error){
        console.log("Problemas al cargar la tabla de Revisiones: " + error.message);  
        showOkMsg(false, "Problemas al cargar la tabla de Revisiones");
    }
}
