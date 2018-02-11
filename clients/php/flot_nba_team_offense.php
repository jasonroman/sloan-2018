<?php

$ratings = [];
$labels  = [];

$headers = [
    'X-Api-Username: sloan',
    'X-Api-Key: sloan2018',
    'Accept: application/json',
];

// initiate the curl session at the given url
$curl = curl_init('http://sloan.test:8000/api/stats/teams/nba/offense');

// set the request headers
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// do not print the results to the console
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// get the result of the curl request
$response = curl_exec($curl);

// close the curl session
curl_close($curl);

// get the ratings as a PHP array
$teams = json_decode($response, true);

/**
 * convert to a format that flot can understand;
 * ratings will look like this: [[0, 100.0], [1, 105.5], ...]
 * labels for the x-axis will look like this: [[0.5, 'Houston'], [1.5, 'Detroit'], ...]
 */
for ($i = 0; $i < count($teams); $i++) {
    $ratings[] = [$i, $teams[$i]['average_offense_rating']];
    $labels[]  = [$i + 0.5, $teams[$i]['city']];
}

/**
 * now output to an HTML file from the command-line via command:
 *      bash -c "php flot_nba_team_offense.php > flot_nba_team_offense.html"
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NBA Team Average Offense Ratings</title>
    <link href="css/flot.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="container">
    <h1>NBA Team Offense</h1>
    <div id="placeholder"></div>
</div>

<script src="js/vendor/jquery.min.js" type="text/javascript"></script>
<script src="js/vendor/jquery.flot.js" type="text/javascript"></script>

<script>
    $.plot('#placeholder', [<?php echo json_encode($ratings); ?>], {
        xaxis: { tickLength: 0, ticks: <?php echo json_encode($labels); ?> },
        yaxis: { min: 95, max: 115 },
        bars: { show: true, barWidth: 0.9 }
    });
</script>

</body>
</html>
