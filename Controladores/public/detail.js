// Constantes para completar la ruta de la API.
const PRODUCTO_API = 'services/public/producto.php';
const PEDIDO_API = 'services/public/pedido.php';
// Constante tipo objeto para obtener los parámetros disponibles en la URL.
const PARAMS = new URLSearchParams(location.search);
// Constante para establecer el formulario de agregar un producto al carrito de compras.
const SHOPPING_FORM = document.getElementById('shoppingForm');
LISTCOMENTARIO = document.getElementById('listComentario'),
IDPRODUCTO=document.getElementById('idProducto');

// Método del eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Detalles del producto';
    // Constante tipo objeto con los datos del producto seleccionado.
    const FORM = new FormData();
    FORM.append('idProducto', PARAMS.get('id'));
    // Petición para solicitar los datos del producto seleccionado.
    const DATA = await fetchData(PRODUCTO_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se colocan los datos en la página web de acuerdo con el producto seleccionado previamente.
        document.getElementById('imagenProducto').src = SERVER_URL.concat('images/productos/', DATA.dataset.imagen_producto);
        document.getElementById('nombreProducto').textContent = DATA.dataset.nombre_producto;
        document.getElementById('descripcionProducto').textContent = DATA.dataset.descripcion_producto;
        document.getElementById('precioProducto').textContent = DATA.dataset.precio_producto;
        document.getElementById('existenciasProducto').textContent = DATA.dataset.existencias_producto;
        document.getElementById('idProducto').value = DATA.dataset.id_producto;
    } else {
        // Se presenta un mensaje de error cuando no existen datos para mostrar.
        document.getElementById('mainTitle').textContent = DATA.error;
        // Se limpia el contenido cuando no hay datos para mostrar.
        document.getElementById('detalle').innerHTML = '';
    }

    
    const FORM2 = new FormData();
    FORM2.append('idProducto', IDPRODUCTO.value);
    // Petición para obtener los datos del registro solicitado.
    const DATA2 = await fetchData(COMENTARIO_API, 'readAllByProducto', FORM2);
    LISTCOMENTARIO.innerHTML = ``;
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA2.status) {
        DATA2.dataset.forEach(row => {
            // Se crea y concatena la fila de la tabla con los datos de cada registro.
            let comentario = `
            <li class="list-group-item" style="border: none;">
            <div class="row">
                <div class="col-md-8">
                    <h5>${row.nombre}</h5>
                </div>
                <div class="col-md-4">
                    <div class="rating rating-${row.id_valoracion}">
                        <input type="radio" id="star-1-${row.id_valoracion}" name="star-radio-${row.id_valoracion}" value="1" data-rating="1">
                        <label for="star-1-${row.id_valoracion}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path pathLength="360" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"></path></svg>
                        </label>
                                <input type="radio" id="star-2-${row.id_valoracion}" name="star-radio-${row.id_valoracion}" value="2" data-rating="2">
                                <label for="star-2-${row.id_valoracion}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path pathLength="360" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"></path></svg>
                                </label>
                                <input type="radio" id="star-3-${row.id_valoracion}" name="star-radio-${row.id_valoracion}" value="3" data-rating="3">
                                <label for="star-3-${row.id_valoracion}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path pathLength="360" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"></path></svg>
                                </label>
                                <input type="radio" id="star-4-${row.id_valoracion}" name="star-radio-${row.id_valoracion}" value="4" data-rating="4">
                                <label for="star-4-${row.id_valoracion}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path pathLength="360" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"></path></svg>
                                </label>
                                <input type="radio" id="star-5-${row.id_valoracion}" name="star-radio-${row.id_valoracion}" value="5" data-rating="5">
                                <label for="star-5-${row.id_valoracion}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path pathLength="360" d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z"></path></svg>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                        <textarea class="form-control" style="resize: none;">${row.comentario_valoracion}</textarea>
                    </div>
                    </div>
                    <p class="mt-2">${row.fecha_valoracion}</p>
                </li>
            `;
            LISTCOMENTARIO.insertAdjacentHTML('beforeend', comentario);
        
            let ratingValue = parseInt(row.calificacion_valoracion);
            let stars = document.querySelectorAll(`.rating-${row.id_valoracion} input[type="radio"]`);
        
            stars.forEach((star, index) => {
                if (index < 6-ratingValue) {
                    star.checked = true;
                } else {
                    star.checked = false;
                }
            });
        });
              
        
        document.querySelectorAll('.rating input[type="radio"], .rating label').forEach(function (element) {
            element.disabled = true;
        });
    } else {
        sweetAlert(4, DATA.error, false);
    }

});

// Método del evento para cuando se envía el formulario de agregar un producto al carrito.
SHOPPING_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SHOPPING_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(PEDIDO_API, 'createDetail', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
    if (DATA.status) {
        sweetAlert(1, DATA.message, false, 'cart.html');
    } else if (DATA.session) {
        sweetAlert(2, DATA.error, false);
    } else {
        sweetAlert(3, DATA.error, true, 'login.html');
    }
});