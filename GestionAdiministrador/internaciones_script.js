document.addEventListener('DOMContentLoaded', () => {
    const internacionModalEl = document.getElementById('internacion-modal');
    const internacionModal = new bootstrap.Modal(internacionModalEl);
    const modalTitle = document.getElementById('modal-title');
    const internacionForm = document.getElementById('internacion-form');
    const saveInternacionBtn = document.getElementById('save-internacion-btn');
    const searchInput = document.getElementById('search-input');
    const internacionTableBody = document.getElementById('internacion-table-body');
    const notificationContainer = document.getElementById('notification-container');
    const estadoGroup = document.getElementById('estado-group');
    
    const nroCamaSelect = document.getElementById('nro-cama');
    const usuarioMedicoSelect = document.getElementById('usuario-medico');
    const planOSSelect = document.getElementById('plan-os');
    const estadoInternacionSelect = document.getElementById('estado-internacion');

    let currentMode = 'add'; 
    let editingInternacionId = null;

    function showNotification(message, type = 'success') {
        notificationContainer.innerHTML = '';
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show shadow-lg" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const div = document.createElement('div');
        div.innerHTML = alertHtml;
        notificationContainer.appendChild(div.firstChild);

        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if (alertEl) {
                const alertInstance = bootstrap.Alert.getInstance(alertEl) || new bootstrap.Alert(alertEl);
                if(alertInstance) alertInstance.dispose(); 
            }
            if(div.parentNode) div.parentNode.removeChild(div);
        }, 5000);
    }

    function getStatusBadge(estado) {
        let baseClass = '';
        if (estado === 'En Curso') baseClass = 'bg-success';
        else if (estado === 'Finalizada') baseClass = 'bg-primary';
        else if (estado === 'Cancelada') baseClass = 'bg-secondary';
        else baseClass = 'bg-warning text-dark';
        
        return `<span class="badge ${baseClass}" style="min-width: 80px;">${estado}</span>`;
    }

    function formatDateTime(datetimeString) {
        if (!datetimeString || datetimeString === '0000-00-00 00:00:00') return 'N/A';
        const date = new Date(datetimeString);
        return date.toLocaleDateString('es-ES') + ' ' + date.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});
    }

    async function loadAuxData() {
        const endpoints = {
            camas: 'internaciones_api.php?action=camasDisponibles',
            medicos: 'internaciones_api.php?action=medicos',
            planes: 'internaciones_api.php?action=planes'
        };

        try {
            const [camasRes, medicosRes, planesRes] = await Promise.all([
                fetch(endpoints.camas),
                fetch(endpoints.medicos),
                fetch(endpoints.planes)
            ]);

            const [camasData, medicosData, planesData] = await Promise.all([
                camasRes.json(),
                medicosRes.json(),
                planesRes.json()
            ]);

            if (camasData.success) populateSelect(nroCamaSelect, camasData.data, 'Seleccione Cama');
            else showNotification('Error al cargar camas.', 'danger');

            if (medicosData.success) populateSelect(usuarioMedicoSelect, medicosData.data, 'Seleccione Medico');
            else showNotification('Error al cargar medicos.', 'danger');
            
            if (planesData.success) populateSelect(planOSSelect, planesData.data, 'Seleccione Plan OS');
            else showNotification('Error al cargar planes.', 'danger');

        } catch (error) {
            showNotification('Error de conexion al cargar datos auxiliares para la Internacion.', 'danger');
        }
    }
    
    function populateSelect(selectEl, dataArray, placeholderText) {
        selectEl.innerHTML = `<option value="" disabled selected>${placeholderText}</option>`;
        dataArray.forEach(item => {
            const option = document.createElement('option');
            option.value = item;
            option.textContent = item;
            selectEl.appendChild(option);
        });
    }

    window.openInternacionModal = function(mode, internacionData = {}) {
        currentMode = mode;
        internacionForm.reset();
        
        loadAuxData().then(() => {
            if (mode === 'add') {
                modalTitle.textContent = 'Iniciar Nueva Internacion';
                saveInternacionBtn.textContent = 'Confirmar Internacion';
                
                document.getElementById('dni-paciente').removeAttribute('disabled');
                document.getElementById('nro-cama').removeAttribute('disabled');
                document.getElementById('fecha-inicio').value = new Date().toISOString().slice(0, 16);
                estadoGroup.classList.add('d-none');
                document.getElementById('fecha-inicio').removeAttribute('disabled');

                editingInternacionId = null;
                internacionModal.show();

            } else if (mode === 'edit') {
                modalTitle.textContent = `Actualizar Internacion ID: ${internacionData.IdInternacion}`;
                saveInternacionBtn.textContent = 'Actualizar Estado';
                
                document.getElementById('dni-paciente').setAttribute('disabled', 'disabled');
                document.getElementById('nro-cama').setAttribute('disabled', 'disabled');
                document.getElementById('fecha-inicio').setAttribute('disabled', 'disabled');
                estadoGroup.classList.remove('d-none');
                
                editingInternacionId = internacionData.IdInternacion;
                document.getElementById('internacion-id').value = internacionData.IdInternacion || '';
                document.getElementById('dni-paciente').value = internacionData.PacienteDNI || '';
                document.getElementById('motivo').value = internacionData.Motivo || '';
                document.getElementById('tipo-ingreso').value = internacionData.TipoIngreso || '';
                
                setTimeout(() => {
                    nroCamaSelect.innerHTML += `<option value="${internacionData.NroCama}">${internacionData.NroCama} (Actual)</option>`;
                    nroCamaSelect.value = internacionData.NroCama;

                    usuarioMedicoSelect.value = internacionData.MedicoUsuario;
                    planOSSelect.value = internacionData.NombrePlan;
                    estadoInternacionSelect.value = internacionData.Estado;

                }, 500); 

                internacionModal.show();
            }
        });
    }

    window.closeInternacionModal = function() {
        internacionModal.hide();
        internacionForm.reset();
        editingInternacionId = null;
    }

    window.deleteInternacion = async function(id, paciente) {
        if (!window.confirm(`ATENCION: Esta seguro de que desea ELIMINAR FISICAMENTE la Internacion del paciente ${paciente} (ID ${id})? Esto debe hacerse solo en casos de error.`)) {
            return;
        }
        
        try {
            const response = await fetch(`internaciones_api.php?id=${id}`, {
                method: 'DELETE',
            });
            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                loadInternaciones(); 
            } else {
                showNotification(result.message, 'danger');
            }
        } catch (error) {
            showNotification('Error de conexion con el servidor al intentar eliminar la internacion.', 'danger');
        }
    }

    async function loadInternaciones(searchTerm = '') {
        internacionTableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>`;
        
        try {
            let url = 'internaciones_api.php';
            if (searchTerm) {
                url += `?search=${encodeURIComponent(searchTerm)}`;
            }
            
            const response = await fetch(url);
            const result = await response.json();

            if (result.success && result.data) {
                renderInternacionTable(result.data);
            } else {
                internacionTableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">${result.message || 'Error al cargar las internaciones.'}</td></tr>`;
            }
        } catch (error) {
            internacionTableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-danger">Error de conexion al cargar la lista de internaciones.</td></tr>`;
        }
    }

    function renderInternacionTable(internaciones) {
        internacionTableBody.innerHTML = '';
        if (internaciones.length === 0) {
            internacionTableBody.innerHTML = `<tr><td colspan="8" class="text-center py-4">No se encontraron internaciones.</td></tr>`;
            return;
        }

        internaciones.forEach(i => {
            const row = document.createElement('tr');
            
            const internacionDataString = JSON.stringify(i).replace(/"/g, '&quot;'); 
            const deleteParams = `${i.IdInternacion}, '${i.PacienteNombre} ${i.PacienteApellido}'`;

            const editButton = i.Estado === 'En Curso' ? 
                `<button onclick='window.openInternacionModal("edit", ${internacionDataString})' 
                        class="btn btn-sm btn-outline-warning me-2 shadow-sm"
                        title="Actualizar Estado (Finalizar/Cancelar)">
                    <i class="bi bi-person-fill-lock"></i> Finalizar
                </button>` : 
                `<button class="btn btn-sm btn-outline-success me-2 shadow-sm" disabled>
                    <i class="bi bi-check-circle-fill"></i> Gestion Completa
                </button>`;

            row.innerHTML = `
                <td class="align-middle">${i.IdInternacion}</td>
                <td class="align-middle fw-bold">${i.PacienteNombre} ${i.PacienteApellido} <span class="badge bg-dark">${i.PacienteDNI}</span></td>
                <td class="align-middle"><span class="badge bg-danger">${i.NroCama}</span></td>
                <td class="align-middle">${i.MedicoUsuario}</td>
                <td class="align-middle">${i.NombreOS} / ${i.NombrePlan}</td>
                <td class="align-middle">
                    <small class="text-success fw-bold">Inicio: ${formatDateTime(i.FechaInicio)}</small><br>
                    <small class="text-secondary">Fin: ${formatDateTime(i.FechaFin)}</small>
                </td>
                <td class="align-middle">${getStatusBadge(i.Estado)}</td>
                <td class="text-end align-middle">
                    ${editButton}
                    <button onclick="window.deleteInternacion(${deleteParams})" 
                            class="btn btn-sm btn-outline-secondary"
                            title="Eliminar registro de la BD (Solo para errores).">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </td>
            `;
            internacionTableBody.appendChild(row);
        });
    }

    internacionForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(internacionForm);
        const internacionData = Object.fromEntries(formData.entries());
        
        let url = 'internaciones_api.php';
        let method = '';
        
        if (currentMode === 'add') {
            method = 'POST';
        } else if (currentMode === 'edit') {
            method = 'PUT';
            url += `?id=${editingInternacionId}`;
            
            internacionData.estado = estadoInternacionSelect.value;
            
            const dataToSend = { estado: internacionData.estado };
            Object.assign(internacionData, dataToSend);
        }
        
        saveInternacionBtn.disabled = true; 
        saveInternacionBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(internacionData)
            });
            
            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                window.closeInternacionModal();
                loadInternaciones(); 
            } else {
                showNotification(result.message, 'danger');
            }
        } catch (error) {
            showNotification('Error de conexion al procesar la solicitud. Verifique que el backend este activo.', 'danger');
        } finally {
            saveInternacionBtn.disabled = false;
            saveInternacionBtn.innerHTML = (currentMode === 'add') ? 'Confirmar Internacion' : 'Actualizar Estado';
        }
    });

    let searchTimeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadInternaciones(searchInput.value);
        }, 300);
    });

    loadInternaciones();
});