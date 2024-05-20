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
    public function setIdCliente($value)
    {
        // Validar que el ID sea un número natural.
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del cliente es incorrecto';
            return false;
        }
    }

    // Método para validar y asignar el nombre del cliente.
    public function setNombreCliente($value, $min = 2, $max = 50)
    {
        // Validar que el nombre sea alfabético y tenga una longitud adecuada.
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para validar y asignar el apellido del cliente.
    public function setApellidoCliente($value, $min = 2, $max = 50)
    {
        // Validar que el apellido sea alfabético y tenga una longitud adecuada.
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El apellido debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->apellido_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El apellido debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para validar y asignar el correo del cliente.
    public function setCorreoCliente($value)
    {
        // Validar que el correo sea válido.
        if (!Validator::validateEmail($value)) {
            $this->data_error = 'El correo electrónico no es válido';
            return false;
        } else {
            $this->correo_cliente = $value;
            return true;
        }
    }

    // Método para validar y asignar el teléfono del cliente.
    public function setTelefonoCliente($value)
    {
        // Validar que el teléfono sea válido.
        if (!Validator::validatePhoneNumber($value)) {
            $this->data_error = 'El teléfono no es válido';
            return false;
        } else {
            $this->telefono_cliente = $value;
            return true;
        }
    }

    // Método para validar y asignar el DUI del cliente.
    public function setDuiCliente($value)
    {
        // Validar que el DUI sea válido.
        if (!Validator::validateDUI($value)) {
            $this->data_error = 'El DUI no es válido';
            return false;
        } else {
            $this->dui_cliente = $value;
            return true;
        }
    }

    // Método para validar y asignar la dirección del cliente.
    public function setDireccionCliente($value, $min = 2, $max = 250)
    {
        // Validar que la dirección tenga una longitud adecuada.
        if (Validator::validateLength($value, $min, $max)) {
            $this->direccion_cliente = $value;
            return true;
        } else {
            $this->data_error = 'La dirección debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para validar y asignar la fecha de nacimiento del cliente.
    public function setNacimientoCliente($value)
    {
        // Validar que la fecha de nacimiento sea válida.
        if (!Validator::validateDate($value)) {
            $this->data_error = 'La fecha de nacimiento no es válida';
            return false;
        } else {
            $this->nacimiento_cliente = $value;
            return true;
        }
    }

    // Método para validar y asignar la clave del cliente.
    public function setClaveCliente($value, $min = 8, $max = 100)
    {
        // Validar que la clave tenga una longitud adecuada.
        if (Validator::validateLength($value,
        $value, $min, $max)) {
            $this->clave_cliente = $value;
            return true;
        } else {
            $this->data_error = 'La clave debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para validar y asignar el estado del cliente.
    public function setEstadoCliente($value)
    {
        // Validar que el estado sea un número natural.
        if (Validator::validateNaturalNumber($value)) {
            $this->estado_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El estado del cliente es incorrecto';
            return false;
        }
    }

    // Método para validar y asignar la fecha de registro del cliente.
    public function setFechaRegistroCliente($value)
    {
        // Validar que la fecha de registro sea válida.
        if (!Validator::validateDate($value)) {
            $this->data_error = 'La fecha de registro no es válida';
            return false;
        } else {
            $this->fecha_registro_cliente = $value;
            return true;
        }
    }

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }
}
