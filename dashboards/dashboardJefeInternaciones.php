<h1 class="mb-4 dashboard-title">Dashboard - Jefe de Internaciones</h1>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card metric-card shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <h6 class="text-muted">Internaciones Activas</h6>
                <h4 id="internacionesActivas">--</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card metric-card shadow-sm border-start border-success border-4">
            <div class="card-body">
                <h6 class="text-muted">Camas Disponibles</h6>
                <h4 id="camasDisponibles">-- / --</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card metric-card shadow-sm border-start border-warning border-4">
            <div class="card-body">
                <h6 class="text-muted">Solicitudes Pendientes</h6>
                <h4 id="solicitudesPendientes">--</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card metric-card shadow-sm border-start border-danger border-4">
            <div class="card-body">
                <h6 class="text-muted">Alertas Críticas</h6>
                <h4 id="alertasCriticas">--</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-bell-fill me-2"></i> Alertas
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="alertsList"></ul>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-outline-danger btn-sm">Ver Todas</button>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-file-earmark-text me-2"></i> Solicitudes de Internación Activas
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="solicitudesTable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">% Internaciones por Estado</div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="estadoInternacionesChart" style="max-height:220px; width:100%;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-header">Vista rápida de camas</div>
            <div class="card-body">
                <div id="bedsGrid" class="d-flex flex-wrap gap-2" style="min-height:150px"></div>
                <small class="text-muted d-block mt-2">
                    <span class="badge bg-success">Disponible</span>
                    <span class="badge bg-danger">Ocupada</span>
                    <span class="badge bg-secondary">En Limpieza</span>
                </small>
            </div>
        </div>
    </div>
</div>