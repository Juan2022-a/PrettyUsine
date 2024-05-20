// Constante para completar la ruta de la API de clientes.
const CLIENTE_API = 'services/admin/cliente.php';
// Constante para establecer el formulario de búsqueda.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal de clientes.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar cliente.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_CLIENTE = document.getElementById('idCliente'),
    NOMBRE_CLIENTE = document.getElementById('nombreCliente'),
    APELLIDO_CLIENTE = document.getElementById('apellidoCliente'),
    DUI_CLIENTE = document.getElementById('duiCliente'),
    CORREO_CLIENTE = document.getElementById('correoCliente'),
    TELEFONO_CLIENTE = document.getElementById('telefonoCliente'),
    DIRECCION_CLIENTE = document.getElementById('direccionCliente'),
    NACIMIENTO_CLIENTE = document.getElementById('nacimientoCliente'),
    ESTADO_CLIENTE = document.getElementById('estadoCliente'),
    CLAVE_CLIENTE = document.getElementById('claveCliente');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar clientes';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Método del evento para cuando se envía el formulario de búsqueda.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método del evento para cuando se envía el formulario de guardar cliente.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_CLIENTE.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(CLIENTE_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los clientes disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los clientes disponibles.
    const DATA = await fetchData(CLIENTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de clientes fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada cliente.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombreCliente}</td>
                    <td>${row.apellidoCliente}</td>
                    <td>${row.duiCliente}</td>
                    <td>${row.correoCliente}</td>
                    <td>${row.telefonoCliente}</td>
                    <td>${row.direccionCliente}</td>
                    <td>${row.nacimientoCliente}</td>
                    <td>${row.estadoCliente}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.idCliente})">
                            <i class="bi bi-pencil-fill"></i>
                        </
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.idCliente})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
            });
            // Se muestra un mensaje de acuerdo con el resultado.
            ROWS_FOUND.textContent = DATA.message;
        } else {
            sweetAlert(4, DATA.error, true);
        }
    }
    
    /*
    *   Función para preparar el formulario al momento de insertar un cliente.
    *   Parámetros: ninguno.
    *   Retorno: ninguno.
    */
    const openCreate = () => {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Crear cliente';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        CLAVE_CLIENTE.disabled = false;
        CONFIRMAR_CLAVE.disabled = false;
    }
    
    /*
    *   Función asíncrona para preparar el formulario al momento de actualizar un cliente.
    *   Parámetros: id (identificador del cliente seleccionado).
    *   Retorno: ninguno.
    */
    const openUpdate = async (id) => {
        // Se define una constante tipo objeto con los datos del cliente seleccionado.
        const FORM = new FormData();
        FORM.append('idCliente', id);
        // Petición para obtener los datos del cliente solicitado.
        const DATA = await fetchData(CLIENTE_API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra la caja de diálogo con su título.
            SAVE_MODAL.show();
            MODAL_TITLE.textContent = 'Actualizar cliente';
            // Se prepara el formulario.
            SAVE_FORM.reset();
            CLAVE_CLIENTE.disabled = true;
            CONFIRMAR_CLAVE.disabled = true;
            // Se inicializan los campos con los datos.
            const ROW = DATA.dataset;
            ID_CLIENTE.value = ROW.idCliente;
            NOMBRE_CLIENTE.value = ROW.nombreCliente;
            APELLIDO_CLIENTE.value = ROW.apellidoCliente;
            DUI_CLIENTE.value = ROW.duiCliente;
            CORREO_CLIENTE.value = ROW.correoCliente;
            TELEFONO_CLIENTE.value = ROW.telefonoCliente;
            DIRECCION_CLIENTE.value = ROW.direccionCliente;
            NACIMIENTO_CLIENTE.value = ROW.nacimientoCliente;
            ESTADO_CLIENTE.value = ROW.estadoCliente;
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
    
    /*
    *   Función asíncrona para eliminar un cliente.
    *   Parámetros: id (identificador del cliente seleccionado).
    *   Retorno: ninguno.*/
    /*
    *   Función asíncrona para eliminar un cliente.
    *   Parámetros: id (identificador del cliente seleccionado).
    *   Retorno: ninguno.
    */
    const openDelete = async (id) => {
        // Se muestra un mensaje de confirmación antes de eliminar el cliente.
        const confirmation = confirm('¿Estás seguro de que deseas eliminar este cliente?');
        // Si el usuario confirma la eliminación, se procede con la petición para eliminar el cliente.
        if (confirmation) {
            // Se define una constante tipo objeto con los datos del cliente a eliminar.
            const FORM = new FormData();
            FORM.append('idCliente', id);
            // Petición para eliminar el cliente.
            const DATA = await fetchData(CLIENTE_API, 'deleteRow', FORM);
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            if (DATA.status) {
                // Se muestra un mensaje de éxito.
                sweetAlert(1, DATA.message, true);
                // Se recarga la tabla para reflejar los cambios.
                fillTable();
            } else {
                sweetAlert(2, DATA.error, false);
            }
        }
    }
    