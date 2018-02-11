import plotly
import plotly.graph_objs as go
import requests

# set the authentication headers
headers = {
    'X-Api-Username': 'sloan',
    'X-Api-Key': 'sloan2018',
    'Accept': 'application/json'
}

# send the api request and get the response as json
response = requests.get('http://sloan.test:8000/api/stats/teams/nba/offense', headers=headers)
teams = response.json()

x = []
y = []

# add the data to the x/y axes
for team in teams:
    x.append(team['full_name'])
    y.append(team['average_offense_rating'])

# set the chart data as a bar chart
data = [go.Bar(x=x, y=y)]

# set the chart options
layout = go.Layout(
    title='NBA Team Offense',
    xaxis=dict(
        title='Team'
    ),
    yaxis=dict(
        title='Rating',
        range=[95, 120]
    )
)

# create the figure based on the data and layout
fig = go.Figure(data=data, layout=layout)

# write the chart to an html file
plotly.offline.plot(fig, filename='plotly_nba_team_offense.html')
