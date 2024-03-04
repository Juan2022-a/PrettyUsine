// Función para mostrar una ventana de confirmación para agregar administrador
$(document).ready(function () {
    $(".ConfirmarEmployed").click(function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        // Obtener valores de los campos
        var apellido = $("#recipient-name").val().trim();
        var nombre = $("#recipient2-name").val().trim();
        var correo = $("#recipient3-name").val().trim();
        var alias = $("#recipient4-name").val().trim();
        var contraseña = $("#recipient4-name").val().trim();

        // Validar campos antes de mostrar la ventana de confirmación
        if (apellido === '' || nombre === '' || correo === '' || alias === '' || contraseña === '') {
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
                    'El nuevo administrador ha sido agregado.',
                    'success'
                );
            }
        });
    });
});
