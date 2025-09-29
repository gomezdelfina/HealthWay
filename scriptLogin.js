//Usuarios
const pswUsers = "1234";

const emailMedicoGuardia = "medicoGuardia@healthway.com";
const emailEnfermera = "enfermera1@healthway.com";
const emailMedicoEsp = "medicoEsp@healthway.com";
const emailAdmin1 = "admin1@healthway.com";
const emailPaciente = "paciente@healthway.com";
const emailJefe = "jefe@healthway.com";

var inputUser = "";
var inputPass = "";

function prevenirSubmit(event){
    event.preventDefault(); // Previene la accion por defecto del evento
    event.stopPropagation(); // Impide que el evento se propague (avance) a las capas superiores en el DOM.
}

function goApp(link, element){
    let elementHref = document.getElementById(element);
    
    elementHref.href = link;
    elementHref.click();
}

function showPassword(){
    let inputPass = document.getElementById("inputPass");

    if(inputPass.type === "password"){
        inputPass.type = "text"
    }else{
        inputPass.type = "password"
    }
}

function validateLoginForm(event){
    inputUser = document.getElementById("inputUser").value;
    inputPass = document.getElementById("inputPass").value;
    let loginForm = document.getElementById("loginForm");
    let invalidUser = document.getElementById("invalidUser");

    if(!loginForm.checkValidity()){
        prevenirSubmit(event);
        loginForm.classList.add('was-validated');// Agrega la clase "was-validated" de BS que da los estilos a los objetos (bordes, colores, iconos) sean validados o no
                                                // teniendo en cuenta el required, pattern, type
    };

    if (inputPass === pswUsers){
        switch(inputUser){
            case "medicoGuardia1": 
                goApp("./DashboardPersMedico.html", "loginBtn");
                loginForm.reset();
                break;
            case "enfermera1": 
                goApp("./DashboardPersMedico.html", "loginBtn");
                loginForm.reset();
                break;
            case "medicoEsp1": 
                goApp("./DashboardPersMedico.html", "loginBtn");
                loginForm.reset();
                break;
            case "admin1":
                goApp("./admin-dashboard.html", "loginBtn");
                loginForm.reset();
                break;
            case "paciente1":
                goApp("./paciente.html", "loginBtn");
                loginForm.reset();
                break;
            case "jefe1":
                goApp("./DashboardJefeInternaciones.html", "loginBtn")
                break;
            default: 
                    prevenirSubmit(event);
                    invalidUser.classList.add("visibility-show");
                    invalidUser.classList.remove("visibility-remove");
        }
    }else{
        prevenirSubmit(event);
        invalidUser.classList.add("visibility-show");
        invalidUser.classList.remove("visibility-remove");
    }
}

function validateRecoveryForm(event){
    let pswRecoveryForm = document.getElementById("pswRecoveryForm");
    let invalidEmail = document.getElementById("invalidEmail");
    let inputEmail = document.getElementById("inputEmail").value;

    if(!pswRecoveryForm.checkValidity()){
        prevenirSubmit(event);
        pswRecoveryForm.classList.add('was-validated');
    };

    if (inputEmail === emailMedicoGuardia ||
        inputEmail === emailEnfermera ||
        inputEmail === emailMedicoEsp ||
        inputEmail === emailAdmin1 ||
        inputEmail === emailPaciente ||
        inputEmail === emailJefe){

        prevenirSubmit(event);
        validEmail.classList.add("visibility-show");
        validEmail.classList.remove("visibility-remove");

        //enviarEmail();
        pswRecoveryForm.reset();
    }else{
        prevenirSubmit(event);
        invalidEmail.classList.add("visibility-show");
        invalidEmail.classList.remove("visibility-remove");
    }
}

window.onload = function() {
    document.getElementById("loginForm").addEventListener("submit", validateLoginForm);
    document.getElementById("pswRecoveryForm").addEventListener("submit", validateRecoveryForm);
    document.getElementById("btnShowPsw").addEventListener("click", showPassword);
};
