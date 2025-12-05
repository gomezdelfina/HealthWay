document.addEventListener('DOMContentLoaded', function() {
    
    // Asume que window.API_BASE_URL fue inyectado en solicitud_internacion.php
    const API_BASE_URL = window.API_BASE_URL || '/HTML/Healthway/api/solicitudes';
    const solicitudesTableBody = document.getElementById('solicitudesTableBody');
    const searchInput = document.getElementById('searchInput');
    const solicitudForm = document.getElementById('solicitudForm');
    const submitBtn = document.getElementById('submitBtn');
    const estadoForm = document.getElementById('estadoForm');
    const saveEstadoBtn = document.getElementById('saveEstadoBtn');
    
   
    loadPacientes(); // Cargar la lista de pacientes para el modal de creación
    loadMedicos();   // Cargar la lista de médicos para el modal de creación
    loadSolicitudes(); // Carga la tabla principal de solicitudes

    // Event listener para el envío del formulario de CREACIÓN
    solicitudForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        await crearSolicitud();
    });
    
    // Event listener para el envío del formulario de GESTIÓN DE ESTADO
    estadoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        await cambiarEstadoSolicitud();
    });


    // Event listener para la búsqueda
    let searchTimeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadSolicitudes(searchInput.value);
        }, 300); 
    });


    async function loadPacientes() {
        const selectPaciente = document.getElementById("pacienteId");
        // Nota: Asumo una API endpoint para obtener pacientes
        const url = API_BASE_URL.replace('/solicitudes', '/pacientes') + '/ObtenerPacientes.php?soloActivos=true'; 
        
        try {
            const res = await fetch(url);
            const data = await res.json();
            
            if (data.status === 'success' && data.data) {
                data.data.forEach(paciente => {
                    const option = document.createElement('option');
                    option.value = paciente.IdPaciente;
                    option.textContent = `${paciente.Nombre} ${paciente.Apellido} (${paciente.DNI})`;
                    selectPaciente.appendChild(option);
                });
            } else {
                 console.warn("No se pudo cargar la lista de pacientes.");
            }
        } catch (error) {
            console.error("Error cargando pacientes:", error);
        }
    }

    async function loadMedicos() {
        const selectMedico = document.getElementById("medicoId");
        // Nota: Asumo una API endpoint para obtener medicos
        const url = API_BASE_URL.replace('/solicitudes', '/administrador') + '/ObtenerMedicos.php'; 
        
        try {
            const res = await fetch(url);
            const data = await res.json();
            
            if (data.status === 'success' && data.data) {
                // Primero, limpia las opciones predeterminadas
                selectMedico.innerHTML = '<option value="" disabled selected>Seleccione un médico</option>';
                
                data.data.forEach(medico => {
                    const option = document.createElement('option');
                    option.value = medico.IdUsuario;
                    option.textContent = `${medico.Nombre} ${medico.Apellido}`;
                    selectMedico.appendChild(option);
                });
            } else {
                 console.warn("No se pudo cargar la lista de médicos.");
            }
        } catch (error) {
            console.error("Error cargando médicos:", error);
        }
    }
    
    
    async function crearSolicitud() {
        const datos = new FormData(solicitudForm);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';

        const url = `${API_BASE_URL}/CrearSolicitud.php`; // Endpoint para crear la solicitud

        try {
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });

            const json = await respuesta.json();

            // Limpiar mensajes de error previos
            solicitudForm.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
            solicitudForm.querySelectorAll(".invalid-feedback").forEach(el => el.innerHTML = "");

            if (json.status === "error" && json.errores) {
                Object.entries(json.errores).forEach(([campo, mensaje]) => {
                    const input = document.getElementById(campo);
                    const feedback = input.parentElement.querySelector(".invalid-feedback");
                    
                    if (input && feedback) {
                        input.classList.add("is-invalid");
                        feedback.innerHTML = mensaje;
                    }
                });
                showNotification('Por favor, revise los campos marcados.', 'warning');

            } else if (json.status === "error") {
                showNotification(json.mensaje, 'danger');
                
            } else if (json.status === "success") {
                showNotification('Solicitud de internación creada correctamente.', 'success');
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('solicitudModal'));
                if (modal) modal.hide();
                solicitudForm.reset();
                loadSolicitudes(); // Recargar la tabla
            }
        } catch (error) {
            showNotification('Error de conexión al procesar la solicitud.', 'danger');
            console.error('Error al crear solicitud:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Guardar Solicitud';
        }
    }
    

    window.loadSolicitudes = async function(busqueda = '') {
        solicitudesTableBody.innerHTML = '<tr><td colspan="7" class="text-center"><span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Cargando solicitudes...</td></tr>';
        
        let url = `${API_BASE_URL}/ObtenerSolicitudes.php`;
        if (busqueda) {
            url += `?busqueda=${encodeURIComponent(busqueda)}`;
        }

        try {
            const res = await fetch(url);
            const data = await res.json();

            solicitudesTableBody.innerHTML = ''; // Limpiar

            if (data.status === 'success' && data.data && data.data.length > 0) {
                data.data.forEach(solicitud => renderSolicitudRow(solicitud));
            } else if (busqueda) {
                solicitudesTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No se encontraron solicitudes con ese criterio.</td></tr>';
            } else {
                solicitudesTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay solicitudes registradas.</td></tr>';
            }

        } catch (error) {
            solicitudesTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar las solicitudes.</td></tr>';
            console.error('Error cargando solicitudes:', error);
        }
    }

  
    function renderSolicitudRow(solicitud) {
        const row = document.createElement('tr');
        
       
        // const formatDateTime = (dt) => {
        //     if (!dt) return 'N/A';
        //     const date = new Date(dt);
        //     return date.toLocaleDateString('es-AR') + ' ' + date.toLocaleTimeString('es-AR').substring(0, 5);
        // };

        // Determinar clase de estilo para el estado
        let badgeClass = 'bg-secondary';
        switch (solicitud.Estado) {
            case 'En espera de internación':
                badgeClass = 'bg-warning text-dark';
                break;
            case 'Pendiente':
                badgeClass = 'bg-info';
                break;
            case 'Internado':
                badgeClass = 'bg-success';
                break;
            case 'Cancelada':
                badgeClass = 'bg-danger';
                break;
        }

        row.innerHTML = `
            <td>${solicitud.IdSolicitud}</td>
            <td>${solicitud.NombrePaciente} ${solicitud.ApellidoPaciente}</td>
            <td>${solicitud.NombreMedico} ${solicitud.ApellidoMedico}</td>
            <td>${formatDateTime(solicitud.FechaSolicitud)}</td>
            <td>${solicitud.Motivo.substring(0, 50)}...</td>
            <td><span class="badge ${badgeClass}">${solicitud.Estado}</span></td>
            <td>
                <button class="btn btn-sm btn-warning me-2" title="Gestionar Estado" 
                        onclick="window.openEstadoModal(${solicitud.IdSolicitud}, '${solicitud.Estado}')">
                    <i class="bi bi-gear"></i>
                </button>
                <button class="btn btn-sm btn-danger" title="Eliminar Solicitud">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        solicitudesTableBody.appendChild(row);
    }
    
   
    window.openEstadoModal = function(id, estadoActual) {
        document.getElementById('solicitudId').value = id;
        document.getElementById('nuevoEstado').value = estadoActual;
        const estadoModal = new bootstrap.Modal(document.getElementById('estadoModal'));
        estadoModal.show();
    }
    
    /**
     * Envía la solicitud para cambiar el estado de la solicitud.
     */
    async function cambiarEstadoSolicitud() {
        const id = document.getElementById('solicitudId').value;
        const nuevoEstado = document.getElementById('nuevoEstado').value;
        
        saveEstadoBtn.disabled = true;
        saveEstadoBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        
        const url = `${API_BASE_URL}/ActualizarEstadoSolicitud.php`; // Nuevo endpoint para actualizar estado
        
        const payload = new FormData();
        payload.append('id', id);
        payload.append('nuevoEstado', nuevoEstado);
        
        try {
            const res = await fetch(url, {
                method: 'POST',
                body: payload
            });
            
            const json = await res.json();
            
            if (json.status === 'success') {
                showNotification('Estado de solicitud actualizado correctamente.', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('estadoModal'));
                if (modal) modal.hide();
                loadSolicitudes(); // Recargar la tabla
            } else {
                showNotification(json.mensaje || 'Error al actualizar el estado de la solicitud.', 'danger');
            }
            
        } catch (error) {
            showNotification('Error de conexión al intentar actualizar el estado.', 'danger');
            console.error('Error al actualizar estado:', error);
        } finally {
            saveEstadoBtn.disabled = false;
            saveEstadoBtn.innerHTML = 'Actualizar Estado';
        }
    }


    function showNotification(message, type = 'success') {
       
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'danger' ? 'Error' : (type === 'warning' ? 'Advertencia' : 'Éxito'),
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            console.log(`Notificación (${type}): ${message}`);
        }
    }
    
});