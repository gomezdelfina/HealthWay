// -- Inicializacion
document.addEventListener('DOMContentLoaded', function (){ 
    //Evento que ejecuta el login
    document.getElementById("loginForm").addEventListener("submit", validateLoginForm);

    //Evento que ejecuta el envio de email
    document.getElementById("pswRecoveryForm").addEventListener("submit", validateRecoveryForm);

    //Evento que muestra/esconde la contrasena
    document.getElementById("btnShowPsw").addEventListener("click", function() {
        showPassword(document.getElementById("inputPass"));
    });
});

// -- AJAX
//Valida si existe el email en la BD
async function validateEmailExists(email) {
    const baseUrl = window.location.origin;

    try {
        let data = {
            'email': email
        };

        let response = await fetch(baseUrl + '/HealthWay/api/auth/validateEmail.php', {
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

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
};

//Valida el login en la BD (user/clave)
async function validateLogin(usuario) {
    const baseUrl = window.location.origin;

    try {
        let data = {
            'usuario': usuario.user,
            'clave': usuario.psw
        };

        let response = await fetch(baseUrl + '/HealthWay/api/auth/validateLogin.php', {
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

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
};

//Envia email para recuperar contrasenia
async function sendEmail(user) {
    const baseUrl = window.location.origin;

    try {
        let data = {
            'idUsuario': user.IdUsuario,
            'email': user.Email,
            'nameUsuario': user.Nombre,
            'apellidoUsuario': user.Apellido
        };

        let response = await fetch(baseUrl + '/HealthWay/api/auth/sendEmailRecovery.php', {
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

            return result;
        }
    }catch (error){
        throw new Error("Problema de conexión con la API: " + error.message);
    }
};

// -- Validaciones
//Valida campos de formulario Login
async function validateLoginForm(event){
    prevenirSubmit(event);
    let formIsInvalid = 0;
    
    if(!validarValorVacio(document.getElementById("inputUser"))){
        document.getElementById("user-error").textContent = 'El campo usuario no puede ser vacío';
        formIsInvalid++;
    };

    if(!validarValorVacio(document.getElementById("inputPass"))){
        document.getElementById("clave-error").textContent = 'El campo clave no puede ser vacío';
        formIsInvalid++;
    };

    if (formIsInvalid === 0) {
        let usuario = {
            'user': document.getElementById("inputUser").value,
            'psw': document.getElementById("inputPass").value
        }
     
        let divValidUser = document.getElementById("validUser");
        try{
            let result = await validateLogin(usuario);

            if(Array.isArray(result) && result.length > 0){
                event.target.submit();
            } else {
                divValidUser.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Error!</strong> Usuario o clave incorrectos.
                    </div>
                `;
            }           
        }catch(error){
            console.log(error);
        
            divValidUser.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Error!</strong> Usuario o clave incorrectos.
                </div>
            `;
        }
    }
}

//Valida campos de formulario Recovery
async function validateRecoveryForm(event){
    prevenirSubmit(event);

    if(!validarValorVacio(document.getElementById("inputEmail"))){
        document.getElementById("email-error").textContent = 'El campo email no puede ser vacío.';
    }else if (!validarString(document.getElementById("inputEmail"), /[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/)){
        document.getElementById("email-error").textContent = 'El campo email no tiene el formato correcto.';
    }else{
        let divValidEmail = document.getElementById("validEmail");
        
        try{
            let result = await validateEmailExists(document.getElementById("inputEmail").value);

            if(Array.isArray(result) && result.length > 0){
                try{
                    let usuarioEmail = {
                        'IdUsuario': result[0].IdUsuario,
                        'Email': result[0].Email,
                        'Nombre': result[0].Nombre,
                        'Apellido': result[0].Apellido
                    }

                    let resultSendEmail = await sendEmail(usuarioEmail);

                    if(resultSendEmail){
                        divValidEmail.innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Felicidades!</strong> Email enviado correctamente.
                            </div>
                        `;

                        event.target.reset();
                    }else{
                        throw new Error();
                    }
                }catch(error){
                    console.log(error); 
                    divValidEmail.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <strong>Error!</strong> Problemas al enviar el email de recuperacion.
                            </div>
                        `;
                }
            } else {
                divValidEmail.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Error!</strong> El email no existe en el sistema.
                    </div>
                `;
            }
        }catch(error){
            console.log(error);  
            divValidEmail.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <strong>Error!</strong> Problemas al validar email.
                    </div>
                `;
        }
    };
}