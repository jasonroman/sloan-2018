<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

$headers = [
    'X-Api-Username: sloan',
    'X-Api-Key: sloan2018',
    'Accept: application/json',
];

// initiate the curl session
$curl = curl_init();

// set the curl options - url and headers, return response as string rather than output
curl_setopt_array($curl, [
    CURLOPT_URL            => 'http://sloan.jayroman.com/api/stats/league-teams/nba/offense',
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_RETURNTRANSFER => 1,
]);

// get the response and close the curl session
$response = curl_exec($curl);
curl_close($curl);

// get the ratings as a PHP array
$teams = json_decode($response, true);

// set some basic chart defaults
$numTeams  = count($teams);
$title     = 'NBA Team Offense';
$rowOffset = 3;

// set the headers
$data[] = ['Team', 'Offense Rating'];

// set the data
foreach ($teams as $team) {
    $data[] = [$team['full_name'], $team['average_offense_rating']];
}

// create the spreadsheet and set the initial data
$spreadsheet = new Spreadsheet();
$worksheet   = $spreadsheet->getActiveSheet();

// initiate the data and of the worksheet and auto-size the data columns
$worksheet->fromArray($data);
$worksheet->getColumnDimensionByColumn(1)->setAutoSize(true);
$worksheet->getColumnDimensionByColumn(2)->setAutoSize(true);

// set the xaxis tick values to the team corresponding to that bar
$tickValues = [new DataSeriesValues(
    DataSeriesValues::DATASERIES_TYPE_STRING,
    sprintf('Worksheet!$A$2:$A$%s', $numTeams + 1)
)];

// set the data for each team
$dataSeriesValues = [new DataSeriesValues(
    DataSeriesValues::DATASERIES_TYPE_NUMBER,
    sprintf('Worksheet!$B$2:$B$%s', $numTeams + 1)
)];

// create the data series that will be charted
$series = new DataSeries(
    DataSeries::TYPE_BARCHART, // plotType
    DataSeries::GROUPING_CLUSTERED, // plotGrouping
    range(0, 1), // plotOrder
    [], // plotLabel
    $tickValues, // plotCategory
    $dataSeriesValues // plotValues
);

// set that the bar chart should show the values on to the bars
$layout = new Layout();
$layout->setShowVal(true);

// create the new chart with the title and plot area
$chart = new Chart(
    $title, // name
    new Title($title), // title
    null, // legend
    new PlotArea($layout, [$series]) // plotArea
);

// set the position and size of the chart based on the row offset and number of teams (approximations)
$chart->setTopLeftPosition(
    $worksheet->getCellByColumnAndRow(1, $rowOffset + $numTeams)->getCoordinate()
);

$chart->setBottomRightPosition(
    $worksheet->getCellByColumnAndRow($numTeams * 2, ($numTeams * 2) + $rowOffset + 10)->getCoordinate()
);

// add the chart to eh worksheet
$worksheet->addChart($chart);

// save the spreadsheet with the charts
$writer = new XlsxWriter($spreadsheet);
$writer->setIncludeCharts(true);

// save the chart to an Excel spreadsheet
$writer->save('phpoffice_nba_team_offense.xlsx');