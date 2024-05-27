<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 * Clase para manejar el comportamiento de los datos de la tabla CLIENTE.
 */
class ClienteHandler
{
    /*
     * Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $telefono = null;
    protected $dui = null;
    protected $direccion = null;
    protected $nacimiento = null;
    protected $clave = null;
    protected $estado = null;
    protected $fecha_registro = null;

    /*
     * Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT * FROM cliente
                WHERE nombre_cliente LIKE ? OR apellido_cliente LIKE ?
                ORDER BY apellido_Cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO cliente(nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, direccion_cliente, nacimiento_cliente, clave_cliente, estado_cliente, fecha_Registro)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->direccion, $this->nacimiento, $this->clave, $this->estado, $this->fecha_registro);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, direccion_cliente, nacimiento_cliente, clave_cliente, estado_cliente, fecha_registro 
        FROM cliente
        ORDER BY fecha_registro DESC';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, dui_cliente, correo_cliente, telefono_cliente, direccion_cliente, nacimiento_cliente,  estado_cliente
        FROM cliente where id_cliente =?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE cliente
        SET nombre_cliente = ?,
            apellido_cliente = ?,
            dui_cliente = ?,
            correo_cliente = ?,
            telefono_cliente = ?,
            direccion_cliente = ?,
            nacimiento_cliente = ?,
            estado_cliente = ?
        WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->direccion, $this->nacimiento, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM cliente
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>