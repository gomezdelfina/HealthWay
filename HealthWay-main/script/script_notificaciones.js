document.addEventListener("DOMContentLoaded", () => {

    //Evento de boton Campanita
    document.getElementById("btnNotificaciones").addEventListener("click", () => {

        document.getElementById("listaNotificaciones").innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border"></div>
                <p class="mt-2">Cargando...</p>
            </div>
        `;

        const myModal = new bootstrap.Modal(document.getElementById("modalNotificaciones"));
        myModal.show();

        fetch("/HealthWay/api/notificaciones/getNotificaciones.php")
            .then(res => res.json())
            .then(data => {

                if (!Array.isArray(data)) {
                    document.getElementById("listaNotificaciones").innerHTML =
                        "<p class='text-danger'>Error al leer notificaciones.</p>";
                    return;
                }

                if (data.length === 0) {
                    document.getElementById("listaNotificaciones").innerHTML =
                        "<p class='text-muted'>No hay notificaciones nuevas.</p>";
                    return;
                }

                let html = "<ul class='list-group'>";

                data.forEach(n => {
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>${n.evento}</b>

                            <button class="btn btn-success btnMarcarLeida" data-id="${n.id}">
                                ✓
                            </button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${n.mensaje}
                            <span>${n.fecha}</span>
                        </li>
                    `;
                });

                html += "</ul>";

                document.getElementById("listaNotificaciones").innerHTML = html;

                // 👉 AHORA SÍ ASIGNAMOS LOS EVENTOS (el HTML ya existe)
                document.querySelectorAll(".btnMarcarLeida").forEach(btn => {

                    btn.addEventListener("click", function () {
                        const id = this.dataset.id;

                        fetch("/HealthWay/api/notificaciones/marcarLeida.php?id=" + id)
                            .then(res => res.json())
                            .then(resp => {
                                if (resp.ok) {
                                    this.closest("li").remove();
                                } else {
                                    alert("Error al marcar como leída.");
                                }
                            })
                            .catch(e => alert("Error de conexión"));
                    });

                });

            })
            .catch(e => {
                document.getElementById("listaNotificaciones").innerHTML =
                    "<p class='text-danger'>Error de conexión.</p>";
            });

    });

    

    // -- Ejecutar cada 10 segundos
    // Actualiza contador de notificaciones
    setInterval(actualizarContador, 10000);

    // -- Ejecutar cada 30 segundos
    // Verifica recordatorios
    setInterval(revisarEjecucionRecordatorio, 30000); 

    // Ejecutar al cargar la página
    actualizarContador();
    revisarEjecucionRecordatorio();
})

function actualizarContador() {
    fetch("/HealthWay/api/notificaciones/getNotificaciones.php")
        .then(res => res.json())
        .then(data => {
            const notifCount = document.getElementById("notifCount");

            if (data.length > 0) {
                notifCount.style.display = "inline-block";
                notifCount.textContent = data.length;
            } else {
                notifCount.style.display = "none";
            }
    });
}

async function revisarEjecucionRecordatorio() {
    const baseUrl = window.location.origin;
    try{
        let response = await fetch(baseUrl + '/HealthWay/api/recordatorios/createRecordatoriosNotif.php', {
            method: 'get',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            let result = await response.json(); 

            return result;
        }
    }catch (error){
        console.error('Error en cron:', error);
    }
}

    
    