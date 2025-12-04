<div class="background mt-3 row">
    <h1 class="mb-4 dashboard-title">Dashboard Personal Medico</h1>
    <div class="row mb-4">
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card metric-card shadow-sm border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h7 class="card-subtitle text-uppercase text-muted">Internaciones activas</h7>
                        </div>
                        <div class="col-2 text-end">
                            <i class="bi bi-hospital fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="card-title metric-value" id="ocupadasValue"></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card metric-card shadow-sm border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h7 class="card-subtitle text-uppercase text-muted">Recordatorios pendientes futuros del día</h7>
                        </div>
                        <div class="col-2 text-end">
                            <i class="bi bi bi-clock me-2 fs-3 text-warning"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="card-title metric-value" id="recPendValue"></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mb-3">
            <div class="card metric-card shadow-sm border-start border-danger border-4 h-100">
                <div class="card-body">
                    <div class="row align-items-center" style="max-height: 300px; overflow-y: auto;">
                        <div class="col-10">
                            <h7 class="card-subtitle text-uppercase text-muted">Recordatorios atrasados del día</h7>
                        </div>
                        <div class="col-2 text-end">
                            <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="card-title metric-value" id="recAtrasValue"></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col">
    <div class="card shadow-sm mb-4 border-warning border-3">
        <div class="card-header bg-warning text-white">
            <i class="bi bi bi-clock me-2"></i> Detalle de recordatorios pendientes futuros del día
        </div>
        <div class="card-body p-0">
            <div class="div-ul" id="recordPendList">
            </div>
            <div class="p-3">
                <a class="text-decoration-none text-light btn btn-warning btn-sm w-100" 
                    href="<?php echo $dirBaseUrl ?>/modules/recordatorios/recordatorios_layout.php">Ver Todos los recordatorios
                </a>
            </div>
        </div>
    </div>
</div>
<div class="col">
    <div class="card shadow-sm mb-4 border-danger border-3">
        <div class="card-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Detalle de recordatorios atrasados del día
        </div>
        <div class="card-body p-0">
            <div class="div-ul" id="recordAtraList">
            </div>
            <div class="p-3">
                <a class="text-decoration-none text-light btn btn-danger btn-sm w-100"
                    href="<?php echo $dirBaseUrl ?>/modules/recordatorios/recordatorios_layout.php">Ver Todos los recordatorios
                </a>
            </div>
        </div>
    </div>
</div>