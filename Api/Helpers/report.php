<?php
require_once('../../libraries/fpdf185/fpdf.php');

class Report extends FPDF
{
    const CLIENT_URL = 'http://localhost/prettyusine/vistas/cecot/';
    private $title = null;

    public function startReport($title)
    {
        session_start();
        if (isset($_SESSION['idAdministrador'])) {
            $this->title = $title;
            $this->SetTitle('PrettyUsine - Reporte', true);
            $this->SetMargins(20, 20, 20);
            $this->AddPage('P', 'Letter');
            $this->AliasNbPages();
        } else {
            header('Location: ' . self::CLIENT_URL);
            exit;
        }
    }

    public function Header()
    {
        // Encabezado
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'PrettyUsine - Reporte', 0, 1, 'C');        

        // Título del reporte
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, $this->title, 0, 1, 'C');
        $this->Ln(5);

        // Logo
        $this->Image('../../../recursos/img/logopretty.png', 20, 10, 30);
        $this->Image('../../../recursos/img/logopretty.png', 170, 10, 30);

        // Fecha y hora
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Fecha/Hora: ' . date('d-m-Y H:i:s'), 0, 1, 'R');
        
        // Línea de separación
        $this->SetLineWidth(0.5);
        $this->Line(20, 45, 200, 45); // Línea horizontal debajo del encabezado
        $this->Ln(10); // Salto de línea después del encabezado
    }

    public function Footer()
    {
        // Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $this->encodeString('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function encodeString($string)
    {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }
}