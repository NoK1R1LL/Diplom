<?php
require 'vendor/autoload.php'; // Подключаем автозагрузчик Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include('db.php');

// Запрос к базе данных для получения данных о товарах
$sql = "SELECT 
            p.photo, 
            p.name, 
            p.description, 
            c.name AS category, 
            p.quantity, 
            s.shelf_number AS shelf
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN shelves s ON p.shelf_id = s.id";
        
$result = $mysqli->query($sql);

// Создаем новый объект Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Устанавливаем заголовки таблицы
$sheet->setCellValue('A1', 'Фото');
$sheet->setCellValue('B1', 'Название');
$sheet->setCellValue('C1', 'Описание');
$sheet->setCellValue('D1', 'Категория');
$sheet->setCellValue('E1', 'Количество');
$sheet->setCellValue('F1', 'Полка');

// Заполняем таблицу данными из базы
$rowNum = 2; // Начинаем с второй строки
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['photo']);
    $sheet->setCellValue('B' . $rowNum, $row['name']);
    $sheet->setCellValue('C' . $rowNum, $row['description']);
    $sheet->setCellValue('D' . $rowNum, $row['category']);
    $sheet->setCellValue('E' . $rowNum, $row['quantity']);
    $sheet->setCellValue('F' . $rowNum, $row['shelf']);
    $rowNum++;
}

// Создаем объект Writer для сохранения файла в формате Excel
$writer = new Xlsx($spreadsheet);

// Отправляем заголовки для скачивания
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="export_product.xlsx"');
header('Cache-Control: max-age=0');

// Сохраняем файл в выходной поток
$writer->save('php://output');
exit;

header("Location: index.php");
?>
