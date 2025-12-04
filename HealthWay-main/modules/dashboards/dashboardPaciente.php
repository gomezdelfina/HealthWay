<div class="container-fluid py-4" id="dashboardPacienteContainer">
    <input type="hidden" id="idUser" name="idUser" value="<?php echo $idUser ?>">
    
    <div class="d-flex gap-2 mb-3">
        <button id="btnHoras" type="button" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-clock-history me-2"></i>Horas de internación
        </button>
        <button id="btnRevisiones" type="button" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-clipboard2-pulse me-2"></i>Revisiones
        </button>
    </div>

    <!-- TABLA DE INTERNACIÓN -->
    <div class="card shadow-sm" id="tablaInternacion">
        <div class="card-header text-white card-header-color">
            <i class="bi bi-clock-history me-2"></i>Control de horas de internación
        </div>

        <div class="card-body" id="contenedorInternaciones">
            <p class="text-secondary">Cargando internaciones...</p>
        </div>
    </div>

    <!-- TABLA DE REVISIONES -->
    <div class="card shadow-sm d-none mt-4" id="tablaRevisiones">
        <div class="card-header text-white card-header-color">
            <i class="bi bi-clipboard2-pulse me-2"></i>Revisiones
        </div>
        <div class="card-body">
            <p>Próximamente se mostrarán los datos de revisiones médicas.</p>
        </div>
    </div>
</div>