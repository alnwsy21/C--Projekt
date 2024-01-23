<?php
// Pfad zur CSV-Datei
$csvFilePath = 'fragen3.csv';

// Funktion zum Lesen der CSV-Datei und Konvertieren in ein assoziatives Array
function readCSV($filePath) {
    $csv = array_map('str_getcsv', file($filePath));
    $headers = array_shift($csv);
    $data = array();
    foreach ($csv as $row) {
        $data[] = array_combine($headers, $row);

    }
    return $data;
}

// Lese die CSV-Datei
$questions = readCSV($csvFilePath);

// Gib die Fragen als JSON zurück
echo json_encode($questions);
?>
