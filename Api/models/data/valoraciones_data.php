<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/valoraciones_handler.php');

class ValoracionesData extends ValoracionesHandler
{

    private $data_error = null;
    private $filename = null;

    public function setIdValoracion($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->idValoracion = $value;
            return true;
        } else {
            $this->data_error = 'El identificador es incorrecto';
            return false;
        }
    }
    public function setIdProducto($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_producto = $value;
            return true;
        } else {
            $this->data_error = 'El identificador es incorrecto';
            return false;
        }
    }

    public function setComentarioValoracion($value, $min = 2, $max = 250)
    {
        if (!$value) {
            return true;
        } elseif (!Validator::validateString($value)) {
            $this->data_error = 'La descripción contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->comentarioValoracion = $value;
            return true;
        } else {
            $this->data_error = 'La descripción debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setCalificaionValoracion($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->calificacionValoracion = $value;
            return true;
        } else {
            $this->data_error = 'El valor de las existencias debe ser numérico entero';
            return false;
        }
    }
    public function setEstadoValoracion($value)
    {
        if (Validator::validateBoolean($value)) {
            $this->estadoValoracion = $value;
            return true;
        } else {
            $this->data_error = 'Esto no es un booleano';
            return false;
        }
    }

    public  function setFechaRegistro($value)
    {
        $this->fechaValoracion = $value;
        return true;
    }

    public function getDataError()
    {
        return $this->data_error;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setIdDetalle($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->iddetalle = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del detalle es incorrecto';
            return false;
        }
    }

    public function setCantidad($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            $this->data_error = 'La cantidad debe ser un número entero válido';
            return false;
        }
    }

    public function deleteDetail()
    {
        $sql = 'DELETE FROM detalle WHERE id_detalle = ?';
        $params = array($this->iddetalle);
        return Database::executeRow($sql, $params);
    }

    public function createDetail()
{
    // Verificar si todos los datos necesarios están presentes y son válidos
    if (empty($this->id_producto) || empty($this->cantidad)) {
        $this->data_error = 'Datos del producto no válidos';
        return false;
    }

    // Insertar el detalle del producto en el carrito (o en una tabla relacionada)
    $sql = 'INSERT INTO detalle_carrito (id_producto, cantidad, id_usuario) VALUES (?, ?, ?)';
    $params = array($this->id_producto, $this->cantidad, $_SESSION['idUsuario']);

    if (Database::executeRow($sql, $params)) {
        return true;
    } else {
        $this->data_error = 'Error al agregar el producto al carrito';
        return false;
    }
}


}