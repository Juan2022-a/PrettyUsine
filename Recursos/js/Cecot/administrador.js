// Función para mostrar una ventana de confirmación
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        Swal.fire({
            title: '¿Quieres agregar un nuevo administrador?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, agregar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí iría el código para agregar el administrador si se confirma la acción
                Swal.fire(
                    '¡Agregado!',
                    'El administrador ha sido agregado.',
                    'success'
                );
            }
        });
    })
});
