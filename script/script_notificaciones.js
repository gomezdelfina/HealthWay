document.addEventListener("DOMContentLoaded", () => {

    document.getElementById("btnNotificaciones").addEventListener("click", () => {

        // Primero limpia y coloca mensaje de carga
        document.getElementById("listaNotificaciones").innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border"></div>
                <p class="mt-2">Cargando...</p>
            </div>
        `;

        // Abrimos el modal de inmediato
        const myModal = new bootstrap.Modal(document.getElementById("modalNotificaciones"));
        myModal.show();

        // Pedimos las notificaciones al servidor
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

                // Construimos la lista
                let html = "<ul class='list-group'>";

                data.forEach(n => {
                    html += `
                        <li class="list-group-item">
                            <b>${n.evento}</b><br>
                            ${n.mensaje}<br>
                            <small class="text-muted">${n.fecha}</small>
                        </li>
                    `;
                });

                html += "</ul>";

                document.getElementById("listaNotificaciones").innerHTML = html;
            })
            .catch(e => {
                document.getElementById("listaNotificaciones").innerHTML =
                    "<p class='text-danger'>Error de conexión.</p>";
            });

    });

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

    // Ejecutar cada 10 segundos
    setInterval(actualizarContador, 10000);

    // Ejecutar al cargar la página
    actualizarContador();

})