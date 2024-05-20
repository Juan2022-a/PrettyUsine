<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

class ClienteHandler
{
    // Otras funciones de manejo de clientes...

    // Acción para leer todos los clientes
    public function readAll()
    {
        // Query para seleccionar todos los clientes
        $sql = 'SELECT * FROM cliente';

        // Obtener los datos de la base de datos
        $clientes = Database::getRows($sql);

        // Verificar si se obtuvieron resultados
        if ($clientes) {
            // Mostrar los clientes en una tabla HTML
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Teléfono</th><th>Dirección</th><th>Fecha de Registro</th></tr>';
            foreach ($clientes as $cliente) {
                echo '<tr>';
                echo '<td>' . $cliente['id_cliente'] . '</td>';
                echo '<td>' . $cliente['nombre_cliente'] . '</td>';
                echo '<td>' . $cliente['apellido_cliente'] . '</td>';
                echo '<td>' . $cliente['correo_cliente'] . '</td>';
                echo '<td>' . $cliente['telefono_cliente'] . '</td>';
                echo '<td>' . $cliente['direccion_cliente'] . '</td>';
                echo '<td>' . $cliente['fecha_registro'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No se encontraron clientes.';
        }
    }

    // Otras acciones para manejar clientes...
}

// Procesar la solicitud según la acción proporcionada en la URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

$clienteHandler = new ClienteHandler();

switch ($action) {
    case 'readAll':
        $clienteHandler->readAll();
        break;
    // Otros casos para manejar diferentes acciones...
    default:
        // Acción por defecto si no se proporciona ninguna o si es inválida
        echo 'Acción no válida.';
        break;
}
?>
