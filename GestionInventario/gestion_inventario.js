
const STORAGE_KEY_INV = 'hospitalInventoryData'; 
let hospitalInventory = []; 
let filteredInventory = []; 

const loadInventory = () => {
    const data = localStorage.getItem(STORAGE_KEY_INV);
    if (data) {
        hospitalInventory = JSON.parse(data);
    } else {
     
        hospitalInventory = [
            { id: generateUniqueId(), nombreInsumo: 'Ibuprofeno 400mg', codigo: 'MED-001', lote: 'LOTE2025A', vencimiento: '2025-10-01', stock: 1500, reorden: 500, estado: 'Normal' },
            { id: generateUniqueId(), nombreInsumo: 'Mascarillas N95', codigo: 'EQP-045', lote: 'LOTE2024X', vencimiento: '2024-12-15', stock: 150, reorden: 200, estado: 'Bajo' },
            { id: generateUniqueId(), nombreInsumo: 'Alcohol Gel 70%', codigo: 'MED-012', lote: 'LOTE2026C', vencimiento: '2026-06-20', stock: 45, reorden: 50, estado: 'Critico' }
        ];
        saveInventory();
    }
    applySearchFilter();
    updateKPIs();
};

const saveInventory = () => {
    localStorage.setItem(STORAGE_KEY_INV, JSON.stringify(hospitalInventory));
    applySearchFilter();
    updateKPIs();
};

const generateUniqueId = () => {
    return 'INV-' + Math.random().toString(36).substring(2, 9).toUpperCase();
};



const addInsumo = (event) => {
    event.preventDefault();
    
    const form = document.getElementById('addInventarioForm');
    const nombreInsumo = form.nombreInsumo.value.trim();
    const codigo = form.codigo.value.trim();
    const lote = form.lote.value.trim();
    const vencimiento = form.vencimiento.value;
    const stock = parseInt(form.stock.value, 10);
    const reorden = parseInt(form.reorden.value, 10);
    
    if (isNaN(stock) || isNaN(reorden) || stock < 0 || reorden < 0) {
        alertCustom('Stock y Reorden deben ser numeros positivos.', 'warning');
        return;
    }

    const newInsumo = {
        id: generateUniqueId(),
        nombreInsumo,
        codigo,
        lote,
        vencimiento,
        stock,
        reorden,
        estado: determineEstado(stock, reorden),
    };

    hospitalInventory.push(newInsumo);
    saveInventory();
    
    form.reset();
    const addInventarioModal = bootstrap.Modal.getInstance(document.getElementById('addInventarioModal'));
    if (addInventarioModal) addInventarioModal.hide();
    
    alertCustom('Insumo ' + nombreInsumo + ' anadido con exito.', 'success');
};

const updateInsumo = (event) => {
    event.preventDefault();
    
    const form = document.getElementById('editInventarioForm');
    const id = form.editInsumoId.value;
    const nombreInsumo = form.editNombreInsumo.value.trim();
    const codigo = form.editCodigo.value.trim();
    const lote = form.editLote.value.trim();
    const vencimiento = form.editVencimiento.value;
    const stock = parseInt(form.editStock.value, 10);
    const reorden = parseInt(form.editReorden.value, 10);

    if (isNaN(stock) || isNaN(reorden) || stock < 0 || reorden < 0) {
        alertCustom('Stock y Reorden deben ser numeros positivos.', 'warning');
        return;
    }
    if (!id || !nombreInsumo || !codigo) {
        alertCustom('Error: Faltan datos necesarios para la actualizacion.', 'error');
        return;
    }

    const index = hospitalInventory.findIndex(i => i.id === id);

    if (index !== -1) {
        hospitalInventory[index].nombreInsumo = nombreInsumo;
        hospitalInventory[index].codigo = codigo;
        hospitalInventory[index].lote = lote;
        hospitalInventory[index].vencimiento = vencimiento;
        hospitalInventory[index].stock = stock;
        hospitalInventory[index].reorden = reorden;
        hospitalInventory[index].estado = determineEstado(stock, reorden);
        
        saveInventory();
        
        const editInventarioModal = bootstrap.Modal.getInstance(document.getElementById('editInventarioModal'));
        if (editInventarioModal) editInventarioModal.hide();
        
        alertCustom('Insumo actualizado con exito.', 'success');
    } else {
        alertCustom('Error: Insumo no encontrado.', 'error');
    }
};

const deleteInsumo = (id, name) => {
    confirmCustom('Esta seguro de que desea eliminar el insumo: ' + name + '?', 'Peligro')
        .then(confirmed => {
            if (confirmed) {
                hospitalInventory = hospitalInventory.filter(insumo => insumo.id !== id);
                saveInventory();
                alertCustom('Insumo eliminado.', 'success');
            }
        });
};



const determineEstado = (stock, reorden) => {
    if (stock <= 0) return 'Agotado';
    if (stock <= reorden) return 'Critico';
    if (stock <= reorden * 1.5) return 'Bajo';
    return 'Normal';
};

const getEstadoBadgeClass = (estado) => {
    switch (estado) {
        case 'Normal': return 'bg-success';
        case 'Bajo': return 'bg-warning text-dark';
        case 'Critico': return 'bg-danger';
        case 'Agotado': return 'bg-secondary';
        default: return 'bg-info';
    }
};



const updateKPIs = () => {
    const totalInsumos = hospitalInventory.length;
    const agotados = hospitalInventory.filter(i => i.estado === 'Agotado').length;
    const criticos = hospitalInventory.filter(i => i.estado === 'Critico').length;
    
    const today = new Date();
    const sixtyDaysFromNow = new Date();
    sixtyDaysFromNow.setDate(today.getDate() + 60);
    
    const porVencer = hospitalInventory.filter(i => {
        const vencDate = new Date(i.vencimiento);
        return vencDate >= today && vencDate <= sixtyDaysFromNow;
    }).length;

    const kpiContainer = document.getElementById('inventoryKPIs');
    if (kpiContainer) {
        kpiContainer.innerHTML = `
            <div class="col-md-3 mb-3">
                <div class="card bg-light text-primary shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-box-fill me-2"></i>Total de Insumos</h5>
                        <p class="card-text fs-3">${totalInsumos}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stock Critico/Agotado</h5>
                        <p class="card-text fs-3">${agotados + criticos}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-calendar-x-fill me-2"></i>Por Vencer (60 dias)</h5>
                        <p class="card-text fs-3">${porVencer}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-check-circle-fill me-2"></i>Stock Optimo</h5>
                        <p class="card-text fs-3">${totalInsumos - (agotados + criticos)}</p>
                    </div>
                </div>
            </div>
        `;
    }
};

const applySearchFilter = () => {
    const searchTerm = document.getElementById('inventorySearch').value.toLowerCase();
    
    if (!searchTerm) {
        filteredInventory = hospitalInventory;
    } else {
        filteredInventory = hospitalInventory.filter(insumo => 
            (insumo.nombreInsumo && insumo.nombreInsumo.toLowerCase().includes(searchTerm)) ||
            (insumo.codigo && insumo.codigo.toLowerCase().includes(searchTerm)) ||
            (insumo.lote && insumo.lote.toLowerCase().includes(searchTerm))
        );
    }
    
    // Ordenar por nombre
    filteredInventory.sort((a, b) => (a.nombreInsumo || '').localeCompare(b.nombreInsumo || ''));
    displayInventory(filteredInventory);
};

const displayInventory = (inventoryToDisplay) => {
    const tableBody = document.getElementById('inventoryTableBody');
    tableBody.innerHTML = ''; 

    if (inventoryToDisplay.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-3">No se encontraron insumos.</td></tr>';
        return;
    }

    inventoryToDisplay.forEach(insumo => {
        const estadoBadgeClass = getEstadoBadgeClass(insumo.estado);
        const stockWarningClass = insumo.estado === 'Critico' || insumo.estado === 'Agotado' ? 'table-danger' : '';
        const vencimientoDate = insumo.vencimiento ? new Date(insumo.vencimiento).toLocaleDateString('es-ES') : 'N/A';
        
        const row = document.createElement('tr');
        row.classList.add(stockWarningClass);
        row.innerHTML = `
            <td>${insumo.codigo || 'N/A'}</td>
            <td>${insumo.nombreInsumo || 'N/A'}</td>
            <td>${insumo.lote || 'N/A'}</td>
            <td>${vencimientoDate}</td>
            <td>${insumo.stock}</td>
            <td>${insumo.reorden}</td>
            <td><span class="badge ${estadoBadgeClass}">${insumo.estado}</span></td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-info me-2" title="Editar Lote" onclick="openEditModalInv('${insumo.id}')">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Eliminar Lote" onclick="deleteInsumoHandler('${insumo.id}', '${insumo.nombreInsumo}')">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
};

const openEditModalInv = (id) => {
    const insumo = hospitalInventory.find(i => i.id === id);
    if (!insumo) {
        alertCustom('Error: Insumo con ID ' + id + ' no encontrado.', 'error');
        return;
    }

   
    document.getElementById('editInsumoId').value = insumo.id;
    document.getElementById('editNombreInsumo').value = insumo.nombreInsumo || '';
    document.getElementById('editCodigo').value = insumo.codigo || '';
    document.getElementById('editLote').value = insumo.lote || '';
    document.getElementById('editVencimiento').value = insumo.vencimiento || '';
    document.getElementById('editStock').value = insumo.stock;
    document.getElementById('editReorden').value = insumo.reorden;

    const editModal = new bootstrap.Modal(document.getElementById('editInventarioModal'));
    editModal.show();
};




const setupCustomModal = () => {
    if (document.getElementById('customAlertModal')) return; 

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




window.deleteInsumoHandler = deleteInsumo;
window.openEditModalInv = openEditModalInv; 

document.addEventListener('DOMContentLoaded', () => {
    setupCustomModal();
    
    loadInventory();

    const addInventarioForm = document.getElementById('addInventarioForm');
    if (addInventarioForm) {
        addInventarioForm.addEventListener('submit', addInsumo);
    }
    
    const inventorySearch = document.getElementById('inventorySearch');
    if (inventorySearch) {
        inventorySearch.addEventListener('input', applySearchFilter); 
    }
    
    const editInventarioForm = document.getElementById('editInventarioForm');
    if (editInventarioForm) {
        editInventarioForm.addEventListener('submit', updateInsumo);
    }
});
