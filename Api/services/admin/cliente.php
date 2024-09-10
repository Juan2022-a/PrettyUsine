<?php
// Se incluye la clase del modelo.
require_once('../../models/data/cliente_data.php');

// Habilitar la depuración de errores para encontrar problemas
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new ClienteData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);

    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $cliente->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                // Validar los datos de entrada
                if (!isset($_POST['nombreCliente']) || !isset($_POST['correoCliente']) || !isset($_POST['duiCliente']) ||
                    !isset($_POST['telefonoCliente']) || !isset($_POST['direccionCliente']) || !isset($_POST['claveCliente']) ||
                    !isset($_POST['estadoCliente'])) {
                    $result['error'] = 'Faltan parámetros necesarios';
                } else {
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$cliente->setNombre($_POST['nombreCliente']) ||
                        !$cliente->setCorreo($_POST['correoCliente']) ||
                        !$cliente->setDui($_POST['duiCliente']) ||
                        !$cliente->setTelefono($_POST['telefonoCliente']) ||
                        !$cliente->setDireccion($_POST['direccionCliente']) ||
                        !$cliente->setClave($_POST['claveCliente']) ||
                        !$cliente->setEstado($_POST['estadoCliente'])
                    ) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($cliente->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Cliente creado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al crear el cliente';
                    }
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $cliente->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen clientes registrados';
                }
                break;
            case 'readOne':
                if (!isset($_POST['idCliente']) || !$cliente->setId($_POST['idCliente'])) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($result['dataset'] = $cliente->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Cliente inexistente';
                }
                break;
            case 'updateRow':
                // Validar los datos de entrada
                if (!isset($_POST['idCliente']) || !isset($_POST['nombreCliente']) || !isset($_POST['correoCliente']) ||
                    !isset($_POST['duiCliente']) || !isset($_POST['telefonoCliente']) || !isset($_POST['direccionCliente']) ||
                    !isset($_POST['estadoCliente'])) {
                    $result['error'] = 'Faltan parámetros necesarios';
                } else {
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !$cliente->setId($_POST['idCliente']) ||
                        !$cliente->setNombre($_POST['nombreCliente']) ||
                        !$cliente->setCorreos($_POST['correoCliente']) ||
                        !$cliente->setDuiS($_POST['duiCliente']) ||
                        !$cliente->setTelefono($_POST['telefonoCliente']) ||
                        !$cliente->setDireccion($_POST['direccionCliente']) ||
                        !$cliente->setEstado($_POST['estadoCliente'])
                    ) {
                        $result['error'] = $cliente->getDataError();
                    } elseif ($cliente->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Cliente modificado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al modificar el cliente';
                    }
                }
                break;
            case 'deleteRow':
                if (!isset($_POST['idCliente']) || !$cliente->setId($_POST['idCliente'])) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el cliente';
                }
                break;
            default:
                $result['error'] = 'Acción no válida';
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode(['status' => 0, 'error' => 'Acceso denegado']));
    }
} else {
    print(json_encode(['status' => 0, 'error' => 'Recurso no disponible']));
}
?>
