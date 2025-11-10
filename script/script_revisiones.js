//--AJAX

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
        console.error("Error al obtener permisos del usuario:", error);
        return [];
    }
}

async function getRevisiones(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/revisiones/getRevisiones.php', {
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
        console.error("Error al obtener revisiones:", error);
        return [];
    }
}

//--

function prevenirSubmit(event){
    event.preventDefault();
    event.stopPropagation();
}

function resetearForm(form) {
    form.reset();
}

function validarOpSelect(element) {
    if (element.value === '-1') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

function validarValorVacio(element) {
    if (element.value === '') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

function validarCamposRevision(event) {
    let formIsValid = 0;
    prevenirSubmit(event);

    if(!validarOpSelect(document.getElementById("interPac"))){
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
        //bootstrap.Toast.getOrCreateInstance(document.getElementById('liveToast')).show();
        event.target.submit(); // Esto enviará el formulario de nuevo.
    }
};

function validarCamposRecordatorio(event) {
    
};

function handleRadioChange(id){
    if(id === "opUnaVez"){
        document.getElementById("divFrecDiasRep").classList.add("visibility-remove");
        document.getElementById("divFrecSemRep").classList.add("visibility-remove");
        document.getElementById("divDiasCheck").classList.add("visibility-remove");
    }else if(id === "opDiariamente"){
        document.getElementById("divFrecDiasRep").classList.remove("visibility-remove");

        document.getElementById("divFrecDiasRep").classList.add("visibility-show");
        document.getElementById("divFrecSemRep").classList.add("visibility-remove");
        document.getElementById("divDiasCheck").classList.add("visibility-remove");
    }else if(id === "opSemanalmente"){
        document.getElementById("divFrecSemRep").classList.remove("visibility-remove");
        document.getElementById("divDiasCheck").classList.remove("visibility-remove");

        document.getElementById("divFrecDiasRep").classList.add("visibility-remove");
        document.getElementById("divFrecSemRep").classList.add("visibility-show");
        document.getElementById("divDiasCheck").classList.add("visibility-show");
    }

}

async function createTableRev(){   
    datos = await getRevisiones();

    tablaRevs = document.getElementById('tablaRevs');
    tablaRevs.innerHTML = '';

    tabla = document.createElement('table');
    tabla.classList.add('table', 'table-hover', 'table-striped');

    thead = document.createElement('thead');
    trHead = document.createElement('tr');

    encabezados = [
        "Paciente", "Habitacion", "Cama", "Tipo", "Estado", "Fecha", "Hora", "Acciones"
    ];
    encabezados.forEach(encabezado => {
        th = document.createElement('th');
        th.textContent = encabezado;
        trHead.appendChild(th);
    });

    thead.appendChild(trHead);
    tabla.appendChild(thead);

    tbody = document.createElement('tbody');
    datos.forEach(fila => {
        //Id
        trBody = document.createElement('tr');
        trBody.setAttribute('data-id', fila.IdRevision);

        //Paciente
        td = document.createElement('td');
        td.textContent = fila.Nombre + ' ' + fila.Apellido;
        trBody.appendChild(td);

        //Habitacion
        td = document.createElement('td');
        td.textContent = fila.Habitacion;
        trBody.appendChild(td);

        //Cama
        td = document.createElement('td');
        td.textContent = fila.Cama;
        trBody.appendChild(td);

        ['Paciente', 'Habitacion', 'Cama', 'Tipo', 'Estado', 'Fecha', 'Hora'].forEach(propiedad => {
            td = document.createElement('td');
            td.textContent = fila.Nombre + ' ' + fila.Apellido;
            trBody.appendChild(td);
        });

        //Boton Ver
        tdVer = document.createElement('td');
        divVer = document.createElement('div');
        divVer.classList.add('d-flex', 'justify-content-end');
        btnVer = document.createElement('button');
        btnVer.classList.add('btn', 'btn-sm', 'btn-outline-dark', 'me-2');
        btnVer.setAttribute('data-bs-toggle', 'modal');
        btnVer.setAttribute('data-bs-target', '#modalRevision');
        btnVer.innerHTML = '<i class="bi bi-eye-fill me-2"></i>Ver';
        btnVer.addEventListener('click', (event) => {
            filaActual = event.target.closest('tr');
            idRevision = filaActual.dataset.idrevision;
            mostrarDetallesRevision(idRevision);
        });
        divVer.appendChild(btnVer);
        tdVer.appendChild(divVer);
        trBody.appendChild(tdVer);

        //Boton Editar
        tdEdit = document.createElement('td');
        divEdit = document.createElement('div');
        divEdit.classList.add('d-flex', 'justify-content-end');
        btnEdit = document.createElement('button');
        btnEdit.classList.add('btn', 'btn-sm', 'btn-outline-dark', 'me-2');
        btnEdit.setAttribute('data-bs-toggle', 'modal');
        btnEdit.setAttribute('data-bs-target', '#modalRevision');
        btnEdit.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar';
        btnEdit.addEventListener('click', (event) => {
            filaActual = event.target.closest('tr');
            idRevision = filaActual.dataset.idrevision;
            mostrarDetallesRevision(idRevision);
        });
        divEdit.appendChild(btnEdit);
        tdEdit.appendChild(divEdit);
        trBody.appendChild(tdEdit);
    });

    tabla.appendChild(tbody);

    tablaRevs.appendChild(tabla);
}

async function loadPermisos(){
    response = await getPermisos();
    
    if(response){
        permisos = response.map(permiso => permiso.IdPermiso);

        if (permisos.includes(8)) { //Crear Revision
            document.getElementById("createRev").classList.add('visibility-show');
            document.getElementById("createRev").classList.remove('visibility-remove');
        }
        
        if (permisos.includes(11)) { //Crear Recordatorio
            document.getElementById("createRec").classList.add('visibility-show');
            document.getElementById("createRec").classList.remove('visibility-remove');
        }
    }
}

async function loadRevData(){
    await loadPermisos();
    await createTableRev();
    //createTableRec();
}

window.onload = function() {
    document.addEventListener('DOMContentLoaded', loadRevData());

    /*Revisiones*/
    document.getElementById('revisionForm').addEventListener('submit', validarCamposRevision);
    document.getElementById('btnCancelRevisionForm').addEventListener('click', 
        resetearForm(document.getElementById('revisionForm')));
    
    /*Recordatorios*/
    radios = document.querySelectorAll('input[name="op"]');
    radios.forEach(radio => {
            radio.addEventListener('change', function() {
                handleRadioChange(this.id);
            });
        });
    if(opCheck = document.querySelector('input[name="op"]:checked')){
        handleRadioChange(opCheck.id);
    };

    document.getElementById('recordatorioForm').addEventListener('submit', validarCamposRecordatorio);
    document.getElementById('btnCancelRecordatorioForm').addEventListener('click', 
        resetearForm(document.getElementById('recordatorioForm')));
}

