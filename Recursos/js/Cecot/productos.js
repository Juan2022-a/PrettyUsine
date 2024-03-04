// Función para mostrar una ventana de confirmación para agregar producto
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        // Obtener valores de los campos
        var nombre = $("#recipient-name").val().trim();
        var precio = $("#recipient-name").val().trim();
        var descripcion = $("#message-text").val().trim();
        var categoria = $("#recipient-name").val().trim();

        // Validar campos antes de mostrar la ventana de confirmación
        if (nombre === '' || precio === '' || descripcion === '' || categoria === '') {
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
            title: '¿Quieres agregar un nuevo producto?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, agregar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí iría el código para agregar el producto si se confirma la acción
                Swal.fire(
                    '¡Agregado!',
                    'El nuevo producto ha sido agregado.',
                    'success'
                );
            }
        });
    });
});
