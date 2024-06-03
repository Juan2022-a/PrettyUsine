<?php

require_once('../../models/data/valoraciones_data.php');

if (isset($_GET['action'])) {
    session_start();

    $valoraciones = new ValoracionesData;

    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);

    if (isset($_SESSION['idAdministrador']) or true) {
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $valoraciones->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$valoraciones->setIdValoracion($_POST['idValoracion']) or
                    !$valoraciones->setEstadoValoracion(isset($_POST['estadoValoracion']) ? 1 : 0)
                ) {
                    $result['error'] = $valoraciones->getDataError();
                } elseif ($valoraciones->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Comentario actualizada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al actualizar el comentario';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $valoraciones->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen' . count($result['dataset']) . 'registros';
                } else {
                    $result['error'] = 'No existen comentarios registrados';
                }
                break;
            case 'readOne':
                if (!$valoraciones->setIdValoracion($_POST['idValoracion'])) {
                    $result['error'] = $valoraciones->getDataError();
                } elseif ($result['dataset'] = $valoraciones->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Comentario inexistente';
                }
                break;
        }
        // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
        $result['Exception'] = Database::getException();
    }
} else {
    print(json_encode('Recurso no disponible'));
}