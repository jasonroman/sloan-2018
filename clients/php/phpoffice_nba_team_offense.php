<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$ratings = [];
$labels  = [];

$headers = [
    'X-Api-Username: sloan',
    'X-Api-Key: sloan2018',
    'Accept: application/json',
];

// initiate the curl session at the given url
$curl = curl_init('http://sloan.test:8000/api/stats/teams/nba/offense');

// set the curl options and get the response, then close the curl session
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

// get the ratings as a PHP array
$teams = json_decode($response, true);

// initiate the sheet and set the appropriate values
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

// save the spreadsheet
$writer = new Xlsx($spreadsheet);
$writer->save('phpoffice_nba_team_offense.xlsx');