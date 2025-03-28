<?php
ob_start(); // Prevent extra output

require '../../../vendor/autoload.php'; // PhpSpreadsheet
include '../../../includes/conn.php'; // Database connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Column Headers (Without Stock Unit)
$headers = [
    'Transaction ID',
    'Transaction Type',
    'From Company',
    'To Company',
    'Posting Date',
    'Delivery Date',
    'DR Number',
    'PO Number',
    'Reference PO',
    'Product Code',
    'Brand',
    'Description',
    'Quantity',
    'Unit',
    'Current Stock Quantity',
    'Created At'
];

// Header Styling (Black BG, White Text, Centered)
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 14,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '000000'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Insert Headers
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Apply Header Style
$lastColumn = $sheet->getHighestColumn();
$sheet->getStyle("A1:{$lastColumn}1")->applyFromArray($headerStyle);

// Fetch Transactions with Stock Data
$query = "SELECT 
            t.transaction_id, 
            t.transaction_type, 
            c1.name AS from_company, 
            c2.name AS to_company, 
            d1.date_value AS posting_date, 
            d2.date_value AS delivery_date, 
            dr.dr_number, 
            po.po_number, 
            ref.reference_po, 
            p.code AS product_code, 
            p.brand, 
            p.description, 
            t.quantity, 
            u.unit_name, 
            s.current_quantity,
            t.created_at
          FROM transactions t
          LEFT JOIN companies c1 ON t.from_company_id = c1.company_id
          LEFT JOIN companies c2 ON t.to_company_id = c2.company_id
          LEFT JOIN dates d1 ON t.posting_date = d1.date_id
          LEFT JOIN dates d2 ON t.delivery_date = d2.date_id
          LEFT JOIN delivery_receipts dr ON t.dr_id = dr.dr_id
          LEFT JOIN purchase_orders po ON t.po_id = po.po_id
          LEFT JOIN reference_pos ref ON t.reference_po_id = ref.reference_po_id
          LEFT JOIN products p ON t.product_id = p.product_id
          LEFT JOIN units u ON t.unit_id = u.unit_id
          LEFT JOIN stocks s ON t.product_id = s.product_id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $rowNum = 2;
    while ($row = $result->fetch_assoc()) {
        // Convert Transaction Type
        $transactionType = ($row['transaction_type'] === 'invoices') ? 'ReStock' : 'Delivery Receipt';

        // Insert Data
        $sheet->setCellValue('A' . $rowNum, $row['transaction_id']);
        $sheet->setCellValue('B' . $rowNum, $transactionType);
        $sheet->setCellValue('C' . $rowNum, $row['from_company']);
        $sheet->setCellValue('D' . $rowNum, $row['to_company']);
        $sheet->setCellValue('E' . $rowNum, $row['posting_date']);
        $sheet->setCellValue('F' . $rowNum, $row['delivery_date']);
        $sheet->setCellValue('G' . $rowNum, $row['dr_number']);
        $sheet->setCellValue('H' . $rowNum, $row['po_number']);
        $sheet->setCellValue('I' . $rowNum, $row['reference_po']);
        $sheet->setCellValue('J' . $rowNum, $row['product_code']);
        $sheet->setCellValue('K' . $rowNum, $row['brand']);
        $sheet->setCellValue('L' . $rowNum, $row['description']);
        $sheet->setCellValue('M' . $rowNum, $row['quantity']);
        $sheet->setCellValue('N' . $rowNum, $row['unit_name']);
        $sheet->setCellValue('O' . $rowNum, $row['current_quantity']);
        $sheet->setCellValue('P' . $rowNum, $row['created_at']);

        // Apply alternating row colors (White & Light Gray)
        $rowStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => ($rowNum % 2 == 0) ? 'F2F2F2' : 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A{$rowNum}:P{$rowNum}")->applyFromArray($rowStyle);

        $rowNum++;
    }
}

// Auto-Adjust Column Widths
foreach (range('A', 'P') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Clear Output Before Headers
ob_end_clean();

// Set Headers for XLSX Download
$filename = 'nglData.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>