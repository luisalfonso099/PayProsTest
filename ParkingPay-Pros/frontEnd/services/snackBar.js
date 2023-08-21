export const showSnackbar =(message, isSuccess)=> {
    const snackbar = document.getElementById("snackbar");
    snackbar.innerText = message;

    if (isSuccess) {
        snackbar.style.backgroundColor = "#4CAF50";
    } else {
        snackbar.style.backgroundColor = "#f44336";
    }

    snackbar.classList.add("show");
    setTimeout(() => {
        snackbar.classList.remove("show");
    }, 3000); // Ocultar después de 3 segundos
}