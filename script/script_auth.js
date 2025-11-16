function prevenirSubmit(event){
    event.preventDefault(); // Previene la accion por defecto del evento
    event.stopPropagation(); // Impide que el evento se propague (avance) a las capas superiores en el DOM.
}

async function getEmail(element) {

    try {
        let data = {
            'email': element.value
        };

        let response = await fetch('api/usuarios/getUsuarioByEmail.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            errorText = await response.text();
            throw new Error(`Error HTTP: ${response.status} - ${errorText}`);
        }else{      
            result = await response.json(); 

            if (result.length > 0) {
                return true;
            } else {
                return false;
            }
        }
    }catch (error){
        console.error("Error al validar el email:", error);
        return false;
    }
};

function showPassword(){
    let inputPass = document.getElementById("inputPass");

    if(inputPass.type === "password"){
        inputPass.type = "text"
    }else{
        inputPass.type = "password"
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

function validarString(element, regexp) {
    regex = regexp;

    if (!regex.test(element.value.trim())) {
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

async function validarEmailGuardado(element) {

    existeEmail = await getEmail(element);
    
    if(!existeEmail){
        element.classList.add("is-invalid");
        return false;
    } else {
        element.classList.remove("is-invalid");
        return true;
    }
}

function validateLoginForm(event){
    let formIsValid = 0;
    
    if(!validarValorVacio(document.getElementById("inputUser"))){
        document.getElementById("user-error").textContent = 'El campo usuario no puede ser vacío';
        formIsValid++;
    };

    if(!validarValorVacio(document.getElementById("inputPass"))){
        document.getElementById("clave-error").textContent = 'El campo clave no puede ser vacío';
        formIsValid++;
    };

    if (formIsValid > 0) {
        prevenirSubmit(event);
    }
}

async function validateRecoveryForm(event){
    let formIsValid = 0;
    prevenirSubmit(event);

    if(!validarValorVacio(document.getElementById("inputEmail"))){
        document.getElementById("email-error").textContent = 'El campo email no puede ser vacío';
        formIsValid++;
    }else if (!validarString(document.getElementById("inputEmail"), /[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{3,}$/)){
        document.getElementById("email-error").textContent = 'El campo email no tiene el formato correcto';
        formIsValid++;
    }else{
        emailExist = await validarEmailGuardado(document.getElementById("inputEmail"));
        
        if(!emailExist){
            document.getElementById("email-error").textContent = 'El email no se encuentra cargado en el sistema';
            formIsValid++;
        }
    };

    if (formIsValid == 0) {
        document.getElementById("validEmail").classList.add('visibility-show');
        document.getElementById("validEmail").classList.remove('visibility-remove');
        event.currentTarget.reset();
    }
}

window.onload = function() {
    document.getElementById("loginForm").addEventListener("submit", validateLoginForm);
    document.getElementById("pswRecoveryForm").addEventListener("submit", validateRecoveryForm);
    document.getElementById("btnShowPsw").addEventListener("click", showPassword);
};
