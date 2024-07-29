<?php
require_once '../../libraries/fpdf185/fpdf.php';

class PDF extends FPDF
{
    // Página de encabezado
    function Header()
    {
        // Borde alrededor de la página
        $this->Rect(5, 5, $this->GetPageWidth() - 10, $this->GetPageHeight() - 10);

        // Título
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, utf8_decode('Factura para cliente'), 0, 1, 'C');
        $this->Ln(10); // Añadir espacio después del título

        // Logo
        $logoWidth = 40; // Ancho del logo
        $pageWidth = $this->GetPageWidth();
        $xCenter = ($pageWidth - $logoWidth) / 2; // Posición centrada para el logo

        $this->Image('../../../recursos/img/logopretty.png', $xCenter, 25, $logoWidth);
        $this->Ln(45); // Añadir espacio después del logo
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    // Tabla con encabezado y datos
    function InvoiceTable($header, $data)
    {
        // Ajustar el ancho de las columnas
        $w = array(70, 30, 30, 30); // Anchos actualizados: DESCRIPCION, CANTIDAD, PRECIO, SUBTOTAL

        // Calcular el ancho total de la tabla
        $totalWidth = array_sum($w);

        // Calcular la posición para centrar la tabla
        $x = ($this->GetPageWidth() - $totalWidth) / 2;

        // Encabezado
        $this->SetXY($x, $this->GetY());
        $this->SetFillColor(169, 209, 84); // Color de fondo para el encabezado
        $this->SetTextColor(255); // Color del texto blanco
        $this->SetDrawColor(0); // Color del borde
        $this->SetLineWidth(.3);
        $this->SetFont('Arial', 'B', 12); // Cambiado a Arial para un estilo más formal
        foreach ($header as $i => $col) {
            $this->Cell($w[$i], 10, $col, 1, 0, 'C', true);
        }
        $this->Ln();

        // Datos
        $this->SetFont('Arial', '', 10); // Cambiado a Arial para un estilo más formal
        $fill = false;
        $totalGeneral = 0;
        foreach ($data as $row) {
            $nombre_producto = utf8_decode($row['nombre_producto']);
            $cantidad = $row['cantidad'];
            $precio_unitario = $row['precio_unitario'];

            // Calcular subtotal por producto
            $subtotal = $cantidad * $precio_unitario;
            $totalGeneral += $subtotal;

            // Calcular la posición Y para cada fila
            $currentY = $this->GetY();
            $this->SetXY($x, $currentY);
            $this->SetTextColor(0); // Color del texto negro
            $this->Cell($w[0], 8, $nombre_producto, 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 8, $cantidad, 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 8, '$' . number_format($precio_unitario, 2, '.', ','), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 8, '$' . number_format($subtotal, 2, '.', ','), 'LR', 0, 'R', $fill);
            $this->Ln();

            $fill = !$fill;
        }
        // Dibujar la línea inferior centrada con la tabla
        $this->SetX($x); // Asegurar que la posición X esté alineada
        $this->Cell(array_sum($w), 0, '', 'T');

        // Mostrar total general
        $this->SetXY($x, $this->GetY());
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($w[0] + $w[1] + $w[2], 10, 'Total', 1, 0, 'C');
        $this->Cell($w[3], 10, '$' . number_format($totalGeneral, 2, '.', ','), 1, 0, 'R');
    }
}

        // Crear objeto PDF
        $pdf = new PDF();
        $pdf->AddPage();

        // Conectar a tu base de datos  
        require_once '../../models/data/pedido_data.php';

        $pedido = new PedidoData;

if (isset($_GET['id_pedido']) && $pedido->setId($_GET['id_pedido'])) {
    if ($dataPedido = $pedido->readDetails()) {
        // Obtener los datos del primer registro (suponiendo que todos los registros tienen la misma información del cliente)
        $firstRecord = $dataPedido[0];
        $nombre_cliente = utf8_decode($firstRecord['nombre']);
        $correo_cliente = utf8_decode($firstRecord['correo']);
        $direccion_cliente = utf8_decode($firstRecord['direccion']);

        // Título y datos del cliente
        $pdf->SetTextColor(0); // Color del texto negro
        $pdf->Cell(0, 8, utf8_decode('Datos del cliente'), 0, 1, 'C');
        $pdf->Cell(0, 8, utf8_decode('Nombre: ') . $nombre_cliente, 0, 1, 'L');
        $pdf->Cell(0, 8, 'Correo: ' . $correo_cliente, 0, 1, 'L');

        // Dirección con MultiCell para manejo de texto largo
        $pdf->Cell(0, 8, utf8_decode('Dirección: '), 0, 1, 'L');
        $pdf->MultiCell(0, 10, $direccion_cliente); // Ajusta el ancho y alto según sea necesario
        $pdf->Ln(10);

        // Tabla de productos
        $pdf->SetXY(0, $pdf->GetY() + 0); // Espacio aumentado antes de la tabla
        $pdf->SetFont('Arial', 'B', 15); // Cambiado a Arial para un estilo más formal
        $header = array('DESCRIPCION', 'CANTIDAD', 'PRECIO', 'SUBTOTAL');
        $pdf->InvoiceTable($header, $dataPedido);

        // Mostrar el PDF
        $pdf->Output();
    } else {
        echo 'No se encontraron datos.';
    }
} else {
    echo 'ID del pedido no válido.';
}
?>