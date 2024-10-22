<?php
require 'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

$htmlContent = $_POST['content'];
$jahr = $_POST['Jahr'];
$monat = $_POST['Monat'];

// Beispiel-HTML-Inhalt
// $htmlContent = '
// <h1>Tabelle 1</h1>
// <table>
//     <tr>
//         <th>Name</th>
//         <th>Alter</th>
//     </tr>
//     <tr>
//         <td>Max</td>
//         <td>28</td>
//     </tr>
//     <tr>
//         <td>Sophie</td>
//         <td>24</td>
//     </tr>
// </table>
// <h1>Tabelle 2</h1>
// <table>
//     <tr>
//         <th>Produkt</th>
//         <th>Preis</th>
//     </tr>
//     <tr>
//         <td>Apfel</td>
//         <td>1.00</td>
//     </tr>
//     <tr>
//         <td>Banane</td>
//         <td>1.20</td>
//     </tr>
// </table>
// ';

// Lade den HTML-String in ein DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($htmlContent);

// Erstelle einen Spout-Writer
$writer = WriterEntityFactory::createXLSXWriter();
$filename = "Arbeitszeiten $monat $jahr.xlsx";
$writer->openToFile($filename);

// Definiere einen Stil für die Überschriften
$headerStyle = (new StyleBuilder())->setFontBold()->setFontSize(16)->build();

$rowIndex = 1;

// Finde alle Überschriften und Tabellen
$h1Tags = $dom->getElementsByTagName('h1');
$tables = $dom->getElementsByTagName('table');

for ($i = 0; $i < $h1Tags->length; $i++) {
    // Überschrift hinzufügen
    $headerRow = WriterEntityFactory::createRowFromArray([$h1Tags->item($i)->nodeValue], $headerStyle);
    $writer->addRow($headerRow);

    // Tabelle hinzufügen
    $rows = $tables->item($i)->getElementsByTagName('tr');
    foreach ($rows as $row) {
        $cellValues = [];
        foreach ($row->childNodes as $cell) {
            if ($cell->nodeName === 'th' || $cell->nodeName === 'td') {
                $cellValues[] = $cell->nodeValue;
            }
        }
        $writer->addRow(WriterEntityFactory::createRowFromArray($cellValues));
    }

    // Eine leere Zeile zwischen Tabellen (optional)
    $emptyRow = WriterEntityFactory::createRowFromArray([]);
    $writer->addRow($emptyRow);
}

$writer->close();

// Datei zum Download anbieten
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
flush(); // Puffer leeren
readfile($filename);
unlink($filename); // Lösche die Datei nach dem Download
exit;
?>
