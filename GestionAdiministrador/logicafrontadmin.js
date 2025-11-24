



document.addEventListener('DOMContentLoaded', () => {
    const userModalEl = document.getElementById('user-modal');
    const userModal = new bootstrap.Modal(userModalEl);
    const modalTitle = document.getElementById('modal-title');
    const userForm = document.getElementById('user-form');
    const saveUserBtn = document.getElementById('save-user-btn');
    const passwordGroup = document.getElementById('password-group');
    const searchInput = document.getElementById('search-input');
    const userTableBody = document.getElementById('user-table-body');
    const notificationContainer = document.getElementById('notification-container');
    const userRoleSelect = document.getElementById('user-role');
    const userPasswordInput = document.getElementById('user-password');
    const habilitadoGroup = document.getElementById('habilitado-group');

    let currentMode = 'add'; // 'add' o 'edit'
    let editingUserId = null;

    /**
     * Muestra una notificacion (Alerta de Bootstrap)
     * @param {string} message - El mensaje a mostrar.
     * @param {string} type - 'success', 'danger', 'warning', 'info'.
     */
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

        // Remover automaticamente despues de 5 segundos
        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if (alertEl) {
                // Intenta usar la API de Bootstrap para cerrar
                const alertInstance = bootstrap.Alert.getInstance(alertEl) || new bootstrap.Alert(alertEl);
                if(alertInstance) alertInstance.dispose(); // Usar dispose para eliminar completamente
            }
            if(div.parentNode) div.parentNode.removeChild(div);
        }, 5000);
    }

    /**
     * Devuelve la clase de Badge de Bootstrap segun el rol y estado.
     * @param {string} role - Rol del usuario.
     * @param {number} habilitado - 1 (activo) o 0 (inactivo).
     * @returns {string} HTML del Badge.
     */
    function getRoleBadge(role, habilitado) {
        let baseClass = '';
        if (role === 'Administrador') baseClass = 'bg-danger';
        else if (role === 'Medico') baseClass = 'bg-primary';
        else if (role === 'Enfermero') baseClass = 'bg-info';
        else if (role === 'Personal') baseClass = 'bg-success';
        else baseClass = 'bg-secondary';
        
        const statusClass = habilitado == 0 ? 'bg-warning text-dark' : baseClass;
        const statusText = habilitado == 0 ? `${role} (Inactivo)` : role;

        return `<span class="badge ${statusClass}">${statusText}</span>`;
    }


    async function loadRoles() {
        try {
            const response = await fetch('usuarios_api.php?action=roles');
            const result = await response.json();

            if (result.success && result.data) {
                userRoleSelect.innerHTML = '<option value="" disabled selected>Seleccione un Rol</option>'; // Opcion por defecto
                result.data.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role;
                    option.textContent = role;
                    userRoleSelect.appendChild(option);
                });
            } else {
                showNotification('Error al cargar la lista de roles: ' + (result.message || 'Desconocido'), 'danger');
            }
        } catch (error) {
            showNotification('Error de conexion al servidor al cargar roles.', 'danger');
            console.error('Error al cargar roles:', error);
        }
    }


    /**
     * Abre el modal y configura el modo (Alta o Edicion).
     * SE HACE GLOBAL (window.) PARA SER LLAMADA DESDE EL onclick DEL HTML.
     * @param {string} mode - 'add' o 'edit'.
     * @param {object} userData - Datos del usuario a editar (opcional).
     */
    window.openUserModal = function(mode, userData = {}) {
        currentMode = mode;
        userForm.reset();
        
        // Limpiar validaciones previas
        Array.from(userForm.querySelectorAll('.is-invalid')).forEach(el => el.classList.remove('is-invalid'));
        
        // Cargar roles antes de mostrar el modal
        loadRoles().then(() => {
            if (mode === 'add') {
                modalTitle.textContent = 'Anadir Nuevo Usuario';
                saveUserBtn.textContent = 'Guardar Usuario';
                
                // Modo Alta: Mostrar campo de contrasena, hacerlo requerido
                passwordGroup.classList.remove('d-none');
                userPasswordInput.setAttribute('required', 'required');
                habilitadoGroup.classList.add('d-none'); // Ocultar estado en alta
                
                // Mostrar etiqueta estandar
                document.getElementById('password-label').innerHTML = 'Contrasena <span class="text-danger">*</span>';
                
                editingUserId = null;
                userModal.show();
            } else if (mode === 'edit') {
                modalTitle.textContent = `Editar Usuario: ${userData.Nombre} ${userData.Apellido}`;
                saveUserBtn.textContent = 'Actualizar Usuario';
                
                // Modo Edicion: Mantener campo de contrasena visible, pero no requerido
                passwordGroup.classList.remove('d-none'); 
                userPasswordInput.removeAttribute('required');
                habilitadoGroup.classList.remove('d-none'); 

                // Mostrar etiqueta para cambio de clave opcional
                document.getElementById('password-label').innerHTML = 'Contrasena (dejar vacio para no cambiar)';
                
                // Cargar datos del usuario
                editingUserId = userData.IdUsuario;
                document.getElementById('user-id').value = userData.IdUsuario || '';
                document.getElementById('user-name').value = userData.Nombre || '';
                document.getElementById('user-lastname').value = userData.Apellido || '';
                document.getElementById('user-username').value = userData.Usuario || '';
                document.getElementById('user-email').value = userData.Email || '';
                document.getElementById('user-phone').value = userData.Telefono || '';
                
               
                setTimeout(() => {
                    if (userRoleSelect.querySelector(`option[value="${userData.DescRol}"]`)) {
                        userRoleSelect.value = userData.DescRol;
                    }
                }, 100);
                
                document.getElementById('user-habilitado').checked = userData.Habilitado == 1;
                
                userModal.show();
            }
        });
    }

    
    window.closeUserModal = function() {
        userModal.hide();
        userForm.reset();
        editingUserId = null;
       
        document.getElementById('password-label').innerHTML = 'Contrasena <span class="text-danger">*</span>';
        userPasswordInput.setAttribute('required', 'required'); // Restaurar requerido por si se abre en modo 'add'
    }

    /**
     * Envia la solicitud DELETE (Baja Logica) al backend.
     * SE HACE GLOBAL (window.) PARA SER LLAMADA DESDE EL onclick DEL HTML.
     * @param {number} id - ID del usuario a deshabilitar.
     */
    window.deleteUser = async function(id, name, lastname) {
        if (!window.confirm(`Esta seguro de que desea deshabilitar (Baja Logica) al usuario ${name} ${lastname} (ID ${id})? Su estado pasara a Inactivo.`)) {
            return;
        }
        
        try {
            // Utilizamos el metodo DELETE con el ID en el query string para la Baja Logica
            const response = await fetch(`usuarios_api.php?id=${id}`, {
                method: 'DELETE',
            });
            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                loadUsers(); // Recargar la tabla via AJAX
            } else {
                showNotification(result.message, 'danger');
            }
        } catch (error) {
            showNotification('Error de conexion con el servidor al intentar deshabilitar el usuario.', 'danger');
            console.error('Error DELETE:', error);
        }
    }


    /**
     * Carga y renderiza la tabla de usuarios via AJAX.
     * @param {string} searchTerm - Termino de busqueda opcional.
     */
    async function loadUsers(searchTerm = '') {
        userTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>`;
        
        try {
            let url = 'usuarios_api.php';
            if (searchTerm) {
                // Busqueda se envia como parametro GET
                url += `?search=${encodeURIComponent(searchTerm)}`;
            }
            
            const response = await fetch(url);
            const result = await response.json();

            if (result.success && result.data) {
                renderUserTable(result.data);
            } else {
                userTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">${result.message || 'Error al cargar los usuarios.'}</td></tr>`;
            }
        } catch (error) {
            userTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger">Error de conexion al cargar la lista de usuarios.</td></tr>`;
            console.error('Error GET:', error);
        }
    }

    /**
     * Renderiza las filas de la tabla.
     * @param {Array<object>} users - Array de objetos de usuario.
     */
    function renderUserTable(users) {
        userTableBody.innerHTML = '';
        if (users.length === 0) {
            userTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4">No se encontraron usuarios que coincidan con la busqueda.</td></tr>`;
            return;
        }

        users.forEach(user => {
            const row = document.createElement('tr');
            row.id = `user-${user.IdUsuario}`;
            
            // Se usa &quot; para evitar problemas de comillas en el atributo onclick
            const userDataString = JSON.stringify({
                IdUsuario: user.IdUsuario,
                Nombre: user.Nombre,
                Apellido: user.Apellido,
                Usuario: user.Usuario,
                Email: user.Email,
                Telefono: user.Telefono,
                DescRol: user.DescRol,
                Habilitado: user.Habilitado
            }).replace(/"/g, '&quot;'); 
            
            // Parametros para la Baja Logica
            const deleteParams = `${user.IdUsuario}, '${user.Nombre.replace(/'/g, "\\'")}', '${user.Apellido.replace(/'/g, "\\'")}'`;

            // Boton de Baja Logica solo si esta Habilitado
            const deleteButton = user.Habilitado == 1 ? 
                `<button onclick="window.deleteUser(${deleteParams})" 
                        class="btn btn-sm btn-outline-danger shadow-sm"
                        title="Baja Logica: Cambia el estado a Inactivo.">
                    <i class="bi bi-person-slash"></i> Deshabilitar
                </button>` : 
                `<button class="btn btn-sm btn-secondary shadow-sm" disabled title="Usuario ya inactivo.">
                    <i class="bi bi-person-x"></i> Inactivo
                </button>`;


            row.innerHTML = `
                <td class="align-middle">${user.IdUsuario}</td>
                <td class="align-middle">${user.Nombre} ${user.Apellido}</td>
                <td class="align-middle">${user.Usuario}</td>
                <td class="align-middle">${user.Email}</td>
                <td class="align-middle">${user.Telefono || 'N/A'}</td>
                <td class="align-middle">${getRoleBadge(user.DescRol, user.Habilitado)}</td>
                <td class="text-end align-middle">
                    <button onclick='window.openUserModal("edit", ${userDataString})' 
                            class="btn btn-sm btn-outline-primary me-2 shadow-sm">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                    ${deleteButton}
                </td>
            `;
            userTableBody.appendChild(row);
        });
    }

    userForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(userForm);
        const userData = Object.fromEntries(formData.entries());
        
        let url = 'usuarios_api.php';
        let method = '';
        
        // Alta o Edicion
        if (currentMode === 'add') {
            method = 'POST';
            userData.habilitado = 1; // Alta: siempre habilitado
        } else if (currentMode === 'edit') {
            method = 'PUT';
            url += `?id=${editingUserId}`;
            
            // Recoger el estado de habilitado del checkbox
            userData.habilitado = document.getElementById('user-habilitado').checked ? 1 : 0;
            
            // Si el campo de contrasena esta vacio en edicion, NO lo enviamos al backend.
            if (!userData.password) {
                delete userData.password;
            }
        }
        
        // El campo de telefono es opcional, 
        if (!userData.phone) {
            userData.phone = ''; // PHP lo manejara como NULL o cadena vacia
        }

    
        if (!userData.name || !userData.lastname || !userData.username || !userData.email || !userData.role || (currentMode === 'add' && !userData.password)) {
            showNotification('Por favor, complete todos los campos obligatorios (*).', 'warning');
            return;
        }
        
        saveUserBtn.disabled = true; // Deshabilitar boton durante el envio
        saveUserBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
        
        try {
            const response = await fetch(url, {
                method: method,
                // POST y PUT envian datos en el cuerpo como JSON
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            });
            
            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                window.closeUserModal();
                loadUsers(); // Recargar la tabla via AJAX
            } else {
                showNotification(result.message, 'danger');
            }
        } catch (error) {
            showNotification('Error de conexion al procesar la solicitud. Verifique que el backend (usuarios_api.php) este activo y la conexion a la BD.', 'danger');
            console.error('Error CRUD:', error);
        } finally {
            saveUserBtn.disabled = false;
            saveUserBtn.innerHTML = (currentMode === 'add') ? 'Guardar Usuario' : 'Actualizar Usuario';
        }
    });

    // Manejador del campo de busqueda con un pequeno retraso (debounce)
    let searchTimeout = null;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsers(searchInput.value);
        }, 300); // Esperar 300ms despues de que el usuario deje de escribir
    });

    // Cargar los usuarios al iniciar la pagina
    loadUsers();
});