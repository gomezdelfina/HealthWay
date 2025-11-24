<div class="background mt-3 row">
    <h1 class="mb-4 dashboard-title">Dashboard Personal Medico</h1>
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card metric-card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h7 class="card-subtitle text-uppercase text-muted">Internaciones activas</h7>
                            <h4 class="card-title metric-value" id="ocupadasValue">125</h4>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-hospital fs-3 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card metric-card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h7 class="card-subtitle text-uppercase text-muted">Recordatorios pendientes del día</h7>
                            <h4 class="card-title metric-value" id="tasaOcupacionValue">20</h4>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-bell-fill fs-3 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card metric-card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h7 class="card-subtitle text-uppercase text-muted">Recordatorios atrasados del día</h7>
                            <h4 class="card-title metric-value text-danger" id="alertasActivasValue">3</h4>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-bell-fill fs-3 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col">
    <div class="card shadow-sm mb-4 border-warning border-3">
        <div class="card-header bg-warning text-white">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Recordatorios pendientes
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush" id="recordPendList">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p>Medicion de signos vitales</p>
                    <p>Diego Cesari</p>
                    <span class="badge bg-warning rounded-pill">De rutina</span>
                    <button class="btn btn-success btn-sm">Realizada</button>
                    <button class="btn btn-warning btn-sm">Ver detalle</button>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p>Higienizacion</p>
                    <p>Marcos Pérez</p>
                    <span class="badge bg-warning rounded-pill">De rutina</span>
                    <button class="btn btn-success btn-sm">Realizada</button>
                    <button class="btn btn-warning btn-sm">Ver detalle</button>
                </li>
            </ul>
            <div class="p-3">
                <button class="btn btn-warning btn-sm w-100">Ver Todos los recordatorios</button>
            </div>
        </div>
    </div>
</div>
<div class="col">
    <div class="card shadow-sm mb-4 border-danger border-3">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Recordatorios atrasados
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush" id="recordPendList">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p>Medicion de signos vitales</p>
                    <p>Diego Cesari</p>
                    <span class="badge bg-danger rounded-pill">De rutina</span>
                    <button class="btn btn-success btn-sm">Realizada</button>
                    <button class="btn btn-danger btn-sm">Ver detalle</button>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p>Higienizacion</p>
                    <p>Marcos Pérez</p>
                    <span class="badge bg-danger rounded-pill">De rutina</span>
                    <button class="btn btn-success btn-sm">Realizada</button>
                    <button class="btn btn-danger btn-sm">Ver detalle</button>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p>Alimentacion</p>
                    <p>Ana Gómez</p>
                    <span class="badge bg-danger rounded-pill">De rutina</span>
                    <button class="btn btn-success btn-sm">Realizada</button>
                    <button class="btn btn-danger btn-sm">Ver detalle</button>
                </li>
            </ul>
            <div class="p-3">
                <button class="btn btn-danger btn-sm w-100">Ver Todos los recordatorios</button>
            </div>
        </div>
    </div>
</div>