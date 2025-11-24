// -- Inicializacion
document.addEventListener('DOMContentLoaded', function (){ 
    //Evento que ejecuta el reseteo de clave
    document.getElementById('resetPasswordForm').addEventListener('submit', validateResetPassForm)

    //Evento que muestra/esconde la contrasena
    document.getElementById("btnShowPswNewPass").addEventListener("click", function() {
        showPassword(document.getElementById("newPass"));
    });
    document.getElementById("btnShowPswConfirmPass").addEventListener("click", function() {
        showPassword(document.getElementById("confirmPass"));
    });
});

// -- AJAX
// Actualiza el campo clave con el nuevo valor
async function resetPassword(pass) {
    const baseUrl = window.location.origin;

    try {
        let response = await fetch(baseUrl + '/HealthWay/api/usuarios/resetPassword.php', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pass),
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
}

// Validación campos de form Reset
async function validateResetPassForm(event){
    prevenirSubmit(event);
    let formIsInvalid = 0;

    if(!validarValorVacio(document.getElementById('newPass'))){
        document.getElementById("newPass-error").textContent = 'El campo nueva contraseña no puede ser vacío.';
        formIsInvalid++;
    };

    if(!validarValorVacio(document.getElementById('confirmPass'))){
        document.getElementById("confirmPass-error").textContent = 'El campo confirmar contraseña no puede ser vacío.';
        formIsInvalid++;
    };

    if(formIsInvalid === 0){
        let pass1 = document.getElementById('newPass').value;
        let pass2 = document.getElementById('confirmPass').value;

        let divValidPass = document.getElementById("validPass");
        let token = document.getElementById("token").value;
        if (pass1 !== pass2) {
            divValidPass.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Error!</strong> Las contaseñas deben coincidir.
                </div>
            `;
        }else{
            try{

                pass = {
                    'token': token,
                    'clave': pass2
                }

                let result = await resetPassword(pass);

                if(result){
                    divValidPass.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Felicitaciones!</strong> Contraseña restaurada con exito.
                        </div>
                    `;
                }else{
                    divValidPass.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Error!</strong> Problemas al reestablecer la contraseña.
                        </div>
                    `;
                }
            }catch(error){
                console.log(error);
                divValidPass.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <strong>Error!</strong> Problemas al reestablecer la contraseña.
                        </div>
                    `;
            }
            
        }
    }
}