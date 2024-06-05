const CLIENTE_API = 'services/publica/cliente.php';

const SAVE_FORM = document.getElementById('saveForm');
    
// Método del evento para cuando el documento ha cargado.
document.addEventListener("DOMContentLoaded", async () =>{
    loadTemplate();
    
    getData();
})

SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    // Se verifica la acción a realizar.
    action = 'changePassword';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);

    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(CLIENTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true, 'perfil.html');
        console.log(DATA.message)
    } else {
        sweetAlert(2, DATA.error, false);
        console.log(DATA.message)
    }
});