function loadAdminDash(){

}

function loadJefeDash(){

}

function loadPMedicoDash(){

}

function loadPacienteDash(){

}

async function loadDashData(){
    response = await getPermisos();
    
    if(response){
        permisos = response.map(permiso => permiso.IdPermiso);

        if (permisos.includes(1)) { //Visualizar dashboard personal medico
            document.getElementById("dashboardPersMedico").classList.add('visibility-show');
            document.getElementById("dashboardPersMedico").classList.remove('visibility-remove');

            loadPMedicoDash();
        } else if (permisos.includes(5)) { //Visualizar dashboard administrador
            document.getElementById("dashboardAdmin").classList.add('visibility-show');
            document.getElementById("dashboardAdmin").classList.remove('visibility-remove');

            loadAdminDash();
        } else if (permisos.includes(6)) { //Visualizar dashboard paciente
            document.getElementById("dashboardPaciente").classList.add('visibility-show');
            document.getElementById("dashboardPaciente").classList.remove('visibility-remove');

            loadPacienteDash();
        } else if (permisos.includes(7)) { //Visualizar dashboard jefe internaciones
            document.getElementById("dashboardPersMedico").classList.add('visibility-show');
            document.getElementById("dashboardPersMedico").classList.remove('visibility-remove');

            loadJefeDash();
        }
    }
}

window.onload = function() {
    document.addEventListener('DOMContentLoaded', loadDashData());
}