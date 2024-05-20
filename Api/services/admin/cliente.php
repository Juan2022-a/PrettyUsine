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
            // Devolver los clientes en formato JSON
            echo json_encode(['status' => true, 'data' => $clientes]);
        } else {
            echo json_encode(['status' => false, 'message' => 'No se encontraron clientes.']);
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
        echo json_encode(['status' => false, 'message' => 'Acción no válida.']);
        break;
}
?>
