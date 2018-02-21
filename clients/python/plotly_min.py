import plotly
import plotly.graph_objs as go
import requests

x, y = [], []
headers = {'X-Api-Username': 'sloan', 'X-Api-Key': 'sloan2018'}
response = requests.get('http://sloan.jayroman.com/api/stats/league-teams/nba/offense', headers=headers)

for team in response.json():
    x.append(team['full_name'])
    y.append(team['average_offense_rating'])

plotly.offline.plot([go.Bar(x=x, y=y)])
