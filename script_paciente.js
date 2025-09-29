function calcular() {
    const costoPorHora = 5000;
    const cubiertas = parseInt(document.getElementById("cubiertas").textContent);
    const usadas = parseInt(document.getElementById("utilizadas").value);

    let extra = 0;
    let pago = 0;

    if (!isNaN(usadas)) {
        if (usadas > cubiertas) {
            extra = usadas - cubiertas;
            pago = extra * costoPorHora;
        }
        document.getElementById("aPagar").textContent = extra;
        document.getElementById("dinero").textContent = "$" + pago.toLocaleString("es-AR");
    } else {
        // si el campo esta vacío o no es numero
        document.getElementById("aPagar").textContent = "—";
        document.getElementById("dinero").textContent = "—";
    }
}

    // Mostrar/Ocultar tabla de horas
    const btnHoras = document.getElementById("btnHoras");
    const tabla = document.getElementById("tablaInternacion");

    if (btnHoras && tabla) {
        btnHoras.addEventListener("click", () => {
            tabla.classList.toggle("d-none");
        });
    }

    // Mostrar/Ocultar tabla de revisiones
    const btnRevisiones = document.getElementById("btnRevisiones");
    const tablaRevisiones = document.getElementById("tablaRevisiones");
    if (btnRevisiones && tablaRevisiones) {
        btnRevisiones.addEventListener("click", () => {
            tablaRevisiones.classList.toggle("d-none");
        });
    }

    // Botón Calcular
    const btnCalcular = document.getElementById("btnCalcular");
    if (btnCalcular) {
        btnCalcular.addEventListener("click", calcular);

    }
