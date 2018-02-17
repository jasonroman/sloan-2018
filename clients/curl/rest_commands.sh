#!/bin/bash

# This is a simple set of example cURL commands (https://curl.haxx.se/) to interact with a REST API.
# You do not have to run these from a shell script; they can be ran directly from the command-line if cURL is installed

# The following is an explanation of the command-line flags used below
#   -i - show the response headers
#   -s - do not show the network output
#   -H - add a header to the request
#   -F - add a form value to the request
#   -X - specify the type of request (GET, POST, etc.)

# retrieve list of users where no authentication is required
curl http://sloan.jayroman.com/public/api/users

# retrieve list of users with passwords
curl -s http://sloan.jayroman.com/public/api/users/secret

# attempt to access a URL that does not exist - will return a 404 not found response
curl -is http://sloan.jayroman.com/public/api/users/all

# attempt to retrieve the list of teams without authentication - will return a 401 unauthorized response
curl -is http://sloan.jayroman.com/api/teams

# pass authentication headers to retrieve the list of teams with stats
curl -is -H "X-Api-Username: sloan" -H "X-Api-Key: sloan2018" http://sloan.jayroman.com/api/teams

# pass the wrong authentication headers - will return a 403 access forbidden response
curl -is -H "X-Api-Username: badusername" -H "X-Api-Key: sloan2018" http://sloan.jayroman.com/api/teams
curl -is -H "X-Api-Username: sloan" -H "X-Api-Key: badpassword" http://sloan.jayroman.com/api/teams

# try to add a get users with a POST request instead of GET - will return a 405 method not allowed response
curl -is -X "POST" http://127.0.0.1:8000/public/api/users

# starting to get a bit unruly...separate out parameters on their own lines for clarity

# add a new user and get their id and api key as the response 0 will return a 201 created
# response with the location where the newly created user can be accessed
curl -is \
    -X "POST" \
    -H "X-Api-Username: sloan" \
    -H "X-Api-Key: sloan2018" \
    -F 'username=newuser' \
    http://sloan.jayroman.com/api/users

# try to add a new user that already exists - will return a 400 bad request response
curl -is \
    -H "X-Api-Username: sloan" \
    -H "X-Api-Key: sloan2018" \
    -F 'username=jason' \
    http://sloan.jayroman.com/api/users

# update a user's email address by user id
curl -is \
    -X "PATCH" \
    -H "X-Api-Username: newuser" \
    -H "X-Api-Key: 9wM78XZV" \
    -H "Content-Type: application/json" \
    --data '{"email": "test@test.com"}' \
    http://sloan.jayroman.com/api/users/3

# delete a user by user id
curl -is \
    -X "DELETE" \
    -H "X-Api-Username: newuser" \
    -H "X-Api-Key: 9wM78XZV" \
    http://sloan.jayroman.com/api/users/3


