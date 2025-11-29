// -- Inicialización
document.addEventListener('DOMContentLoaded', function(){
    //Carga inicial de datos
    loadSelectEstadosRev();
    loadSelectTiposRev();
    loadSelectPacientesRev();
    renderizarTablaRev();

    //Validacion datos formulario
    document.getElementById('revisionForm').addEventListener('submit', validarCamposRevision);

    //Cancelacion de formulario
    document.getElementById('btnCancelRevisionForm').addEventListener('click', function() {
        resetFormRevision();
    });

    // Event listener para resetear modal al cerrar
    let modalElement = document.getElementById('modalRevision');
    modalElement.addEventListener('hidden.bs.modal', function() {
        resetFormRevision();
    });

    // Event listener para mostrar el boton crear
    document.getElementById("createRev").addEventListener('click', (event) => {
        document.getElementById("btnGuardarRevision").classList.remove("visibility-remove");
        document.getElementById("btnGuardarRevision").classList.add("visibility-show");

        document.getElementById("btnActRevision").classList.remove("visibility-show");
        document.getElementById("btnActRevision").classList.add("visibility-remove");

        activarDatosModal('crear');
    });

    // Busqueda de revisiones por Paciente, Cama, Habitacion
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
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
}

//Consulta revision a la BD segun ID
async function getRevision(data){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/getRevisionById.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

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
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

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
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

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
        let response = await fetch(baseUrl + '/HealthWay/api/internaciones/getPacientesInterAct.php', {
            method: 'get',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
}

//Genera revision en la BD
async function createRevision(data){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/createRevision.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
}

//Actualiza revision en la BD
async function editRevision(data){
    const baseUrl = window.location.origin;
    
    try {
        let response = await fetch(baseUrl + '/HealthWay/api/revisiones/editRevision.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            let errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
}
// --

// --Utils
//Habilita y deshabilita la edicion de los elementos del modal
function activarDatosModal(type){
    if(type == 'crear'){
        document.getElementById("revPac").disabled = false;
        document.getElementById("tipoRevis").disabled = false;
        document.getElementById("estadoRevis").disabled = false;
        document.getElementById("sintomaRevi").disabled = false;
        document.getElementById("diagRevi").disabled = false;
        document.getElementById("tratamRevi").disabled = false;
        document.getElementById("notasRevi").disabled = false;
        
    }else if (type == 'ver'){
        document.getElementById("revPac").disabled = true;
        document.getElementById("tipoRevis").disabled = true;
        document.getElementById("estadoRevis").disabled = true;
        document.getElementById("sintomaRevi").disabled = true;
        document.getElementById("diagRevi").disabled = true;
        document.getElementById("tratamRevi").disabled = true;
        document.getElementById("notasRevi").disabled = true;

    }else if (type == 'editar'){
        document.getElementById("revPac").disabled = true;
        document.getElementById("tipoRevis").disabled = false;
        document.getElementById("estadoRevis").disabled = false;
        document.getElementById("sintomaRevi").disabled = false;
        document.getElementById("diagRevi").disabled = false;
        document.getElementById("tratamRevi").disabled = false;
        document.getElementById("notasRevi").disabled = false;
    }
}

// Validación de los campos del formulario
async function validarCamposRevision(event) {
    prevenirSubmit(event);
    let formIsValid = 0;

    let IdRevision = '';
    let IdPaciente = '';
    let FechaCreacion = '';
    let HoraCreacion = '';
    let TipoRev = '';
    let EstadoRev = '';
    let Sintomas = '';
    let Diagnostico = '';
    let Tratamiento = '';
    let Notas = '';

    if(!validarOpSelect(document.getElementById("revPac"))){
        document.getElementById("valPac").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valPac").classList.add("visibility-hidden");
        IdPaciente = document.getElementById("revPac").value;
    };
     
    if(!validarOpSelect(document.getElementById("tipoRevis"))){
        document.getElementById("valTipoR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTipoR").classList.add("visibility-hidden");
        TipoRev = document.getElementById("tipoRevis").value;
    };

    if(!validarOpSelect(document.getElementById("estadoRevis"))){
        document.getElementById("valEstR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valEstR").classList.add("visibility-hidden");
        EstadoRev = document.getElementById("estadoRevis").value;
    };

    if(!validarValorVacio(document.getElementById("sintomaRevi"))){
        document.getElementById("valSint").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valSint").classList.add("visibility-hidden");
        Sintomas = document.getElementById("sintomaRevi").value;
    };
    
    if(!validarValorVacio(document.getElementById("diagRevi"))){
        document.getElementById("valDiag").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valDiag").classList.add("visibility-hidden");
        Diagnostico = document.getElementById("diagRevi").value;
    };
    
    if(!validarValorVacio(document.getElementById("tratamRevi"))){
        document.getElementById("valTratam").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTratam").classList.add("visibility-hidden");
        Tratamiento = document.getElementById("tratamRevi").value;
    };

    Notas = document.getElementById("notasRevi").value;
    FechaCreacion = document.getElementById("fechaRevis").value;
    HoraCreacion = document.getElementById("horaRevis").value;

    if (formIsValid === 0) {
        if(event.submitter.id == 'btnGuardarRevision'){
            let rev = {
                'IdPaciente': IdPaciente,
                'FechaCreacion': FechaCreacion,
                'HoraCreacion': HoraCreacion,
                'TipoRev': TipoRev,
                'EstadoRev': EstadoRev,
                'Sintomas': Sintomas,
                'Diagnostico': Diagnostico,
                'Tratamiento': Tratamiento,
                'Notas': Notas
            };
                
            crearRevision(rev);
        }else if(event.submitter.id == 'btnActRevision'){
            IdRevision = document.getElementById("idRevision").value;
            let rev = {
                'IdRevision': IdRevision,
                'IdPaciente': IdPaciente,
                'FechaCreacion': FechaCreacion,
                'HoraCreacion': HoraCreacion,
                'TipoRev': TipoRev,
                'EstadoRev': EstadoRev,
                'Sintomas': Sintomas,
                'Diagnostico': Diagnostico,
                'Tratamiento': Tratamiento,
                'Notas': Notas
            };

            editarRevision(rev);
        }
    }
};

// Resetear formulario
function resetFormRevision() {
    const form = document.getElementById('revisionForm');
    form.reset();

    let divsError = document.querySelectorAll('div .invalid-feedback');
    divsError.forEach(div => {
        div.classList.remove("visibility-show");
        div.classList.add("visibility-hidden");
    });

    elemsError = document.querySelectorAll('.is-invalid');
    elemsError.forEach(elem => {
        elem.classList.remove("is-invalid");
    });
}
// --

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
        let response = await getPacientes();
    
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

        let datos = await getRevisiones();

        if(datos.length === 0) {
            divTablaRevs.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay revisiones creadas</h5>
                    <p class="text-muted">Crea una revision para comenzar</p>
                </div>
            `;
        }else{
            let tableHTML = `
                        <div class="table-responsive">
                            <table id="tablaRevs" class="table table-hover table-striped align-middle">
                                <thead class="table-light">
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

            tableHTML += `
                        </tbody>
                    </table>
                </div>

                <div id="msgSinResultados" class="text-center py-5 visibility-remove">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No se encontraron coincidencias</h5>
                    <p class="text-muted">Intenta buscar con otros términos</p>
                </div>
            `;

            divTablaRevs.innerHTML = tableHTML;

            let botonesVer = document.querySelectorAll(".btn-ver-rev");
            botonesVer.forEach(btn => {
                btn.addEventListener('click', (event) => {
                    idRevision = event.currentTarget.dataset.id;
                    document.getElementById("idRevision").value = idRevision;

                    document.getElementById("btnGuardarRevision").classList.add("visibility-remove");
                    document.getElementById("btnGuardarRevision").classList.remove("visibility-show");
                    document.getElementById("btnActRevision").classList.add("visibility-remove");
                    document.getElementById("btnActRevision").classList.remove("visibility-show");

                    activarDatosModal('ver');
                    verRevision(idRevision);
                });
            });

            let botonesEditar = document.querySelectorAll(".btn-editar-rev");
            botonesEditar.forEach(btn => {
                btn.addEventListener('click', (event) => {
                    idRevision = event.currentTarget.dataset.id;
                    document.getElementById("idRevision").value = idRevision;

                    document.getElementById("btnGuardarRevision").classList.add("visibility-remove");
                    document.getElementById("btnGuardarRevision").classList.remove("visibility-show");
                    document.getElementById("btnActRevision").classList.add("visibility-show");
                    document.getElementById("btnActRevision").classList.remove("visibility-remove");

                    activarDatosModal('editar');
                    verRevision(idRevision);
                });
            });
        }
    }catch(error){
        console.log("Problemas al cargar la tabla de Revisiones: " + error.message);  
        showOkMsg(false, "Problemas al cargar la tabla de Revisiones");
    }
}
// --

// --Transacciones
//Carga datos de Modal Revision con Revision
async function verRevision(idRev){
    try{
        let rev = {
            'idRevision': idRev
        }

        let response = await getRevision(rev);

        if(response.length > 0){
            document.getElementById("revPac").value = response[0].IdPaciente;
            document.getElementById("fechaRevis").value = response[0].FechaCreacion;
            document.getElementById("horaRevis").value = response[0].HoraCreacion;
            document.getElementById("tipoRevis").value = response[0].TipoRevision;
            document.getElementById("estadoRevis").value = response[0].EstadoRevision;
            document.getElementById("sintomaRevi").value = response[0].Sintomas;
            document.getElementById("diagRevi").value = response[0].Diagnostico;
            document.getElementById("tratamRevi").value = response[0].Tratamiento;
            document.getElementById("notasRevi").value = response[0].Notas || '';
        }else{
            throw new Error("No se encontró la revisión en la BD");
        }
    } catch(error){
        console.log("Problemas al cargar los datos de la Revision: " + error.message); 
        showOkMsg(false, "Problemas al mostrar la revision");
    }
}

//Crear revision
async function crearRevision(rev){
    try{
        let $result = await createRevision(rev);

        showOkMsg(true, 'Se cargó una nueva revisión correctamente');

        cerrarModal(document.getElementById('modalRevision'));
        renderizarTablaRev();
    }catch(error){
        console.log("Problemas al crear revision: " + error.message);
        showOkMsg(false,  'Problemas al generar revision');
    }
}

// Editar revision
async function editarRevision(rev) {
    try{
        let $result = await editRevision(rev);

        showOkMsg(true, 'Se actualizó revisión correctamente');

        cerrarModal(document.getElementById('modalRevision'));
        renderizarTablaRev();
    }catch(error){
        console.log("Problemas al actualizar la revision: " + error.message);
        showOkMsg(false, 'Problemas al actualizar la revision');
    }
}

// Buscar revision
function buscarRevision(){
    let input = document.getElementById('buscadorRevs');
    let valor = input.value.toLowerCase();
                
    let tabla = document.getElementById('tablaRevs');
    
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
//--