<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla PEDIDO.
 */
class PedidoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $id_cliente = null;
    protected $direccionpedido = null;
    protected $estadoPedido = null;
    protected $fechaRegistro = null;

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/pedidos/';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar registros que coincidan con el valor proporcionado.
    public function searchRows($value)
    {
        $value = '%' . $value . '%';
        $sql = 'SELECT id_pedido, id_cliente, direccion_pedido, estado_pedido, fecha_registro
                FROM pedido
                WHERE nombre_producto LIKE ? OR nombre_cliente LIKE ?
                ORDER BY fecha_registro DESC';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo registro.
    public function createRow()
    {
        $sql = 'INSERT INTO pedido(nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, fecha_registro, imagen_pedido)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->nombreProducto, $this->nombreCliente, $this->direccionCliente, $this->estadoPedido, $this->fechaRegistro, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener todos los registros.
    public function readAll()
    {
        $sql = 'SELECT id_pedido, nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, fecha_registro, imagen_pedido
                FROM pedido
                ORDER BY fecha_registro DESC';
        return Database::getRows($sql);
    }

    // Método para obtener un solo registro por su ID.
    public function readOne()
    {
        $sql = 'SELECT id_pedido, nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, fecha_registro, imagen_pedido
                FROM pedido
                WHERE id_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para obtener el nombre del archivo de imagen de un registro.
    public function readFilename()
    {
        $sql = 'SELECT imagen_pedido
                FROM pedido
                WHERE id_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un registro.
    public function updateRow()
    {
        $sql = 'UPDATE pedido
                SET nombre_producto = ?, nombre_cliente = ?, direccion_cliente = ?, estado_pedido = ?, fecha_registro = ?, imagen_pedido = ?
                WHERE id_pedido = ?';
        $params = array($this->nombreProducto, $this->nombreCliente, $this->direccionCliente, $this->estadoPedido, $this->fechaRegistro, $this->imagen, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un registro.
    public function deleteRow()
    {
        $sql = 'DELETE FROM pedido
                WHERE id_pedido = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>
