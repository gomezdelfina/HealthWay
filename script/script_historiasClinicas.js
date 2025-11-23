//AJAX

//Consulta internaciones a la BD
async function getInternaciones(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/internaciones/getInternaciones.php', {
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
        console.error("Error al obtener internaciones:", error);
        return [];
    }
}

//Consulta internaciones segun Id Paciente a la BD
async function getInternacionesByPaciente(){
    const baseUrl = window.location.origin;
    
     try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/internaciones/getInternacionesByPaciente.php', {
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
        console.error("Error al obtener internaciones:", error);
        return [];
    }
}

//Consulta revisiones segun Id Internacion a la BD
async function getRevisionesByInter(){
    const baseUrl = window.location.origin;

    let data = {
            'IdInternacion': document.getElementById("revPac").value
        };

    try {
        let response = await fetch(baseUrl + '/2025/HeathWay/Codigo/HealthWay/api/revisiones/getRevisionesByInter.php', {
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
        console.error("Error al obtener revisiones por internacion:", error);
        return [];
    }
}

//Consulta permisos a la BD
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
//

function loadInternacionesTable(internaciones){
    let divTableInter = document.getElementById('divTableInter');
    divTableInter.innerHTML = '';

    let tableHTML = `<table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>N. Internacion</th>
                                <th>Paciente</th>
                                <th>Estado</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>`;

    internaciones.forEach(inter => {
        let fechaInicio = inter.FechaInicio.toLocaleDateString();
        let fechaFin = inter.FechaFin.toLocaleDateString();

        tableHTML += `<tr data-id="${inter.IdInternacion}">
                        <td>${inter.IdInternacion}</td>
                        <td>${inter.Nombre} ${inter.Apellido}</td>
                        <td>${inter.EstadoInternacion}</td>
                        <td>${fechaInicio}</td>
                        <td>${fechaFin}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#modalHC" data-id="${fila.IdRevisiones}">
                                    <i class="bi bi-eye-fill me-2"></i>Ver
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-outline-dark me-2">
                                    <i class="bi bi-download me-2"></i>Descargar
                                </button>
                            </div>
                        </td>
                    </tr>`;
    });

    tableHTML += `</tbody></table>`;
    divTablaRevs.innerHTML = tableHTML;

    divTablaRevs.querySelectorAll('button[data-bs-target="#modalHC"]').forEach(button => {
        button.addEventListener('click', (event) => {
            const idRevision = event.currentTarget.dataset.id;
            mostrarDetallesHC(idRevision);
        });
    });
}

async function loadHCDataAndElements(){
    try{
        let responsePerm = await getPermisos();
        const permisos = responsePerm ? responsePerm.map(permiso => permiso.IdPermiso) : []; 
        
        //Permisos
        if (permisos.includes(39)) {
            document.getElementById("escQR").classList.add('visibility-show');
            document.getElementById("escQR").classList.remove('visibility-remove');
        }

        const internaciones = [];
        if (permisos.includes(40)) {
            internaciones = await getInternaciones();
        }
        if(permisos.includes(41)){
            internaciones  = await getInternacionesByPaciente();
        }

        //Elementos del DOM
        loadInternacionesTable(internaciones);
    } catch (error) {
        console.error("Error al cargar datos concurrentemente:", error);
    }
}

window.onload = function() {
    document.addEventListener('DOMContentLoaded', loadHCDataAndElements);
}