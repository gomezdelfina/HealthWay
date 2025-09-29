

const STORAGE_KEY = 'hospitalUsersData'; 
let hospitalUsers = []; 
let filteredUsers = []; 

const loadUsers = () => {
    const data = localStorage.getItem(STORAGE_KEY);
    if (data) {
        hospitalUsers = JSON.parse(data);
    } else {
        hospitalUsers = [
            { id: generateUniqueId(), nombreCompleto: 'Dr. Alejandro Soto', username: 'asoto', rol: 'Medico', estado: 'Activo', passwordSet: true }, // Se agrega passwordSet
            { id: generateUniqueId(), nombreCompleto: 'Lic. Carla Gimenez', username: 'cgimenez', rol: 'Enfermero', estado: 'Activo', passwordSet: true }, // Se agrega passwordSet
            { id: generateUniqueId(), nombreCompleto: 'Ing. Laura Diaz', username: 'ldiaz', rol: 'Administrador', estado: 'Inactivo', passwordSet: false }
        ];
        saveUsers();
    }
    applySearchFilter();
};

const saveUsers = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(hospitalUsers));
    applySearchFilter();
};

const generateUniqueId = () => {
    return 'UID-' + Math.random().toString(36).substring(2, 9).toUpperCase();
};


// --- OPERACIONES CRUD LOCALES ---

const addUser = (event) => {
    event.preventDefault();
    
    const form = document.getElementById('addUserForm');
    const nombreCompleto = form.nombreCompleto.value.trim();
    const username = form.username.value.trim();
    const rol = form.rol.value;
    const password = form.password.value; // Capturamos la contrasena
    
    if (rol === 'Seleccione un Rol') {
        alertCustom('Por favor, seleccione un Rol valido.', 'warning');
        return;
    }

    const newUser = {
        id: generateUniqueId(),
        nombreCompleto,
        username,
        rol,
        // Usamos passwordSet para indicar que se establecio una contrasena
        passwordSet: password.length > 0, 
        estado: 'Activo', 
    };

    hospitalUsers.push(newUser);
    saveUsers();
    
    form.reset();
    const addUserModal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    if (addUserModal) addUserModal.hide();
    
    alertCustom('Usuario ' + username + ' anadido con exito.', 'success');
};

const updateUser = (event) => {
    event.preventDefault();
    
    const form = document.getElementById('editUserForm');
    const id = form.editUserId.value;
    const nombreCompleto = form.editNombreCompleto.value.trim();
    const username = form.editUsername.value.trim();
    const rol = form.editRol.value;

    if (!id || !nombreCompleto || !username || !rol) {
        alertCustom('Error: Faltan datos necesarios para la actualizacion.', 'error');
        return;
    }

    const index = hospitalUsers.findIndex(u => u.id === id);

    if (index !== -1) {
        hospitalUsers[index].nombreCompleto = nombreCompleto;
        hospitalUsers[index].username = username;
        hospitalUsers[index].rol = rol;
        
        saveUsers();
        
        const editUserModal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
        if (editUserModal) editUserModal.hide();
        
        alertCustom('Usuario actualizado con exito.', 'success');
    } else {
        alertCustom('Error: Usuario no encontrado.', 'error');
    }
};


const deleteUser = (id, name) => {
    confirmCustom('Esta seguro de que desea eliminar al usuario: ' + name + '?', 'Peligro')
        .then(confirmed => {
            if (confirmed) {
                hospitalUsers = hospitalUsers.filter(user => user.id !== id);
                saveUsers();
                alertCustom('Usuario eliminado.', 'success');
            }
        });
};

const toggleUserStatus = (id, currentStatus, name) => {
    const newStatus = currentStatus === 'Activo' ? 'Inactivo' : 'Activo';
    const confirmMsg = 'Desea cambiar el estado de ' + name + ' a ' + newStatus + '?';
    
    confirmCustom(confirmMsg, 'Confirmar Cambio de Estado')
        .then(confirmed => {
            if (confirmed) {
                const index = hospitalUsers.findIndex(u => u.id === id);
                if (index !== -1) {
                    hospitalUsers[index].estado = newStatus;
                    saveUsers();
                    alertCustom('Estado de ' + name + ' cambiado a ' + newStatus + '.', 'success');
                }
            }
        });
};


// --- FUNCIONES DE INTERFAZ DE USUARIO (UI) ---

const applySearchFilter = () => {
    const searchTerm = document.getElementById('userSearch').value.toLowerCase();
    
    if (!searchTerm) {
        filteredUsers = hospitalUsers;
    } else {
        filteredUsers = hospitalUsers.filter(user => 
            (user.nombreCompleto && user.nombreCompleto.toLowerCase().includes(searchTerm)) ||
            (user.rol && user.rol.toLowerCase().includes(searchTerm)) ||
            (user.username && user.username.toLowerCase().includes(searchTerm)) ||
            (user.id && user.id.toLowerCase().includes(searchTerm))
        );
    }
    
    filteredUsers.sort((a, b) => (a.nombreCompleto || '').localeCompare(b.nombreCompleto || ''));
    displayUsers(filteredUsers);
};

const displayUsers = (usersToDisplay) => {
    const tableBody = document.getElementById('usersTableBody');
    tableBody.innerHTML = ''; 

    if (usersToDisplay.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">No se encontraron usuarios.</td></tr>';
        return;
    }

    usersToDisplay.forEach(user => {
        const estadoBadgeClass = user.estado === 'Activo' ? 'bg-success' : 'bg-danger';
        const toggleIconClass = user.estado === 'Activo' ? 'bi-lock-fill' : 'bi-unlock-fill';
        const toggleBtnClass = user.estado === 'Activo' ? 'btn-outline-danger' : 'btn-outline-success';
        const toggleBtnTooltip = user.estado === 'Activo' ? 'Desactivar Usuario' : 'Activar Usuario';

        // Determinar el contenido de la columna de Contrasena
        const passwordIndicator = user.passwordSet 
            ? '<span class="badge bg-secondary" title="Contrasena establecida">***</span>'
            : '<span class="badge bg-warning text-dark" title="Contrasena temporal no establecida">Pendiente</span>';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="small text-muted">${user.id || 'N/A'}</td>
            <td>${user.nombreCompleto || 'N/A'}</td>
            <td>${user.username || 'N/A'}</td>
            <td>${user.rol || 'N/A'}</td>
            <td>${passwordIndicator}</td> <!-- NUEVA COLUMNA DE INDICADOR DE CONTRASEÑA -->
            <td><span class="badge ${estadoBadgeClass}">${user.estado || 'N/A'}</span></td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-info me-2" title="Editar Usuario" onclick="openEditModal('${user.id}')">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm ${toggleBtnClass} me-2" title="${toggleBtnTooltip}" onclick="toggleUserStatusHandler('${user.id}', '${user.estado}', '${user.nombreCompleto}')">
                    <i class="bi ${toggleIconClass}"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Eliminar Usuario" onclick="deleteUserHandler('${user.id}', '${user.nombreCompleto}')">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
};

const openEditModal = (id) => {
    const user = hospitalUsers.find(u => u.id === id);
    if (!user) {
        alertCustom('Error: Usuario con ID ' + id + ' no encontrado.', 'error');
        return;
    }

    document.getElementById('editUserId').value = user.id;
    document.getElementById('editNombreCompleto').value = user.nombreCompleto || '';
    document.getElementById('editUsername').value = user.username || '';
    document.getElementById('editRol').value = user.rol || 'Medico';

    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editModal.show();
};


// --- DIALOGOS PERSONALIZADOS (MODALS) ---

const setupCustomModal = () => {
    const modalHtml = `
        <div class="modal fade" id="customAlertModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customAlertTitle">Aviso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="customAlertBody"></div>
                    <div class="modal-footer" id="customAlertFooter">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
};

const alertCustom = (message, type = 'info') => {
    const modalElement = document.getElementById('customAlertModal');
    if (!modalElement) {
        console.warn('Modal de aviso no encontrado. Usando console.log.');
        console.log('[ALERTA ' + type.toUpperCase() + ']: ' + message);
        return;
    }
    
    const modal = new bootstrap.Modal(modalElement);
    document.getElementById('customAlertTitle').textContent = type === 'success' ? 'Exito' : (type === 'warning' ? 'Advertencia' : (type === 'error' ? 'Error' : 'Aviso'));
    
    const header = modalElement.querySelector('.modal-header');
    header.className = 'modal-header'; 
    if (type === 'success') header.classList.add('bg-success', 'text-white');
    else if (type === 'error') header.classList.add('bg-danger', 'text-white');
    else if (type === 'warning') header.classList.add('bg-warning', 'text-dark');
    else header.classList.add('bg-light');

    document.getElementById('customAlertBody').innerHTML = message;
    
    modal.show();
    
    setTimeout(() => {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) modalInstance.hide();
    }, 4000);
};

const confirmCustom = (message, title = 'Confirmacion') => {
    return new Promise(resolve => {
        const modalElement = document.getElementById('customAlertModal');
        if (!modalElement) {
            console.error('Modal de confirmacion no encontrado.');
            resolve(false);
            return;
        }
        
        const header = modalElement.querySelector('.modal-header');
        header.className = 'modal-header'; 
        header.classList.add('bg-light'); 

        const modal = new bootstrap.Modal(modalElement);
        document.getElementById('customAlertTitle').textContent = title;
        document.getElementById('customAlertBody').innerHTML = message;
        
        const footer = document.getElementById('customAlertFooter');
        footer.innerHTML = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="confirmCancel">Cancelar</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirmOk">Continuar</button>
        `;
        
        const okBtn = document.getElementById('confirmOk');
        const cancelBtn = document.getElementById('confirmCancel');
        
        okBtn.onclick = () => { modal.hide(); resolve(true); };
        cancelBtn.onclick = () => { modal.hide(); resolve(false); };
        
        modalElement.addEventListener('hidden.bs.modal', function handler() {
            resolve(false);
            modalElement.removeEventListener('hidden.bs.modal', handler);
        }, { once: true });
        
        modal.show();
    });
};


// --- ASIGNACION DE EVENT LISTENERS Y HANDLERS GLOBALES ---

window.deleteUserHandler = deleteUser;
window.toggleUserStatusHandler = toggleUserStatus;
window.openEditModal = openEditModal; 

document.addEventListener('DOMContentLoaded', () => {
    setupCustomModal();
    
    loadUsers();

    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', addUser);
    }
    
    const userSearch = document.getElementById('userSearch');
    if (userSearch) {
        userSearch.addEventListener('input', applySearchFilter); 
    }
    
    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', updateUser);
    }
});
