function prevenirSubmit(event){
    event.preventDefault();
    event.stopPropagation();
}

function resetearForm(form) {
    form.reset();
}

function validarOpSelect(element) {
    if (element.value === '-1') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

function validarValorVacio(element) {
    if (element.value === '') {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

function validarCamposRevision(event) {
    let formIsValid = 0;
    prevenirSubmit(event);

    if(!validarOpSelect(document.getElementById("interPac"))){
        document.getElementById("valPac").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valPac").classList.add("visibility-hidden");
    };
     
    if(!validarOpSelect(document.getElementById("tipoRevis"))){
        document.getElementById("valTipoR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTipoR").classList.add("visibility-hidden");
    };

    if(!validarOpSelect(document.getElementById("estadoRevis"))){
        document.getElementById("valEstR").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valEstR").classList.add("visibility-hidden");
    };

    if(!validarValorVacio(document.getElementById("sintomaRevi"))){
        document.getElementById("valSint").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valSint").classList.add("visibility-hidden");
    };
    
    if(!validarValorVacio(document.getElementById("diagRevi"))){
        document.getElementById("valDiag").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valDiag").classList.add("visibility-hidden");
    };
    
    if(!validarValorVacio(document.getElementById("tratamRevi"))){
        document.getElementById("valTratam").classList.add("visibility-show");
        formIsValid++;
    }else{
        document.getElementById("valTratam").classList.add("visibility-hidden");
    };

    if (formIsValid === 0) {
        //bootstrap.Toast.getOrCreateInstance(document.getElementById('liveToast')).show();
        event.target.submit(); // Esto enviará el formulario de nuevo.
    }
};

function validarCamposRecordatorio(event) {
    
};

function handleRadioChange(id){
    if(id === "opUnaVez"){
        document.getElementById("divFrecDiasRep").classList.add("visibility-remove");
        document.getElementById("divFrecSemRep").classList.add("visibility-remove");
        document.getElementById("divDiasCheck").classList.add("visibility-remove");
    }else if(id === "opDiariamente"){
        document.getElementById("divFrecDiasRep").classList.remove("visibility-remove");

        document.getElementById("divFrecDiasRep").classList.add("visibility-show");
        document.getElementById("divFrecSemRep").classList.add("visibility-remove");
        document.getElementById("divDiasCheck").classList.add("visibility-remove");
    }else if(id === "opSemanalmente"){
        document.getElementById("divFrecSemRep").classList.remove("visibility-remove");
        document.getElementById("divDiasCheck").classList.remove("visibility-remove");

        document.getElementById("divFrecDiasRep").classList.add("visibility-remove");
        document.getElementById("divFrecSemRep").classList.add("visibility-show");
        document.getElementById("divDiasCheck").classList.add("visibility-show");
    }

}

function inicio() {
    /*Revisiones*/
    document.getElementById('revisionForm').addEventListener('submit', validarCamposRevision);
    document.getElementById('btnCancelRevisionForm').addEventListener('click', 
        resetearForm(document.getElementById('revisionForm')));
    
    /*Recordatorios*/
    radios = document.querySelectorAll('input[name="op"]');
    radios.forEach(radio => {
            radio.addEventListener('change', function() {
                handleRadioChange(this.id);
            });
        });
    if(opCheck = document.querySelector('input[name="op"]:checked')){
        handleRadioChange(opCheck.id);
    };

    document.getElementById('recordatorioForm').addEventListener('submit', validarCamposRecordatorio);
    document.getElementById('btnCancelRecordatorioForm').addEventListener('click', 
        resetearForm(document.getElementById('recordatorioForm')));
}

window.onload = inicio;

