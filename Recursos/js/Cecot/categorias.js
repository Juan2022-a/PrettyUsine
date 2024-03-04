// Función para mostrar una ventana de confirmación para agregar categoria
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        Swal.fire({
            title: '¿Quieres agregar una nueva categoria?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, agregar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí iría el código para agregar la categoria si se confirma la acción
                Swal.fire(
                    '¡Agregado!',
                    'la nueva categoria ha sido agregada.',
                    'success'
                );
            }
        });
    })
});
