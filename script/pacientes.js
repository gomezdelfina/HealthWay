//---------------------------------------------------------
// CONFIG
//---------------------------------------------------------
const API_BASE = '/HealthWay/api/administrador';

// Bootstrap modal
let pacienteModal;

// Form elements
const form = document.getElementById('pacienteForm');
const submitButton = document.getElementById('submitButton');
const modalTitle = document.getElementById('modalTitle');
const pacienteIdInput = document.getElementById('pacienteId');
const formTypeInput = document.getElementById('formType');
const osSelect = document.getElementById('nombreOS');
const habilitadoGroup = document.getElementById('habilitadoGroup');

//---------------------------------------------------------
// INIT
//---------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {

    pacienteModal = new bootstrap.Modal(document.getElementById('pacienteModal'));

    cargarPacientes();
    cargarObrasSociales();

    form.addEventListener('submit', handleFormSubmit);

    document.getElementById('buscarInput').addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            cargarPacientes();
        }
    });
});

//---------------------------------------------------------
// MENSAJES (Bootstrap Alerts)
//---------------------------------------------------------
function mostrarMensaje(message, isSuccess) {
    const container = document.getElementById('messageContainer');
    const tipo = isSuccess ? 'success' : 'danger';

    const html = `
        <div class="alert alert-${tipo} alert-dismissible fade show shadow" role="alert">
            <strong>${isSuccess ? 'Éxito:' : 'Error:'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}

//---------------------------------------------------------
// FETCH GENÉRICO
//---------------------------------------------------------
async function fetchAPI(url, options = {}) {
    try {
        const res = await fetch(url, options);
        const data = await res.json();

        if (!data.success) {
            mostrarMensaje(data.message || "Error desconocido", false);
            return null;
        }

        return data;

    } catch (err) {
        mostrarMensaje("Error de red o servidor.", false);
        return null;
    }
}

//---------------------------------------------------------
// OBRAS SOCIALES
//---------------------------------------------------------
async function cargarObrasSociales() {
    const response = await fetchAPI(`${API_BASE}/obras_sociales.php`);

    if (!response) return;

    osSelect.innerHTML = '';

    response.data.forEach(os => {
        const option = document.createElement('option');
        option.value = os.IdOS;
        option.textContent = os.NombreOS;
        osSelect.appendChild(option);
    });
}

//---------------------------------------------------------
// LISTAR PACIENTES
//---------------------------------------------------------
async function cargarPacientes() {
    const tbody = document.getElementById('pacientesTableBody');
    const search = document.getElementById('buscarInput').value;

    tbody.innerHTML = `
        <tr><td colspan="7" class="text-center py-4 text-muted">
            <div class="spinner-border"></div>
            <p class="mt-2">Buscando...</p>
        </td></tr>
    `;

    const response = await fetchAPI(`${API_BASE}/listar.php?search=${encodeURIComponent(search)}`);

    if (!response) return;

    tbody.innerHTML = '';

    if (response.data.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="7" class="text-center py-4 text-muted">No se encontraron pacientes.</td></tr>
        `;
        return;
    }

    response.data.forEach(p => {
        const row = tbody.insertRow();

        const estado = p.Habilitado == 1
            ? `<span class="badge bg-success">Habilitado</span>`
            : `<span class="badge bg-danger">Inhabilitado</span>`;

        row.innerHTML = `
            <td>${p.IdPaciente}</td>
            <td>${p.Nombre} ${p.Apellido}</td>
            <td>${p.DNI}</td>
            <td>${p.FechaNac.split(" ")[0]}</td>
            <td>${p.NombreOS}</td>
            <td>${estado}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-primary me-2"
                    onclick='openModal("editar", ${JSON.stringify(p)})'>Editar</button>

                <button class="btn btn-sm btn-danger"
                    onclick='confirmarEliminar(${p.IdPaciente}, "${p.Nombre} ${p.Apellido}")'>
                    Eliminar
                </button>
            </td>
        `;
    });
}

//---------------------------------------------------------
// ELIMINAR
//---------------------------------------------------------
async function confirmarEliminar(id, nombre) {
    if (!confirm(`¿Eliminar al paciente ${nombre} (ID: ${id})?`)) return;

    const response = await fetchAPI(`${API_BASE}/eliminar.php?id=${id}`, {
        method: "DELETE"
    });

    if (response) {
        mostrarMensaje(response.message, true);
        cargarPacientes();
    }
}

//---------------------------------------------------------
// FORM SUBMIT
//---------------------------------------------------------
/**
async function handleFormSubmit(e) {
    e.preventDefault();

    const formData = new FormData(form);
    const data = {};

    formData.forEach((value, key) => {
        data[key] = ['dni', 'telefono', 'habilitado'].includes(key)
            ? parseInt(value)
            : value;
    });

    const tipo = formTypeInput.value;

    const url = tipo === 'crear'
        ? `${API_BASE}/crear.php`
        : `${API_BASE}/editar.php?id=${pacienteIdInput.value}`;

    const method = tipo === 'crear' ? 'POST' : 'PUT';

    const response = await fetchAPI(url, {
        method: method,
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
    });

    if (response) {
        mostrarMensaje(response.message, true);
        pacienteModal.hide();
        cargarPacientes();
    }
} **/

//---------------------------------------------------------
// MODAL
//---------------------------------------------------------
function openModal(tipo, paciente = null) {
    form.reset();
    formTypeInput.value = tipo;

    habilitadoGroup.classList.add('d-none');

    if (tipo === 'crear') {
        modalTitle.textContent = "Crear Nuevo Paciente";
        submitButton.textContent = "Guardar Paciente";

    } else {
        modalTitle.textContent = `Editar Paciente: ${paciente.Nombre} ${paciente.Apellido}`;
        submitButton.textContent = "Actualizar Paciente";

        pacienteIdInput.value = paciente.IdPaciente;
        document.getElementById('nombre').value = paciente.Nombre;
        document.getElementById('apellido').value = paciente.Apellido;
        document.getElementById('dni').value = paciente.DNI;
        document.getElementById('email').value = paciente.Email;
        document.getElementById('telefono').value = paciente.Telefono;
        document.getElementById('fechaNac').value = paciente.FechaNac.split(" ")[0];
        document.getElementById('genero').value = paciente.Genero;
        document.getElementById('estadoCivil').value = paciente.EstadoCivil;
        osSelect.value = paciente.NombreOS;
        document.getElementById('habilitado').value = paciente.Habilitado;

        habilitadoGroup.classList.remove('d-none');
    }

    pacienteModal.show();
}

function closeModal() {
    pacienteModal.hide();
    form.reset();
}

function ObtenerPacientes() {

    const selectPaciente = document.getElementById("paciente");
    const nombreInput = document.getElementById("nombre");
    const apellidoInput = document.getElementById("apellido");
    const telefonoInput = document.getElementById("telefono");
    const emailInput = document.getElementById("email");

    function buscar() {

        const localidad = selectLocalidad.value;

        if (localidad === "") {

            habInput.value = "";
            refInput.value = "";
            return;

        }

        fetch("buscarLocalidad.php?localidad=" + encodeURIComponent(localidad))

            .then(response => response.json())
            .then(data => {

                if (data.existe) {

                    habInput.value = data.cant_habitantes;
                    refInput.value = data.referente;

                } else {

                    habInput.value = "";
                    refInput.value = "";

                }

            })

            .catch(error => {

                console.log(error);

            })

    }

    selectPaciente.addEventListener("change", buscar);

}