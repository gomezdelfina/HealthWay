document.addEventListener("DOMContentLoaded", () => {
    
    const totalCamas = 150;
    const ocupadas = 82;
    const disponibles = totalCamas - ocupadas;
    const solicitudesPendientes = 5;
    const alertasCriticas = 2;

    document.getElementById("internacionesActivas").textContent = ocupadas;
    document.getElementById("camasDisponibles").textContent = `${disponibles} / ${totalCamas}`;
    document.getElementById("solicitudesPendientes").textContent = solicitudesPendientes;
    document.getElementById("alertasCriticas").textContent = alertasCriticas;

    const alertsList = document.getElementById("alertsList");
    const alertas = [
        "Paciente en estado crítico - Habitación 203",
        "Insumos bajos en Unidad Coronaria"
    ];
    alertsList.innerHTML = alertas
        .map(a => `<li class="list-group-item text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>${a}</li>`)
        .join("");

    const solicitudesTable = document.getElementById("solicitudesTable");
    const solicitudes = [
        { id: 1, paciente: "Juan Pérez", estado: "Urgente" },
        { id: 2, paciente: "María López", estado: "Programada" },
        { id: 3, paciente: "Carlos Gómez", estado: "Reprogramada" },
        { id: 4, paciente: "Ana Torres", estado: "Urgente" }
    ];
    solicitudesTable.innerHTML = solicitudes
        .map(s => `
      <tr>
        <td>${s.id}</td>
        <td>${s.paciente}</td>
        <td><span class="badge ${s.estado === "Urgente" ? "bg-danger" : s.estado === "Programada" ? "bg-success" : "bg-warning"}">
          ${s.estado}
        </span></td>
      </tr>
    `).join("");

    const bedsGrid = document.getElementById("bedsGrid");
    const camas = [];
    for (let i = 1; i <= totalCamas; i++) {
        let estado;
        if (i <= ocupadas) estado = "Ocupada";
        else if (i % 10 === 0) estado = "En Limpieza"; // cada 10 camas, una en limpieza
        else estado = "Disponible";
        camas.push({ id: i, estado });
    }

    bedsGrid.innerHTML = camas
        .map(c => `
      <div class="p-2 text-center rounded shadow-sm"
           style="width:60px; font-size:0.8rem; cursor:pointer;
                  background-color: ${c.estado === "Disponible" ? "#198754" : c.estado === "Ocupada" ? "#dc3545" : "#6c757d"};
                  color:white;">
        C${c.id}
      </div>
    `).join("");

    const ctx = document.getElementById("estadoInternacionesChart").getContext("2d");
    const estadosCount = {
        Urgente: solicitudes.filter(s => s.estado === "Urgente").length,
        Programada: solicitudes.filter(s => s.estado === "Programada").length,
        Reprogramada: solicitudes.filter(s => s.estado === "Reprogramada").length
    };

    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Urgente", "Programada", "Reprogramada"],
            datasets: [{
                data: [estadosCount.Urgente, estadosCount.Programada, estadosCount.Reprogramada],
                backgroundColor: ["#dc3545", "#198754", "#ffc107"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        }
    });
});
