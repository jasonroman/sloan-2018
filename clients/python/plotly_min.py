import plotly
import requests

x, y = [], []
headers = {'X-Api-Username': 'sloan', 'X-Api-Key': 'sloan2018'}
response = requests.get('http://sloan.test:8000/api/stats/teams/nba/offense', headers=headers)

for team in response.json():
    x.append(team['full_name'])
    y.append(team['average_offense_rating'])

plotly.offline.plot([plotly.graph_objs.Bar(x=x, y=y)], filename='plotly_min.html')
