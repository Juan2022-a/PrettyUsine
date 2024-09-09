<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla valoraciones.
 */
class ValoracionesHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $idValoracion = null;
    protected $id_producto = null;
    protected $calificacionValoracion = null;
    protected $comentarioValoracion = null;
    protected $fechaValoracion = null;
    protected $estadoValoracion = null;
    protected $iddetalle = null;
    protected $cantidad = null;

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // CREATE
    public function createComentario()
    {
        $sql = 'INSERT INTO valoracion (id_producto, calificacion_valoracion, comentario_valoracion, fecha_valoracion, estado_valoracion)
                VALUES (?, ?, ?, NOW(), ?)';
        $params = array(
            $this->id_producto,
            $this->calificacionValoracion,
            $this->comentarioValoracion,
            $this->estadoValoracion
        );
        return Database::executeRow($sql, $params);
    }

    // SEARCH
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT v.id_valoracion, p.nombre_producto, p.imagen AS imagen_producto, v.calificacion_valoracion, v.comentario_valoracion, v.fecha_valoracion, v.estado_valoracion
                FROM valoracion v
                INNER JOIN producto p ON v.id_producto = p.id_producto
                WHERE p.nombre_producto LIKE ?
                ORDER BY p.id_producto';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // READ ALL
    public function readAll()
    {
        $sql = 'SELECT v.id_valoracion, p.nombre_producto, p.imagen, v.calificacion_valoracion, v.comentario_valoracion, v.fecha_valoracion, v.estado_valoracion
                FROM valoracion v
                INNER JOIN producto p ON v.id_producto = p.id_producto
                ORDER BY p.nombre_producto;';
        return Database::getRows($sql);
    }

    // READ ONE
    public function readOne()
    {
        $sql = 'SELECT v.id_valoracion, p.nombre_producto, p.imagen AS imagen_producto, v.calificacion_valoracion, v.comentario_valoracion, v.fecha_valoracion, v.estado_valoracion
                FROM valoracion v
                INNER JOIN producto p ON v.id_producto = p.id_producto
                WHERE id_valoracion = ?';
        $params = array($this->idValoracion);
        return Database::getRow($sql, $params);  // Cambié getRows por getRow ya que se espera un solo registro
    }

    // UPDATE
    public function updateRow()
    {
        $sql = 'UPDATE valoracion 
                SET estado_valoracion = ?, comentario_valoracion = ?, calificacion_valoracion = ?
                WHERE id_valoracion = ?';
        $params = array(
            $this->estadoValoracion,
            $this->comentarioValoracion,
            $this->calificacionValoracion,
            $this->idValoracion
        );
        return Database::executeRow($sql, $params);
    }

    // DELETE
    public function deleteRow()
    {
        $sql = 'DELETE FROM valoracion WHERE id_valoracion = ?';
        $params = array($this->idValoracion);
        return Database::executeRow($sql, $params);
    }
}
