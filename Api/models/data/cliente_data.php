<?php
// Incluir la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Incluir la clase padre.
require_once('../../models/handler/cliente_handler.php');

/*
 * Clase para manejar el encapsulamiento de los datos de la tabla CLIENTE.
 */
class ClienteData extends ClienteHandler
{
    // Atributo para manejar errores de datos.
    private $data_error = null;

    /*
     * Métodos para validar y asignar valores de los atributos.
     */

    // Método para validar y asignar el ID del cliente.
    public function setId($value)
    {
        // Validar que el ID sea un número natural.
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    // Método para validar y asignar el nombre del cliente.
    public function setNombre($value, $min = 2, $max = 50)
    {
        // Validar que el nombre sea alfabético y tenga una longitud adecuada.
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para validar y asignar el apellido del cliente.
    public function setApellido($value, $min = 2, $max = 50)
    {
        // Validar que el apellido sea alfabético y tenga una longitud adecuada.
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El apellido debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->apellido = $value;
            return true;
        } else {
            $this->data_error = 'El apellido debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Agrega métodos similares para los otros atributos como correo, teléfono, etc.

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }
}
?>
