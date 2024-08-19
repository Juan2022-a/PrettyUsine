<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla CLIENTE.
*/
class ClienteHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;    
    protected $correo = null;
    protected $telefono = null;
    protected $dui = null;    
    protected $direccion = null;
    protected $clave = null;
    protected $estado = null;

    /*
    *   Métodos para gestionar la cuenta del cliente.
    */
    public function checkUser($mail, $password)
    {
        $sql = 'SELECT id_cliente, correo_cliente, clave_cliente, estado_cliente
                FROM cliente
                WHERE correo_cliente = ?';
        $params = array($mail);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave_cliente'])) {
            $this->id = $data['id_cliente'];
            $this->correo = $data['correo_cliente'];
            $this->estado = $data['estado_cliente'];
            return true;
        } else {
            return false;
        }
    }

    // Dentro de la clase ClienteHandler
public function checkSession()
{
    if (isset($_SESSION['idCliente'])) {
        $this->id = $_SESSION['idCliente'];
        $sql = 'SELECT id_cliente, estado_cliente FROM cliente WHERE id_cliente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if ($data && $data['estado_cliente']) {
            return $this->id;
        }
    }
    return false;
}


    public function checkStatus()
    {
        if ($this->estado) {
            $_SESSION['idCliente'] = $this->id;
            $_SESSION['correoCliente'] = $this->correo;
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE cliente
                SET clave_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->clave, $_SESSION['idCliente']);
        return Database::executeRow($sql, $params);
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clave_cliente
                FROM cliente
                WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clave_cliente'])) {
            return true;
        } else {
            return false;
        }
    }
    

    public function editProfile()
    {
        $sql = 'UPDATE cliente
                SET nombre_cliente = ?, correo_cliente = ?, telefono_cliente = ?,  direccion_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->correo, $this->telefono, $this->direccion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, correo_cliente, telefono_cliente, direccion_cliente
        FROM cliente
        WHERE id_cliente = ?';
        $params = array($_SESSION['idCliente']);
        return Database::getRow($sql, $params);
    }

    public function changeStatus()
    {
        $sql = 'UPDATE cliente
                SET estado_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
    */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT nombre_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente
                FROM cliente
                WHERE nombre_cliente LIKE ? OR correo_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO cliente(nombre_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, clave_cliente)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->correo, $this->dui, $this->telefono, $this->direccion, $this->clave);
        return Database::executeRow($sql, $params);
    }

    public function createUsuario()
    {
        $sql = 'INSERT INTO cliente(nombre_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, clave_cliente)
        VALUES(?, ?, ?, ?, ?, ?)';
$params = array($this->nombre, $this->correo, $this->dui, $this->telefono, $this->direccion, $this->clave);
return Database::executeRow($sql, $params);
}

    public function readAll()
    {
        $sql = 'SELECT  id_cliente, nombre_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, clave_cliente
                FROM cliente
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, correo_cliente, dui_cliente, telefono_cliente, direccion_cliente, estado_cliente
                FROM cliente
                WHERE id_cliente = ?';
        $params = array( $this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE cliente
                SET nombre_cliente = ? = ?, correo_cliente = ?, dui_cliente = ?,  telefono_cliente = ?, direccion_cliente = ? = ?, estado_cliente = ? 
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM cliente
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM cliente
                WHERE dui_cliente = ? OR correo_cliente = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }

    public function generarPinRecuperacion()    
    {
        $pin = sprintf("%06d", mt_rand(1, 999999)); // Genera un PIN de 6 dígitos
        $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes')); // codigo valido por 30 minutos
 
        $sql = "UPDATE cliente SET recovery_pin = ?, pin_expiry = ? WHERE correo_cliente = ?";
        $params = array($pin, $expiry, $this->correo);
 
        if (Database::executeRow($sql, $params)) {
            return $pin; // Retorna el PIN para enviarlo al usuario
        } else {
            // Manejo de errores
            error_log("Error al generar el PIN de recuperación para el correo: " . $this->correo);
        }
        return false;
    }
    public function verificarPinRecuperacion($pin)
    {
        $sql = "SELECT id_cliente FROM cliente
            WHERE correo_cliente = ? AND recovery_pin = ? AND pin_expiry > NOW()";
        $params = array($this->correo, $pin);
 
        $result = Database::getRow($sql, $params);
 
        if ($result) {
            return $result['id_cliente'];
        } else {
            // Manejo de errores
            error_log("Error al verificar el PIN de recuperación para el correo: " . $this->correo);
        }
        return false;
    }

    public function resetearPin()
    {
        $sql = "UPDATE cliente SET recovery_pin = NULL, pin_expiry = NULL WHERE id_cliente = ?";
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
 
    public function cambiarClaveConPin($id_cliente, $nuevaClave)
    {
        $sql = 'UPDATE cliente SET clave_cliente = ? WHERE id_cliente = ?';
        $params = array(password_hash($nuevaClave, PASSWORD_DEFAULT), $id_cliente);
        return Database::executeRow($sql, $params);
    }

}
