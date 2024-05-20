<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

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
                WHERE nombre_Cliente LIKE ? OR apellido_Cliente LIKE ?
                ORDER BY apellido_Cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO cliente(nombre_cliente, apellido_Cliente, dui_Cliente, correo_Cliente, telefono_Cliente, direccion_Cliente, nacimiento_Cliente, clave_Cliente, estado_Cliente, fecha_Registro)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->direccion, $this->nacimiento, $this->clave, $this->estado, $this->fecha_registro);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT * FROM cliente
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT * FROM cliente
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE cliente
                SET nombre_Cliente = ?, apellido_Cliente = ?, dui_Cliente = ?, correo_Cliente = ?, telefono_Cliente = ?, direccion_Cliente = ?, nacimiento_Cliente = ?, clave_Cliente = ?, estado_Cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->dui, $this->correo, $this->telefono, $this->direccion, $this->nacimiento, $this->clave, $this->estado, $this->id);
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
