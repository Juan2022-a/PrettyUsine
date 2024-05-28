const PRODUCTOS_API = 'services/admin/producto.php';
const VALORACIONES_API = 'services/admin/valoraciones.php';

const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');

const INFO_MODAL = new bootstrap.Modal('#infoModal'),
    MODAL_TITLE_INFO = document.getElementById('titleModalInfo');

// BUSCADOR
const SEARCH_FORM = document.getElementById('searchForm');

const SAVE_FORM = document.getElementById('saveForm'),
    ID_VALORACION = document.getElementById('idValoracion'),
    NOMBRE_PRODUCTO = document.getElementById('nombreProductoValoracion'),
    COMENTARIO_VALORACION = document.getElementById('comentarioValoracion'),
    ESTADO_COMENTARIO = document.getElementById('switchComentarios');

//Obtiene el id de la tabla
const PAGINATION_TABLE = document.getElementById('paginationTable');
//Declaramos una variable que permitira guardar la paginacion de la tabla
let PAGINATION;


// Obtenemos el id de la etiqueta img que mostrara la imagen que hemos seleccionado en nuestro input
const IMAGEN = document.getElementById('imagen'),
    IMAGEN_ESTRELLA1 = document.getElementById('imagenE1'),
    IMAGEN_ESTRELLA2 = document.getElementById('imagenE2'),
    IMAGEN_ESTRELLA3 = document.getElementById('imagenE3'),
    IMAGEN_ESTRELLA4 = document.getElementById('imagenE4'),
    IMAGEN_ESTRELLA5 = document.getElementById('imagenE5');


// CUANDO SE CARGUE EL DOCUMENTO
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Gestionar valoraciones';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// CUANDO SE CARGUE EL DOCUMENTO


// Función asincrona para inicializar la instancia de DataTable(Paginacion en las tablas)
const initializeDataTable = async () => {
    PAGINATION = await new DataTable(PAGINATION_TABLE, {
        paging: true,
        searching: true,
        language: spanishLanguage,
        responsive: true
    });



};

SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

const searchRow = async () => {
    //Obtenemos lo que se ha escrito en el input
    const inputValue = SEARCH_INPUT.value;
    // Mandamos lo que se ha escrito y lo convertimos para que sea aceptado como FORM
    const FORM = new FormData();
    FORM.append('search', inputValue);
    //Revisa si el input esta vacio entonces muestra todos los resultados de la tabla
    if (inputValue === '') {
        fillTable();
    } else {
        // En caso que no este vacio, entonces cargara la tabla pero le pasamos el valor que se escribio en el input y se mandara a la funcion FillTable()
        fillTable(FORM);
    }
}

// Función asincrona para reinicializar DataTable después de realizar cambios en la tabla
const resetDataTable = async () => {
    //Revisamos si ya existe una instancia de DataTable ya creada, si es asi se elimina
    if (PAGINATION) {
        PAGINATION.destroy();
    }
    // Espera a que se ejecute completamente la funcion antes de seguir (fillTable llena la tabla con los datos actualizados)
    await fillTable();
    //Espera a que se ejecute completamente la funcion antes de seguir.
    await initializeDataTable();
};

SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);

    const estadoComentario = ESTADO_COMENTARIO.checked ? 1 : 0;

    FORM.set('estadoProducto', estadoComentario);

    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(VALORACIONES_API, 'updateRow', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        INFO_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        //Llamos la funcion que reinicializara DataTable y cargara nuevamente la tabla para visualizar los cambios.
        await resetDataTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idValoracion', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(VALORACIONES_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        INFO_MODAL.show();
        MODAL_TITLE_INFO.textContent = 'Crear el detalle del producto';
        //MODAL_BUTTON.textContent = 'Actualizar '
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const [ROW] = DATA.dataset;
        ID_VALORACION.value = ROW.id_valoracion;
        NOMBRE_PRODUCTO.value = ROW.nombre_producto;
        COMENTARIO_VALORACION.value = ROW.comentario_valoracion;
        //Cargamos la imagen del registro seleccionado
        IMAGEN.src = SERVER_URL + 'images/productos/' + ROW.imagen_producto;
        ESTADO_COMENTARIO.checked = parseInt(ROW.estado_valoracion);

        const notaValoracion = ROW.calificacion_valoracion;
        console.log(notaValoracion)

        switch (notaValoracion) {
            case 1:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrellaBlanca.png'
                break;
            case 2:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrellaBlanca.png'
                break;
            case 3:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrellaBlanca.png'
            case 4:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrellaBlanca.png'
                break;
            case 5:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrella.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrella.png'
                break;
            default:
                IMAGEN_ESTRELLA1.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA2.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA3.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA4.src = '../../api/images/productos/estrellaBlanca.png'
                IMAGEN_ESTRELLA5.src = '../../api/images/productos/estrellaBlanca.png'
                break;
        }

    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const fillTable = async (form = null, option = null) => {
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    (form) ? action = 'searchRows' : action = 'readAll';
    const DATA = await fetchData(VALORACIONES_API, action, form);
    if (DATA.status) {
        DATA.dataset.forEach(row => {
            icon = (parseInt(row.estado_valoracion) === 1) ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
            TABLE_BODY.innerHTML += `
            <tr>
                <td class="align-middle">${row.nombre_producto}</td>
                    <td class="align-middle">${row.fecha_valoracion}</td>
                    <td class="align-middle">${row.calificacion_valoracion}/5</td>
                    <td><i class="${icon}"></i></td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-info me-2 mb-2 mb-sm-2" onclick="openUpdate(${row.id_valoracion})">
                            <i class="bi bi-info-circle"></i>
                        </button>

                    </td>
                </td>
            </tr>
            `
        });
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(3, DATA.error, true);
    }
}

