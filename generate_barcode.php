<?php
require 'vendor/autoload.php'; // Убедись, что установлена библиотека Picqer\Barcode
use Picqer\Barcode\BarcodeGeneratorPNG;

// Функция для расчёта контрольной суммы EAN-13
function calculateEAN13Checksum($code) {
    $sum = 0;
    // Пройдем по всем цифрам, начиная с первой
    for ($i = 0; $i < 12; $i++) {
        $digit = (int) $code[$i];
        // Для нечётных позиций умножаем на 1, для чётных на 3
        if ($i % 2 == 0) {
            $sum += $digit;
        } else {
            $sum += $digit * 3;
        }
    }
    // Находим контрольную цифру
    $remainder = $sum % 10;
    if ($remainder == 0) {
        return 0;
    } else {
        return 10 - $remainder;
    }
}

// Название товара
$productName = "Черная футболка";

// Генерация уникального 12-значного кода для товара
$productID = crc32($productName); // Генерируем уникальный ID на основе названия товара
$baseCode = str_pad($productID, 11, "0", STR_PAD_LEFT); // Формируем строку длиной 12 цифр

// Расчитаем контрольную цифру
$checksum = calculateEAN13Checksum($baseCode);

// Итоговый EAN-13 код
$ean13Code = $baseCode . $checksum;

// Генерация штрих-кода
$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($ean13Code, $generator::TYPE_EAN_13);

// Устанавливаем имя файла с названием товара
$filename = preg_replace('/[^a-zA-Z0-9_]/', '_', $productName) . '.png'; // Заменяем неалфавитные символы на подчеркивания

// Отправляем заголовки для скачивания изображения
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Выводим штрих-код
echo $barcode;
?>
