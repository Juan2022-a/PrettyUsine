-- dropDatabase --
-- drop database PrettyUsine;

-- Crear la base de datos --
CREATE DATABASE IF NOT EXISTS PrettyUsine DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
 
-- Seleccionar la base de datos --
USE PrettyUsine;
 
----------- Crear la tabla de administrador -----------
CREATE TABLE administrador (
  id_administrador int(10) UNSIGNED NOT NULL,
  nombre_administrador varchar(50) NOT NULL,
  apellido_administrador varchar(50) NOT NULL,
  correo_administrador varchar(100) NOT NULL,
  alias_administrador varchar(25) NOT NULL,
  clave_administrador varchar(100) NOT NULL,
  fecha_registro datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 ----------- Crear la tabla de categoria -----------
 
CREATE TABLE categoria (
  id_categoria int(10) UNSIGNED NOT NULL,
  nombre_categoria varchar(50) NOT NULL,
  descripcion_categoria varchar(250) DEFAULT NULL,
  imagen_categoria varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

insert into categoria (id_categoria, nombre_categoria, descripcion_categoria) values (1,'Carnivoras', 'Las mejores plantas a nivel visual que existe en esta vida');
select * from categoria;

----------- Crear la tabla de usuarios -----------

CREATE TABLE cliente (
  id_cliente int(10) UNSIGNED NOT NULL,
  nombre_cliente varchar(50) NOT NULL,
  apellido_cliente varchar(50) NOT NULL,
  dui_cliente varchar(10) NOT NULL,
  correo_cliente varchar(100) NOT NULL,
  telefono_cliente varchar(9) NOT NULL,
  direccion_cliente varchar(250) NOT NULL,
  nacimiento_cliente date NOT NULL,
  clave_cliente varchar(100) NOT NULL,
  estado_cliente tinyint(1) NOT NULL DEFAULT 1,
  fecha_registro date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
----------- Crear la tabla de detalles del pedido -----------

CREATE TABLE detalle_pedido (
  id_detalle int(10) UNSIGNED NOT NULL,
  id_producto int(10) UNSIGNED NOT NULL,
  cantidad_producto smallint(6) UNSIGNED NOT NULL,
  precio_producto decimal(5,2) UNSIGNED NOT NULL,
  id_pedido int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

----------- Crear la tabla de pedidos -----------

CREATE TABLE pedido (
  id_pedido int(10) UNSIGNED NOT NULL,
  id_cliente int(10) UNSIGNED NOT NULL,
  direccion_pedido varchar(250) NOT NULL,
  estado_pedido enum('Pendiente','Finalizado','Entregado','Anulado') NOT NULL,
  fecha_registro date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 ----------- Crear tabla producto -----------
 
CREATE TABLE producto (
  id_producto int(10) UNSIGNED NOT NULL,
  nombre_producto varchar(50) NOT NULL,
  descripcion_producto varchar(250) NOT NULL,
  precio_producto decimal(5,2) NOT NULL,
  existencias_producto int(10) UNSIGNED NOT NULL,
  imagen_producto varchar(25) NOT NULL,
  id_categoria int(10) UNSIGNED NOT NULL,
  estado_producto tinyint(1) NOT NULL,
  id_administrador int(10) UNSIGNED NOT NULL,
  fecha_registro date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 ----------- Crear tabla tipo usuario -----------

create table tipo_usuario (
id_tipo_usuario int auto_increment primary key,
nombre_tipo_usuario varchar(50)
);

----------- Crear la tabla de valoracion -----------

CREATE TABLE valoracion (
id_valoracion int(10) UNSIGNED NOT NULL,
id_producto int(10) UNSIGNED NOT NULL,
calificacion_producto INT NULL,
comentario_producto VARCHAR(250)NULL ,
fecha_registro datetime NOT NULL DEFAULT current_timestamp(),
estado_comentario BOOLEAN NOT NULL
);
 
ALTER TABLE administrador
ADD PRIMARY KEY (id_administrador),
ADD UNIQUE KEY correo_usuario (correo_administrador),
ADD UNIQUE KEY alias_usuario (alias_administrador);
    
ALTER TABLE categoria
ADD PRIMARY KEY (id_categoria),
ADD UNIQUE KEY nombre_categoria (nombre_categoria);
  
ALTER TABLE cliente
ADD PRIMARY KEY (id_cliente),
ADD UNIQUE KEY dui_cliente (dui_cliente),
ADD UNIQUE KEY correo_cliente (correo_cliente);

ALTER TABLE detalle_pedido
ADD PRIMARY KEY (id_detalle),
ADD KEY id_producto (id_producto),
ADD KEY id_pedido (id_pedido);

ALTER TABLE pedido
ADD PRIMARY KEY (id_pedido),
ADD KEY id_cliente (id_cliente);

ALTER TABLE producto
ADD PRIMARY KEY (id_producto),
ADD UNIQUE KEY nombre_producto (nombre_producto,id_categoria),
ADD KEY id_categoria (id_categoria),
ADD KEY id_usuario (id_administrador);
 
ALTER TABLE valoracion
ADD FOREIGN KEY (id_producto)
REFERENCES producto (id_producto);

 ----------- AUTO_INCREMENT de la tabla administrador -----------
ALTER TABLE administrador
  MODIFY id_administrador int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- AUTO_INCREMENT de la tabla categoria -----------
 
ALTER TABLE categoria
  MODIFY id_categoria int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- AUTO_INCREMENT de la tabla cliente -----------

ALTER TABLE cliente
  MODIFY id_cliente int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- AUTO_INCREMENT de la tabla detalle_pedido -----------

ALTER TABLE detalle_pedido
  MODIFY id_detalle int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- AUTO_INCREMENT de la tabla pedido -----------

ALTER TABLE pedido
  MODIFY id_pedido int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- AUTO_INCREMENT de la tabla producto -----------

ALTER TABLE producto
  MODIFY id_producto int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

 ----------- Filtros para la tabla detalle_pedido -----------

ALTER TABLE detalle_pedido
  ADD CONSTRAINT detalle_pedido_ibfk_1 FOREIGN KEY (id_producto) REFERENCES producto (id_producto) ON UPDATE CASCADE,
  ADD CONSTRAINT detalle_pedido_ibfk_2 FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido) ON UPDATE CASCADE;

 ----------- Filtros para la tabla pedido -----------

ALTER TABLE pedido
  ADD CONSTRAINT pedido_ibfk_1 FOREIGN KEY (id_cliente) REFERENCES `cliente` (id_cliente) ON UPDATE CASCADE;

 ----------- Filtros para la tabla producto -----------

ALTER TABLE producto
  ADD CONSTRAINT producto_ibfk_1 FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria) ON UPDATE CASCADE,
  ADD CONSTRAINT producto_ibfk_2 FOREIGN KEY (id_administrador) REFERENCES administrador (id_administrador) ON UPDATE CASCADE;
COMMIT;