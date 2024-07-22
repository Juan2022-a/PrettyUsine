<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla PEDIDO.
 */
class PedidoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id_pedido = null;
    protected $imagenproducto = null;
    protected $nombreproducto = null;
    protected $nombrecliente = null;
    protected $direccioncliente = null;
    protected $fecharegistro = null;
    protected $estadopedido = null;
    protected $id_detalle = null;
    protected $cliente = null;
    protected $producto = null;
    protected $cantidad = null;

    const ESTADOS = array(array('Pendiente', 'Pendiente'), array('Finalizado', 'Finalizado'), array('Entregado', 'Entregado'), array('Anulado', 'Anulado'));

    // Constante para establecer la ruta de las imágenes.
    const RUTA_IMAGEN = '../../images/pedidos/';

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar registros que coincidan con el valor proporcionado.
    public function searchRows($value)
    {
        $value = '%' . $value . '%';
        $sql = 'SELECT id_pedido, nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, fecha_registro, imagen_pedido
                FROM pedido
                WHERE nombre_producto LIKE ? OR nombre_cliente LIKE ?
                ORDER BY fecha_registro DESC';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // // Método para crear un nuevo registro.
    // public function createRow()
    // {
    //     $sql = 'INSERT INTO pedido(nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, fecha_registro, imagen_pedido)
    //             VALUES(?, ?, ?, ?, ?, ?)';
    //     $params = array($this->nombreProducto, $this->nombreCliente, $this->direccionCliente, $this->estadoPedido, $this->fechaRegistro, $this->imagen);
    //     return Database::executeRow($sql, $params);
    // }

    // Método para obtener todos los registros.
    public function readAll()
    {
        $sql = 'SELECT 
	    PD.id_pedido,
        C.nombre_cliente,
        C.direccion_cliente,
        P.fecha_registro,        
        PD.estado_pedido
    FROM 
        detalle_pedido DP
    JOIN 
        producto P ON DP.id_producto = P.id_producto
    JOIN 
        pedido PD ON DP.id_pedido = PD.id_pedido
    JOIN 
        cliente C ON PD.id_cliente = C.id_cliente';
        return Database::getRows($sql);
    }

    // Método para obtener un solo registro por su ID.
    public function readOne()
    {
        $sql = 'SELECT id_pedido, nombre_producto, nombre_cliente, direccion_cliente, estado_pedido, pedido.fecha_registro
        FROM pedido
        inner JOIN detalle_pedido  using (id_pedido)
        inner join cliente using (id_cliente)
        inner JOIN producto  using (id_producto)
        WHERE id_pedido = ?';
        $params = array($this->id_pedido);
        return Database::getRow($sql, $params);
    }

    // Método para obtener el nombre del archivo de imagen de un registro.
    public function readFilename()
    {
        $sql = 'SELECT imagen_pedido
                FROM pedido
                WHERE id_pedido = ?';
        $params = array($this->id_pedido);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un registro.
    public function updateRow()
    {
        $sql = 'UPDATE pedido
                SET imagen_pedido = ?, nombre_producto = ?, nombre_cliente = ?, direccion_cliente = ?, estado_pedido = ?, fecha_registro = ?
                WHERE id_pedido = ?';
        $params = array($this->nombreproducto, $this->nombrecliente, $this->direccioncliente, $this->estadopedido, $this->fecharegistro, $this->imagenproducto, $this->id_pedido);
        return Database::executeRow($sql, $params);
    }

    public function getOrder()
    {
        $this->estadopedido = 'Pendiente';
        $sql = 'SELECT id_pedido
                FROM pedido
                WHERE estado_pedido = ? AND id_cliente = ?';
        $params = array($this->estadopedido, $_SESSION['idCliente']);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['idPedido'] = $data['id_pedido'];
            return true;
        } else {
            return false;
        }
    }

    // Método para iniciar un pedido en proceso.
    public function startOrder()
    {
        if ($this->getOrder()) {
            return true;
        } else {
            $sql = 'INSERT INTO pedido(direccion_pedido, id_cliente)
                    VALUES((SELECT direccion_cliente FROM cliente WHERE id_cliente = ?), ?)';
            $params = array($_SESSION['idCliente'], $_SESSION['idCliente']);
            // Se obtiene el ultimo valor insertado de la llave primaria en la tabla pedido.
            if ($_SESSION['idPedido'] = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Método para agregar un producto al carrito de compras.
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO detalle_pedido(id_producto, precio_producto, cantidad_producto, id_pedido)
                VALUES(?, (SELECT precio_producto FROM producto WHERE id_producto = ?), ?, ?)';
        $params = array($this->producto, $this->producto, $this->cantidad, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener los productos que se encuentran en el carrito de compras.
    public function readDetail()
    {
        $sql = 'SELECT id_detalle, nombre_producto, detalle_pedido.precio_producto, detalle_pedido.cantidad_producto
                FROM detalle_pedido
                INNER JOIN pedido USING(id_pedido)
                INNER JOIN producto USING(id_producto)
                WHERE id_pedido = ?';
        $params = array($_SESSION['idPedido']);
        return Database::getRows($sql, $params);
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finishOrder()
    {
        $this->estadopedido = 'Finalizado';
        $sql = 'UPDATE pedido
                SET estado_pedido = ?
                WHERE id_pedido = ?';
        $params = array($this->estadopedido, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    public function readHistorials()
    {
        $sql = 'SELECT 
        p.nombre_producto,
        p.imagen_producto,
        dp.cantidad_producto,
        dp.precio_producto,
        pe.fecha_registro,
        c.direccion_cliente
    FROM 
        detalle_pedido dp
    INNER JOIN 
        pedido pe ON dp.id_pedido = pe.id_pedido
    INNER JOIN 
        producto p ON dp.id_producto = p.id_producto
    INNER JOIN
        cliente c ON pe.id_cliente = c.id_cliente
    WHERE 
        pe.estado_pedido = "Finalizado" AND
        c.id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        return Database::getRows($sql, $params);
    }



    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        $sql = 'UPDATE detalle_pedido
                SET cantidad_producto = ?
                WHERE id_detalle = ? AND id_pedido = ?';
        $params = array($this->cantidad, $this->id_detalle, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM detalle_pedido
                WHERE id_detalle = ? AND id_pedido = ?';
        $params = array($this->id_detalle, $_SESSION['idPedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un registro.
    /* public function deleteRow()
     {
         $sql = 'DELETE FROM pedido
         WHERE id_pedido = ?';
         $params = array($this->id);
         return Database::executeRow($sql, $params);
     }*/
}
?>