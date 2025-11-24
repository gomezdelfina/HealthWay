<h1 class="mb-4 dashboard-title">Dashboard de Administracion</h1>
<div class="row mb-4" id="kpiRow">
</div>
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Evolucion de Ocupacion (Ultimos 7 dias)</div>
            <div class="card-body">
                <canvas id="ocupacionChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm mb-4 border-danger border-3">
            <div class="card-header bg-danger text-white"><i class="bi bi-exclamation-triangle-fill me-2"></i>ALERTAS CRITICAS (T.I.)</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="criticalAlertsList"></ul>
                <div class="p-3"><button class="btn btn-danger btn-sm w-100">Ver Todas</button></div>
            </div>
        </div>
    </div>
</div>