// Función para mostrar una ventana de confirmación para agregar categoría
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        // Obtener valores de los campos
        var nombreCategoria = $("#recipient-name").val().trim();
        var descripcionCategoria = $("#message-text").val().trim();

        // Validar campos antes de mostrar la ventana de confirmación
        if (nombreCategoria === '' || descripcionCategoria === '') {
            // Mostrar mensaje de error si algún campo está vacío
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, completa todos los campos.',
            });
            return;
        }

        // Mostrar ventana de confirmación
        Swal.fire({
            title: '¿Quieres agregar una nueva categoría?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, agregar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí iría el código para agregar la categoría si se confirma la acción
                Swal.fire(
                    '¡Agregado!',
                    'La nueva categoría ha sido agregada.',
                    'success'
                );
            }
        });
    })
});
