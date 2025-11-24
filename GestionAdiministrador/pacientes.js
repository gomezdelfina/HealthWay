const API_URL = './pacientes_api.php';
const modal = document.getElementById('pacienteModal');
const form = document.getElementById('pacienteForm');
const submitButton = document.getElementById('submitButton');
const modalTitle = document.getElementById('modalTitle');
const pacienteIdInput = document.getElementById('pacienteId');
const formTypeInput = document.getElementById('formType');
const osSelect = document.getElementById('nombreOS');
const habilitadoGroup = document.getElementById('habilitadoGroup');

document.addEventListener('DOMContentLoaded', () => {
    cargarPacientes();
    cargarObrasSociales();
    form.addEventListener('submit', handleFormSubmit);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            cargarPacientes();
        }
    });
    modal.addEventListener('click', (e) => {
        if (e.target.id === 'pacienteModal') {
            closeModal();
        }
    });
});

function mostrarMensaje(message, isSuccess) {
    const container = document.getElementById('messageContainer');
    const alertClass = isSuccess ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    const html = `
        <div class="${alertClass} border-l-4 p-4 mb-3 rounded-lg shadow-lg" role="alert">
            <p class="font-bold">${isSuccess ? 'Exito' : 'Error'}</p>
            <p class="text-sm">${message}</p>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    setTimeout(() => {
        const alert = container.lastChild;
        if (alert) alert.remove();
    }, 5000);
}

function ajaxRequest(url, method, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    
    xhr.onload = function() {
        let response;
        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            mostrarMensaje('Respuesta invalida del servidor.', false);
            return;
        }
        
        if (response.success) {
            callback(response);
        } else {
            mostrarMensaje(response.message, false);
        }
    };
    
    xhr.onerror = function() {
        mostrarMensaje('Error de red o servidor al comunicarse con la API.', false);
    };

    if (data) {
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));
    } else {
        xhr.send();
    }
}

function cargarObrasSociales() {
    ajaxRequest(
        `${API_URL}?action=obrasSociales`, 
        'GET', 
        null, 
        (response) => {
            osSelect.innerHTML = '';
            response.data.forEach(os => {
                const option = document.createElement('option');
                option.value = os;
                option.textContent = os;
                osSelect.appendChild(option);
            });
        }
    );
}

function cargarPacientes() {
    const tbody = document.getElementById('pacientesTableBody');
    const searchTerm = document.getElementById('searchInput').value;
    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Buscando...</td></tr>';

    ajaxRequest(
        `${API_URL}?search=${encodeURIComponent(searchTerm)}`, 
        'GET', 
        null, 
        (response) => {
            tbody.innerHTML = '';
            if (response.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-gray-500">No se encontraron pacientes.</td></tr>';
                return;
            }

            response.data.forEach(paciente => {
                const row = tbody.insertRow();
                row.className = 'hover:bg-gray-50';
                
                const estadoTexto = paciente.Habilitado == 1 ? 
                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Habilitado</span>` : 
                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inhabilitado</span>`;

                row.insertCell().textContent = paciente.IdPaciente;
                row.insertCell().textContent = `${paciente.Nombre} ${paciente.Apellido}`;
                row.insertCell().textContent = paciente.DNI;
                row.insertCell().textContent = paciente.FechaNac.split(' ')[0];
                row.insertCell().textContent = paciente.NombreOS;
                row.insertCell().innerHTML = estadoTexto;
                
                const actionsCell = row.insertCell();
                actionsCell.className = 'space-x-2';
                
                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.className = 'text-indigo-600 hover:text-indigo-900 text-sm font-medium';
                editBtn.onclick = () => openModal('editar', paciente);
                actionsCell.appendChild(editBtn);

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Eliminar';
                deleteBtn.className = 'text-red-600 hover:text-red-900 text-sm font-medium';
                deleteBtn.onclick = () => confirmarEliminar(paciente.IdPaciente, `${paciente.Nombre} ${paciente.Apellido}`);
                actionsCell.appendChild(deleteBtn);
            });
        }
    );
}

function confirmarEliminar(id, nombreCompleto) {
    if (window.confirm(`Esta seguro de eliminar al paciente ${nombreCompleto} (ID: ${id})? Esta accion es permanente.`)) {
        ajaxRequest(
            `${API_URL}?id=${id}`, 
            'DELETE', 
            null, 
            (response) => {
                mostrarMensaje(response.message, true);
                cargarPacientes();
            }
        );
    }
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = (key === 'dni' || key === 'telefono' || key === 'habilitado') ? parseInt(value) : value;
    });
    
    const type = formTypeInput.value;
    let url = API_URL;
    let method = '';

    if (type === 'crear') {
        method = 'POST';
    } else if (type === 'editar') {
        method = 'PUT';
        url += `?id=${pacienteIdInput.value}`;
    }

    ajaxRequest(
        url, 
        method, 
        data, 
        (response) => {
            mostrarMensaje(response.message, true);
            closeModal();
            cargarPacientes();
        }
    );
}

function openModal(type, paciente = null) {
    form.reset();
    formTypeInput.value = type;
    habilitadoGroup.classList.add('hidden'); 
    
    if (type === 'crear') {
        modalTitle.textContent = 'Crear Nuevo Paciente';
        submitButton.textContent = 'Guardar Paciente';
    } else if (type === 'editar' && paciente) {
        modalTitle.textContent = `Editar Paciente: ${paciente.Nombre} ${paciente.Apellido}`;
        submitButton.textContent = 'Actualizar Paciente';
        pacienteIdInput.value = paciente.IdPaciente;
        
        document.getElementById('nombre').value = paciente.Nombre;
        document.getElementById('apellido').value = paciente.Apellido;
        document.getElementById('dni').value = paciente.DNI;
        document.getElementById('email').value = paciente.Email;
        document.getElementById('telefono').value = paciente.Telefono;
        document.getElementById('fechaNac').value = paciente.FechaNac.split(' ')[0];
        document.getElementById('genero').value = paciente.Genero;
        document.getElementById('estadoCivil').value = paciente.EstadoCivil;
        
        if (paciente.NombreOS) osSelect.value = paciente.NombreOS;
        document.getElementById('habilitado').value = paciente.Habilitado;
        habilitadoGroup.classList.remove('hidden');
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    form.reset();
}