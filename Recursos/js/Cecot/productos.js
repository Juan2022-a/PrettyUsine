// Función para mostrar una ventana de confirmación para agregar un producto
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

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
                // Aquí iría el código para agregar producto si se confirma la acción
                Swal.fire(
                    '¡Agregado!',
                    'El producto ha sido agregado.',
                    'success'
                );
            }
        });
    })
});
