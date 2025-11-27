document.addEventListener('DOMContentLoaded', function() {

    AparecerIndividual();
    AparecerCompartida();
    AparecerCamaIndividual();
    AparecerCamaCompartida();
    OpcionPaciente();
    OpcionSolicitud();
    OpcionHabitacion();
    OpcionCama();

    document.getElementById('registerform').addEventListener('submit', async (e) => {
        e.preventDefault();
        validarForm();
    });
    
});


// ==================== APARICIONES ====================

function AparecerCompartida() {
    const Habitacion = document.getElementById("habitacionPac");
    const Compartida = document.getElementById("DivComp");

    Habitacion.addEventListener("change", function () {
        Compartida.style.display = this.value === "Compartida" ? "block" : "none";
    });
}

function AparecerIndividual() {
    const Habitacion = document.getElementById("habitacionPac");
    const Individual = document.getElementById("DivInd");

    Habitacion.addEventListener("change", function () {
        Individual.style.display = this.value === "Individual" ? "block" : "none";
    });
}

function AparecerCamaCompartida() {
    const Cama = document.getElementById("camaComPac");
    const Compartida = document.getElementById("DivCama");

    Cama.addEventListener("change", function () {
        Compartida.style.display = this.value !== "" ? "block" : "none";
    });
}

function AparecerCamaIndividual() {
    const Habitacion = document.getElementById("camaIndPac");
    const Individual = document.getElementById("DivCama");

    Habitacion.addEventListener("change", function () {
        Individual.style.display = this.value !== "" ? "block" : "none";
    });
}



// ==================== PACIENTES ====================

function OpcionPaciente() {
    const selectPacientes = document.getElementById("paciente");

    fetch("/Healthway/api/internaciones/InternacionPaciente.php")
        .then(response => response.json())
        .then(data => {

            // Si tu PHP devuelve {success:true, data:[...]}
            const lista = Array.isArray(data.data) ? data.data : data;

            selectPacientes.innerHTML = "";

            const firstOption = document.createElement('option');
            firstOption.value = "";
            firstOption.textContent = "Seleccionar un paciente";
            selectPacientes.appendChild(firstOption);

            lista.forEach(item => {
                const option = document.createElement('option');
                option.value = item.IdPaciente;
                option.textContent = `${item.Apellido}, ${item.Nombre} - DNI: ${item.DNI}`;
                selectPacientes.appendChild(option);
            });

        })
        .catch(error => {
            console.error("Error cargando pacientes:", error);

            const errorOption = document.createElement('option');
            errorOption.value = "";
            errorOption.textContent = "Error al cargar pacientes";
            selectPacientes.appendChild(errorOption);
        });
}



// ==================== SOLICITUDES ====================

function OpcionSolicitud() {
    const selectPacientes = document.getElementById('paciente');
    const selectSolicitudes = document.getElementById('solicitud');

    function cargarSolicitudes() {
        const idPaciente = selectPacientes.value;

        selectSolicitudes.innerHTML = '<option value="">Seleccione una solicitud</option>';

        if (idPaciente === "") return;

        fetch(`/Healthway/api/internaciones/InternacionSolicitud.php?idPaciente=${idPaciente}`)
            .then(r => r.json())
            .then(data => {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.IdSolicitud;
                    option.textContent = `${item.TipoSolicitud} - ${item.FechaCreacion}`;
                    selectSolicitudes.appendChild(option);
                });
            })
            .catch(err => {
                console.error("Error al cargar solicitudes:", err);
            });
    }

    selectPacientes.addEventListener('change', cargarSolicitudes);
}



// ==================== HABITACIONES ====================

function OpcionHabitacion() {
    const selectHabitacion = document.getElementById('habitacionPac');
    const selectCompartida = document.getElementById('camaComPac');
    const selectIndividual = document.getElementById('camaIndPac');

    selectHabitacion.addEventListener('change', function() {
        const valor = this.value;

        selectCompartida.innerHTML = '<option value=""> Seleccione primero un tipo de habitación </option>';
        selectIndividual.innerHTML = '<option value=""> Seleccione primero un tipo de habitación </option>';

        if (valor === "") return;

        fetch(`/Healthway/api/internaciones/InternacionHabitacion.php?tipoHab=${valor}`)
            .then(response => response.json())
            .then(data => {

                const destino = valor === "Compartida" ? selectCompartida : selectIndividual;

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.IdHabitacion;
                    option.textContent = item.NumeroHabitacion;
                    destino.appendChild(option);
                });
            })
            .catch(error => console.error('Error AJAX:', error));
    });
}



// ==================== CAMAS ====================

function OpcionCama() {
    const selectCompartida = document.getElementById('camaComPac');
    const selectIndividual = document.getElementById('camaIndPac');
    const selectCama = document.getElementById('camaPac');
    const divCama = document.getElementById('DivCama');

    function cargarCamas(origen) {
        const numeroHab = origen.value;

        if (!numeroHab) {
            selectCama.innerHTML = '<option value="">Seleccione una cama</option>';
            divCama.style.display = "none";
            return;
        }

        selectCama.innerHTML = '<option>Cargando camas...</option>';

        fetch(`/Healthway/api/internaciones/InternacionCama.php?numeroHab=${encodeURIComponent(numeroHab)}`)
            .then(r => r.json())
            .then(data => {
                selectCama.innerHTML = '<option value="">Seleccione una cama</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(cama => {
                        const option = document.createElement('option');
                        option.value = cama.IdCama;
                        option.textContent = `Cama ${cama.NumeroCama}`;
                        selectCama.appendChild(option);
                    });
                    divCama.style.display = "block";
                } else {
                    selectCama.innerHTML = '<option disabled>No hay camas disponibles</option>';
                    divCama.style.display = "block";
                }
            })
            .catch(err => {
                console.error('Error:', err);
                selectCama.innerHTML = '<option>Error al cargar camas</option>';
            });
    }

    selectCompartida.addEventListener("change", function() {
        selectIndividual.value = "";
        cargarCamas(this);
    });

    selectIndividual.addEventListener("change", function() {
        selectCompartida.value = "";
        cargarCamas(this);
    });
}

// ==================== VALIDACION ====================

async function validarForm() {

    const formulario = document.getElementById("registerform");
    const datos = new FormData(formulario);

    const respuesta = await fetch("/Healthway/api/internaciones/RegistrarInternaciones.php", {
        method: "POST",
        body: datos
    });

    const json = await respuesta.json();

    formulario.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
    formulario.querySelectorAll(".invalid-feedback").forEach(el => el.innerHTML = "");

    if (json.status === "error" && json.errores) {

        Object.entries(json.errores).forEach(([campo, mensaje]) => {

            const input = document.getElementById(campo);
            const feedback = input.parentElement.querySelector(".invalid-feedback");

            input.classList.add("is-invalid");
            feedback.innerHTML = mensaje;
        });

        return;
    }

    if (json.status === "error") {

        document.getElementById("resultado").innerHTML = `
            <div class="alert alert-danger">${json.mensaje}</div>`;
        return;
    }

    if (json.status === "success") {

        document.getElementById("resultado").innerHTML = `
            <div class="alert alert-success">${json.mensaje}</div>`;
    }
}
