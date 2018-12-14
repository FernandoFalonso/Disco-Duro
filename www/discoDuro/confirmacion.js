window.addEventListener("load", inicio);

function inicio() {
    for ($i = 0; $i < document.getElementsByClassName("borr").length; $i++) {
        document.getElementsByClassName("borr")[$i].addEventListener("click", confirmar, false);
    }
}

function confirmar(e) {
    if (confirm("¿Desea eliminar?")) {
        return true;
    } else {
        e.preventDefault();
        return false;
    }
}