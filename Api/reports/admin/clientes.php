<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once ('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once ('../../models/data/cliente_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;

// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Clientes');

// Se instancia el modelo ClienteData para obtener los datos de los clientes.
$clienteData = new ClienteData;

// Añadir un margen negro alrededor del reporte
$pdf->SetFillColor(0, 0, 0); // Color negro
$pdf->rect(5, 5, 205, 270, ); // Dibuja un rectángulo negro (ajusta la posición y tamaño según sea necesario)

// Se verifica si existen registros de clientes para mostrar.
if ($dataClientes = $clienteData->readAll()) {
    // Configuración de estilos y encabezados de la tabla
    $pdf->setFillColor(200);
    $pdf->setFont('Arial', 'B', 11);

    // Encabezados de la tabla
    $pdf->cell(35, 10, $pdf->encodeString('Nombre'), 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'DUI', 1, 0, 'C', 1);
    $pdf->cell(35, 10, 'Telefono', 1, 0, 'C', 1);
    $pdf->cell(75, 10, $pdf->encodeString('Dirección'), 1, 1, 'C', 1);

    // Iteración sobre los clientes
    foreach ($dataClientes as $cliente) {
        // Datos de cada cliente
        $nombre = $cliente['nombre_cliente'];
        $dui = $cliente['dui_cliente'];
        $telefono = $cliente['telefono_cliente'];
        $direccion = $cliente['direccion_cliente'];

        // Filas de datos de clientes
        $pdf->setFont('Arial', '', 11);
        $pdf->cell(35, 10, $pdf->encodeString($nombre), 1, 0, 'L');
        $pdf->cell(35, 10, $dui, 1, 0, 'C');
        $pdf->cell(35, 10, $telefono, 1, 0, 'C');
        $pdf->multiCell(75, 10, $pdf->encodeString($direccion), 1, 'L');
    }
} else {
    // Si no hay clientes para mostrar
    $pdf->setFont('Arial', '', 11);
    $pdf->cell(0, 10, $pdf->encodeString('No hay clientes para mostrar'), 1, 1, 'C');
}

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'clientes.pdf');
?>