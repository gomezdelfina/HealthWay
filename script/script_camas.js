document.addEventListener('DOMContentLoaded', async () => {

    const contenedor = document.getElementById("contenedorCamas");
    const input = document.getElementById('buscarInput');

    let idInternacionActual = null;

    // ================================
    // BUSCADOR
    // ================================
    input.addEventListener('input', async () => {
        const termino = input.value.trim();
        termino === "" ? await cargarCamas() : await buscarCamas(termino);
    });


    // ================================
    // FUNCIÓN BUSCAR CAMAS
    // ================================
    async function buscarCamas(termino) {
        contenedor.innerHTML = '<p class="text-center text-muted">Buscando camas...</p>';

        try {
            const res = await fetch('/HTML/Healthway/api/internaciones/BuscarInternacion.php?busqueda=' + encodeURIComponent(termino));
            const data = await res.json();

            contenedor.innerHTML = '';

            if (!data.length) {
                contenedor.innerHTML = '<p class="text-center text-muted">No se encontraron resultados.</p>';
                return;
            }

            data.forEach(cama => renderizarCama(cama));

        } catch (error) {
            contenedor.innerHTML = '<p class="text-danger text-center">Error al buscar camas.</p>';
        }
    }


    // ================================
    // CARGAR CAMAS (paginado)
    // ================================
    async function cargarCamas(pagina = 1) {
        contenedor.innerHTML = '<p class="text-center text-muted">Cargando camas...</p>';

        try {
            const res = await fetch(`/HTML/Healthway/api/internaciones/ObtenerCamas.php?pagina=${pagina}`);
            const { camas, totalPaginas } = await res.json();
            contenedor.innerHTML = "";

            if (!camas.length) {
                contenedor.innerHTML = '<p class="text-center text-muted">No se encontraron camas.</p>';
                return;
            }

            camas.forEach(cama => renderizarCama(cama));
            renderizarPaginacion(totalPaginas, pagina);

        } catch (err) {
            contenedor.innerHTML = '<p class="text-danger text-center">Error al cargar las camas.</p>';
        }
    }


    // ================================
    // FUNCIÓN PARA RENDERIZAR UNA CAMA
    // ================================
    function renderizarCama(cama) {
        const ocupada = ['Activa', 'Reprogramada', 'Trasladada'].includes(cama.EstadoInternacion);
        const deshabilitada = cama.Habilitada == 0;

        let color = "bg-light";
        let texto = "<span class='text-success fw-semibold'>Disponible</span>";
        let estadoBadge = "";

        if (ocupada) {
            if (cama.EstadoInternacion === "Reprogramada") {
                color = "bg-white border-warning";
                estadoBadge = `<span class="badge bg-warning text-dark">Reprogramada</span>`;
            } else if (cama.EstadoInternacion === "Trasladada") {
                color = "bg-white border-info";
                estadoBadge = `<span class="badge bg-info text-dark">Trasladada</span>`;
            } else {
                color = "bg-white border-success";
                estadoBadge = `<span class="badge bg-success">Activa</span>`;
            }
            texto = `
                <p class="mb-1 fw-semibold text-dark">${cama.NombrePaciente || "Paciente desconocido"}</p>
                <small class="text-muted">Hab: ${cama.NumeroHabitacion} | Cama: ${cama.NumeroCama}</small>
            `;
        } else if (deshabilitada) {
            color = "bg-secondary text-white";
            texto = "<span>No habilitada</span>";
        }

        const div = document.createElement("div");
        div.className = "col-md-2 mb-3";
        div.innerHTML = `
            <div class="card ${color} shadow-sm p-3 border-0 text-center" style="border-radius: 12px;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="small">Cama ${cama.NumeroCama}</strong>
                    ${estadoBadge}
                </div>
                ${texto}
                ${ocupada ? `
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-dark ver-btn"
                                data-id="${cama.IdInternacion}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalInternacion">
                            <i class="bi bi-eye-fill me-1"></i>Ver
                        </button>
                    </div>` : ""}
            </div>
        `;
        contenedor.appendChild(div);
    }


    // ================================
    // PAGINACIÓN
    // ================================
    function renderizarPaginacion(total, actual) {
        const paginacion = document.querySelector(".pagination");
        paginacion.innerHTML = "";

        const crearItem = (pagina, texto, disabled = false, active = false) => `
            <li class="page-item ${disabled ? "disabled" : ""} ${active ? "active" : ""}">
                <a class="page-link" href="#" data-pagina="${pagina}">${texto}</a>
            </li>
        `;

        paginacion.innerHTML += crearItem(actual - 1, "Anterior", actual === 1);

        for (let i = 1; i <= total; i++) {
            paginacion.innerHTML += crearItem(i, i, false, i === actual);
        }

        paginacion.innerHTML += crearItem(actual + 1, "Siguiente", actual === total);

        paginacion.querySelectorAll("a[data-pagina]").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                cargarCamas(parseInt(e.target.dataset.pagina));
            });
        });
    }


    // ============================================================
    // CLICK DENTRO DE CONTENEDOR CAMAS (NO EN TODO EL DOCUMENTO!)
    // ============================================================
    contenedor.addEventListener("click", async e => {

        const btn = e.target.closest(".ver-btn");
        if (!btn) return;

        idInternacionActual = btn.dataset.id;

        const response = await fetch('/HTML/Healthway/api/internaciones/VerInternacion.php?id=' + idInternacionActual);
        const data = await response.json();

        if (data.error) return alert(data.error);

        document.getElementById('interNumber').value = data.IdInternacion || '';
        document.getElementById('nombrePac').value = data.NombrePaciente || '';
        document.getElementById('camaInter').value = data.IdCama || '';
        document.getElementById('habInter').value = data.IdHabitacion || '';
        document.getElementById('estadoInter').value = data.EstadoInternacion || '';
        document.getElementById('fechaInter').value = data.FechaInicio || '';
        document.getElementById('fechaFinInter').value = data.FechaFin || '';
        document.getElementById('notasInter').value = data.Observaciones || '';
    });


    // ================================
    // FINALIZAR INTERNACIÓN
    // ================================
    document.getElementById("finalizar").addEventListener("click", () => {

        if (!idInternacionActual) {
            Swal.fire("Error", "No se pudo obtener la internación.", "error");
            return;
        }

        Swal.fire({
            title: "¿Finalizar internación?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, finalizar",
            cancelButtonText: "Cancelar"
        }).then(result => {

            if (!result.isConfirmed) return;

            fetch(`/HTML/Healthway/api/internaciones/FinalizarInternacion.php?id=${idInternacionActual}`)
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {

                        Swal.fire({
                            icon: "success",
                            title: "Internación finalizada",
                            timer: 1800,
                            showConfirmButton: false
                        });

                        bootstrap.Modal.getInstance(document.getElementById("modalInternacion")).hide();
                        cargarCamas();

                    } else {
                        Swal.fire("Error", data.msg, "error");
                    }
                })
                .catch(err => Swal.fire("Error", err, "error"));
        });
    });


    // ================================
    // MODIFICAR INTERNACIÓN
    // ================================
    document.getElementById("modificar").addEventListener("click", async () => {

        if (!idInternacionActual) {
            Swal.fire("Error", "No se pudo obtener la internación.", "error");
            return;
        }

        // Si el modal Bootstrap está abierto, lo ocultamos para que no interfiera
        const modalEl = document.getElementById("modalInternacion");
        const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        // Hide the bootstrap modal (if shown)
        try { bsModal.hide(); } catch (e) { /* no pasa nada si no estaba abierto */ }

        // Mostrar SweetAlert con inputs
        const result = await Swal.fire({
            title: "Modificar internación",
            html: `
                <div class="col-12">
                    <label class="form-label" for="newEstado">Estado:</label>
                    <input class="form-control" id="newEstado" placeholder="Actualizar estado">
                </div>
                <div class="col-12 mt-2">
                    <label class="form-label" for="observacion">Observaciones:</label>
                    <input class="form-control" id="observacion" placeholder="Ingrese una observación">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            allowOutsideClick: false,
            focusConfirm: false,
            preConfirm: () => ({
                newEstado: document.getElementById("newEstado").value,
                observacion: document.getElementById("observacion").value
            })
        });

        // Si el usuario canceló, podés reabrir el modal original si querés:
        if (!result.isConfirmed) {
            // reabrir modalInternacion si lo deseas
            try { bsModal.show(); } catch (e) { /* ignorar */ }
            return;
        }

        // Enviar cambios al backend
        try {
            const res = await fetch("/HTML/Healthway/api/internaciones/ModificarInternacion.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id: idInternacionActual,
                    newEstado: result.value.newEstado,
                    observacion: result.value.observacion
                })
            });
            const data = await res.json();

            if (data.ok) {
                await Swal.fire({
                    icon: "success",
                    title: "Internación modificada",
                    timer: 1500,
                    showConfirmButton: false
                });
                cargarCamas();
            } else {
                Swal.fire("Error", data.msg, "error");
                // si querés volver a mostrar el modal:
                try { bsModal.show(); } catch (e) {}
            }
        } catch (err) {
            Swal.fire("Error", "Fallo de conexión: " + err, "error");
            try { bsModal.show(); } catch (e) {}
        }
    });


    // ================================
    // CARGA INICIAL
    // ================================
    cargarCamas();
});
