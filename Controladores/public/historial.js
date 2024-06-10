// Constante para completar la ruta de la API.
const PEDIDO_API = 'services/public/pedido.php',
    MODELOTALLAS_API = 'services/public/modelotallas.php',
    COMENTARIO_API = 'services/public/comentario.php',
    DETALLEPEDIDO_API = 'services/public/detallepedido.php';
// Constante para establecer el cuerpo de la tabla.
const TABLE_BODY = document.getElementById('tableBody');

const ID_DETALLE = document.getElementById('idDetalle'),
    IDGUARDAR = document.getElementById('idGuardar');

const SAVE_MODAL2 = new bootstrap.Modal('#saveModal'),
    SAVE_FORM2 = document.getElementById('saveForm'),
    INPUTSEARCH = document.getElementById('inputsearch'),
    MODAL_TITLE2 = document.getElementById('modalTitle'),
    COMENTARIO = document.getElementById('contenidoComentario'),
    FECHA_COMENTARIO = document.getElementById('fechaComentario'),
    DIVSTARS = document.getElementById('divstars');
let timeout_id;

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Historial de compras';
    // Llamada a la función para mostrar los productos del carrito de compras.
    readDetail();
});

// Método del evento para cuando se envía el formulario de agregar un producto al carrito.
SAVE_FORM2.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Obtener el valor de las estrellas seleccionadas
    const selectedStars = document.querySelector('input[name="star-radio"]:checked');
    const starValue = selectedStars ? selectedStars.value : null;

    const FORM = new FormData(SAVE_FORM2);
    // Agregar el valor de las estrellas al FormData
    FORM.append('starValue', 6 - starValue);

    const DATA = await fetchData(COMENTARIO_API, 'createRow', FORM);


    if (DATA.status) {
        SAVE_MODAL2.hide();
        sweetAlert(1, DATA.message, false);
        readDetail();

    } else if (DATA.session) {
        console.log(2);
        sweetAlert(2, DATA.error, false);
    } else {
        console.log(3);
        sweetAlert(3, DATA.error, true);
    }
});

INPUTSEARCH.addEventListener('input', function () {
    clearTimeout(timeout_id);
    timeout_id = setTimeout(async function () {
        readDetail();
    }, 50); // Delay de 50ms
});

/*
*   Función para obtener el detalle del carrito de compras.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
async function readDetail() {
    // Petición para obtener los datos del pedido en proceso.
    const FORM = new FormData();
    FORM.append('valor', INPUTSEARCH.value); //

    const DATA = await fetchData(PEDIDO_API, 'readHistorials', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se inicializa el cuerpo de la tabla.
        TABLE_BODY.innerHTML = '';
        // Se declara e inicializa una variable para calcular el importe por cada producto.
        let subtotal = 0;
        // Se declara e inicializa una variable para sumar cada subtotal y obtener el monto final a pagar.
        let total = 0;
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(async row => {
            subtotal = row.precio_producto * row.cantidad_producto;
            total += subtotal;

            TABLE_BODY.innerHTML += `
<div class="container">
    <div class="row justify-content-center">
        <!-- Comienzo de las tarjetas -->
        <div class="col-8 mb-4">
            <div class="card h-100">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="${SERVER_URL}images/productos/${row.imagen_producto}" class="img-fluid rounded" alt="${row.nombre_producto}" style="max-height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <input type="hidden" id="idModelo" name="idModelo" value="${row.id_producto}">
                            <h5 class="card-title">${row.nombre_producto}</h5>
                            <p class="card-text">
                                <strong>Precio:</strong> $${row.precio_producto}<br>
                                <strong>Cantidad:</strong> ${row.cantidad_producto}<br>
                                <strong>Fecha:</strong> ${row.fecha_registro}<br>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Puedes duplicar este bloque div.col-8 mb-4 para cada tarjeta adicional -->
    </div>
</div>
        `;
        });
        /*document.querySelectorAll('.rating input[type="radio"], .rating label').forEach(function (element) {
            element.disabled = false;
        });*/
    } else {
        sweetAlert(4, DATA.error, false);
    }
}

/*
*   Función para abrir la caja de diálogo con el formulario de cambiar cantidad de producto.
*   Parámetros: id (identificador del producto) y quantity (cantidad actual del producto).
*   Retorno: ninguno.
*/
