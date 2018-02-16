<?php

$ratings = [];
$labels  = [];

$headers = [
    'X-Api-Username: sloan',
    'X-Api-Key: sloan2018',
    'Accept: application/json',
];

// initiate the curl session at the given url
$curl = curl_init('http://sloan.jayroman.com/api/stats/teams/nba/ratings');

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
 * each data series will have a team's offense and defense rating;
 * the 'bars' variable allows the bars to be placed side-by-side for each series
 * ratings will look like this: [[[0, 100.0], [1, 105.5]], ...]
 * labels used for the legend will look like this: ['Houston Rockets', 'Detroit Pistons', ...]
 */

$i = 1;
foreach ($teams as $team) {
    $ratings[] = [
        'data'  => [[0, $team['average_offense_rating']], [1, $team['average_defense_rating']]],
        'label' => $team['full_name'],
        'bars'  => ['order' => $i],
    ];

    $i++;
}

/**
 * now output to an HTML file from the command-line via command:
 *      bash -c "php flot_nba_team_ratings.php > flot_nba_team_ratings.html"
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NBA Team Offense Ratings</title>
    <link href="css/flot.css" rel="stylesheet" type="text/css">
</head>
<body>

<div id="container">
    <h1>NBA Team Ratings</h1>
    <div id="placeholder"></div>
</div>

<script src="js/vendor/jquery.min.js" type="text/javascript"></script>
<script src="js/vendor/jquery.flot.js" type="text/javascript"></script>
<script src="js/vendor/jquery.flot.barnumbers.enhanced.js" type="text/javascript"></script>
<script src="js/vendor/jquery.flot.orderBars.js" type="text/javascript"></script>

<script>
    $.plot('#placeholder', <?php echo json_encode($ratings); ?>, {
        xaxis: {
            min: -0.5,
            max: 1.5,
            mode: null,
            tickLength: 0,
            ticks: [[0, 'NBA Team Offense'], [1, 'NBA Team Defense']]
        },
        yaxis: {
            min: 95,
            max: 115
        },
        bars: {
            show: true,
            barWidth: (0.4 / 2),
            numbers: {
                show: true,
                font: '10pt Arial',
                fontColor: '#555',
                xAlign: function(x) { return x + 0.1; },
                threshold: 0.25,
                yAlign: function(y) { return y; }, // shows numbers at the top of the bar
                yOffset: 8 // pixel offset so numbers are not right up on the edge of the top of the bar
            }
        }
    });
</script>

</body>
</html>
