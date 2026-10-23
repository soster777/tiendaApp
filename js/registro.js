// validacion del formulario de registro del lado del navegador:
// si las dos claves no coinciden no lo deja enviar y marca el campo en rojo.
// (el backend, log/reg.php, lo vuelve a chequear igual por si alguien se saltea esto)
document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("formRegistro");
    const clave = document.getElementById("floatingClave");
    const claveRep = document.getElementById("floatingClaveRep");

    function revisarClaves() {
        if (claveRep.value !== "" && clave.value !== claveRep.value) {
            // is-invalid es la clase de Bootstrap que pinta el campo y muestra el .invalid-feedback
            claveRep.classList.add("is-invalid");
            return false;
        }
        claveRep.classList.remove("is-invalid");
        return true;
    }

    // se revisa mientras escribe, para avisar antes de mandar el formulario
    clave.addEventListener("input", revisarClaves);
    claveRep.addEventListener("input", revisarClaves);

    formulario.addEventListener("submit", function (evento) {
        if (!revisarClaves()) {
            evento.preventDefault(); // corta el envio
            claveRep.focus();
        }
    });
});
