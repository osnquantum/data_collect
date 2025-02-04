<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch data from the database

include('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM submissions");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$conn->close();

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Web Link');
$sheet->setCellValue('E1', 'Media File Path');
$sheet->setCellValue('F1', 'Submission Date');

// Add data
$row = 2;
foreach ($data as $item) {
    $sheet->setCellValue('A' . $row, $item['id']);
    $sheet->setCellValue('B' . $row, $item['name']);
    $sheet->setCellValue('C' . $row, $item['email']);
    $sheet->setCellValue('D' . $row, $item['web_link']);
    $sheet->setCellValue('E' . $row, $item['media_file_path']);
    $sheet->setCellValue('F' . $row, $item['submission_date']);
    $row++;
}

// Save the file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="submissions.xlsx"');
$writer->save('php://output');
?>
