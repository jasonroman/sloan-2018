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
response = requests.get('http://sloan.jayroman.com/api/stats/teams/nba/ratings', headers=headers)
teams = response.json()

offense_x = []
offense_y = []
defense_x = []
defense_y = []

# add the data to the x/y axes
for team in teams:
    offense_x.append(team['full_name'])
    offense_y.append(team['average_offense_rating'])
    defense_x.append(team['full_name'])
    defense_y.append(team['average_defense_rating'])

# set offense series as a bar chart, with color options and text labels
series_offense = go.Bar(
    x=offense_x,
    y=offense_y,
    name="NBA Team Offense",
    text=offense_y,
    textposition='auto',
    marker=dict(
        color='rgba(55, 128, 191, 0.7)',
        line=dict(
            color='rgba(55, 128, 191, 1.0)',
            width=2,
        )
    )
)

# set defense series as a bar chart, with color options and text labels
series_defense = go.Bar(
    x=defense_x,
    y=defense_y,
    name="NBA Team Defense",
    text=defense_y,
    textposition='auto',
    marker=dict(
        color='rgba(219, 64, 82, 0.7)',
        line=dict(
            color='rgba(219, 64, 82, 1.0)',
            width=2,
        )
    )
)

# add both series to the chart data
data = [series_offense, series_defense]

# set the chart options
layout = go.Layout(
    title='NBA Team Ratings',
    yaxis=dict(
        range=[95, 115]
    )
)

# create the figure based on the data and layout
fig = go.Figure(data=data, layout=layout)

# write the chart to an html file
plotly.offline.plot(fig, filename='plotly_nba_team_ratings.html')
