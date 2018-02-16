const express = require('express');
const request = require('request');
const path = require('path');

const app = express();

const BASE_URL = 'http://sloan.jayroman.com'
const API_USERNAME = 'sloan';
const API_KEY = 'sloan2018';

const HEADERS = {
  'X-Api-Username': API_USERNAME,
  'X-Api-Key': API_KEY
};

// set template engine
app.set('view engine', 'pug');
app.set('views', path.join(__dirname, 'views'));

// set public folder
app.use(express.static(path.join(__dirname, 'public')));

// main page
app.get('/', (req, res) => {
  // get the nba teams offense ratings from the API
  request.get({url: BASE_URL + '/api/stats/teams/nba/offense', headers: HEADERS}, (err, req, body) => {
    var ratings = [];
    var labels = [];

    // parse the JSON string into an object
    teams = JSON.parse(body);

    // loop through each team and convert to a format that flot can understand
    // ratings will look like this: [[0, 100.0], [1, 105.5], ...]
    // labels for the x-axis will look like this: [[0.5, 'Houston'], [1.5, 'Detroit'], ...]
    Object.keys(teams).forEach((key, idx) => {
      ratings.push([idx, teams[key]['average_offense_rating']]);
      labels.push([idx + 0.5, teams[key]['full_name']]);
    });

    // render the pug template with the ratings and labels as JSON strings
    res.render('index', {
        'ratings': JSON.stringify(ratings),
        'labels': JSON.stringify(labels)
    });
  });
});

// start the server
app.listen(3000, () => {
  console.log('Server started on port 3000...');
});
