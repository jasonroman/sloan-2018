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

// convert to a format that flot can understand;
/**
 * convert to a format that flot can understand;
 * ratings will look like this: [[0, 100.0], [1, 105.5], ...]
 * labels will look like this: [[0.5, 'Houston'], [1.5, 'Detroit'], ...]
 */
for ($i = 0; $i < count($teams); $i++) {
    $ratings[] = [$i, $teams[$i]['average_offensive_rating']];
    $labels[]  = [$i + 0.5, $teams[$i]['city']];
}

/**
 * now output as a PHP page - from the command-line in Git Bash, run this command:
 *      bash -c "php nba_offense_average.php > test.html"
 *
 * this will save the output to an HTML file that you can then open
 */

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NBA Offensive Ratings</title>
    <style>
        #container { width: 850px; height: 450px; margin: auto; }
        #placeholder { width: 100%; height: 100%; }
    </style>
</head>
<body>

<div id="container">
    <div id="placeholder"></div>
</div>

<script src="js/vendor/jquery.min.js" type="text/javascript"></script>
<script src="js/vendor/jquery.flot.js" type="text/javascript"></script>

<script>
    $.plot('#placeholder', [<?php echo json_encode($ratings); ?>], {
        xaxis: { tickLength: 0, ticks: <?php echo json_encode($labels); ?> },
        yaxis: { min: 95, max: 120 },
        bars: { show: true, barWidth: 0.9 }
    });
</script>

</body>
</html>
