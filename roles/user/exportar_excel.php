<?php
session_start();
require_once('../../database/database.php');
require_once('../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Verificar si hay solicitud de exportación
if(isset($_POST['exportar']) && $_POST['exportar'] == 'excel') {
    try {
        $conexion = new database;
        $con = $conexion->conectar();

        // Obtener filtros
        $filtro_escritura = $_POST['filtro_escritura'] ?? '';
        $filtro_distrito = $_POST['filtro_distrito'] ?? '';
        $filtro_tipo = $_POST['filtro_tipo'] ?? '';

        // Construir consulta con filtros
        $query = "SELECT p.* , d.nom_distrito ,
                            t.nom_tipo , e.*, c.*, s.*, estado.nom_estado AS estado
                            FROM propiedades p
                            LEFT JOIN distrito d ON p.id_distrito = d.id_distrito
                            LEFT JOIN tipo_propiedad t ON p.id_tip_prop = t.id_tip_prop
                            LEFT JOIN escritura e ON p.id_lugar = e.id_lugar
                            LEFT JOIN contrato c ON p.id_lugar = c.id_lugar
                            LEFT JOIN seguros s ON p.id_lugar = s.id_lugar
                            LEFT JOIN estado ON p.id_estado = estado.id_estado
                            WHERE 1=1";

        if(!empty($filtro_escritura)) {
            $escritura = ($filtro_escritura == 'si') ? 1 : 0;
            $query .= " AND e.documento_pdf = NULL " . ($escritura ? "OR e.documento_pdf != ''" : "");
        }

        if(!empty($filtro_distrito)) {
            $query .= " AND d.nom_distrito = '$filtro_distrito'";
        }

        if(!empty($filtro_tipo)) {
            $query .= " AND t.nom_tipo = '$filtro_tipo'";
        }

        $query .= " ORDER BY d.nom_distrito ASC";

        $stmt = $con->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Crear nuevo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Informes Propiedades");

        // Definir encabezados
        $encabezados = ['Código', 'Propiedad', 'Dirección', 'Tipo Propiedad', 'Nro Matrícula', 
                        'Ficha Catastral', 'Valor Escritura', 'Fecha Registro', 'Código Contable',
                        'Valor Contable', 'Valor Contable Lote', 'Valor Contable Edificio',
                        'Valor Avalúo', 'Valor Avalúo Lote', 'Valor Avalúo Edificio',
                        'Nro Contrato', 'Valor Contrato', 'Seguro', 'Escritura Física','Estado', 'Observación'];

        // Añadir encabezados con estilos
        $columnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U'];
        foreach($columnas as $index => $columna) {
            $cell = $sheet->getCell($columna . '1');
            $cell->setValue($encabezados[$index]);

            // Estilo del encabezado
            $style = $cell->getStyle();
            $style->getFont()->setBold(true);
            $style->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $style->getFill()->setFillType(Fill::FILL_SOLID);
            $style->getFill()->getStartColor()->setARGB('FF167a93');
        }

        // Añadir datos
        $fila = 2;
        foreach($datos as $row) {
            $sheet->setCellValue('A' . $fila, $row['cod_lugar'] ?? '');
            $sheet->setCellValue('B' . $fila, $row['nom_lugar'] ?? '');
            $sheet->setCellValue('C' . $fila, $row['direccion'] ?? '');
            $sheet->setCellValue('D' . $fila, $row['nom_tipo'] ?? '');
            $sheet->setCellValue('E' . $fila, $row['nro_matricula'] ?? '');
            $sheet->setCellValue('F' . $fila, $row['ficha_catastral'] ?? '');
            $sheet->setCellValue('G' . $fila, $row['valor'] ?? '');
            $sheet->setCellValue('H' . $fila, $row['fecha_registro'] ?? '');
            $sheet->setCellValue('I' . $fila, $row['codigo_contable'] ?? '');
            $sheet->setCellValue('J' . $fila, $row['valor_contable'] ?? '');
            $sheet->setCellValue('K' . $fila, $row['valor_contable_lote'] ?? '');
            $sheet->setCellValue('L' . $fila, $row['valor_contable_build'] ?? '');
            $sheet->setCellValue('M' . $fila, $row['valor_avaluo'] ?? '');
            $sheet->setCellValue('N' . $fila, $row['valor_avaluo_lote'] ?? '');
            $sheet->setCellValue('O' . $fila, $row['valor_avaluo_build'] ?? '');
            $sheet->setCellValue('P' . $fila, $row['nro_contrato'] ?? '');
            $sheet->setCellValue('Q' . $fila, $row['valor_contrato'] ?? '');
            $sheet->setCellValue('R' . $fila, $row['precio'] ?? '');
            $sheet->setCellValue('S' . $fila, (isset($row['documento_pdf']) && !empty($row['documento_pdf'])) ? 'Sí' : 'No');
            $sheet->setCellValue('T'. $fila, $row['estado']?? '');
            $sheet->setCellValue('U' . $fila, $row['observacion'] ?? '');


            // Centrar datos
            $todasLasColumnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'. 'U'];
            foreach($todasLasColumnas as $columna) {
                $sheet->getCell($columna . $fila)->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $fila++;
        }

        // Ajustar ancho de columnas
        $todasLasColumnas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        foreach($todasLasColumnas as $columna) {
            $sheet->getColumnDimension($columna)->setAutoSize(true);
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Informes_Propiedades_' . date('d-m-Y_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();

    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}

echo json_encode(['error' => 'No se especificó exportación']);
