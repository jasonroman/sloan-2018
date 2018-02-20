import requests
import plotly
import plotly.graph_objs as go

headers = {
    'X-Api-Key': 'sloan2018',
    'X-Api-Username': 'sloan'
}

url = 'http://sloan.jayroman.com/api/stats/league-teams/nba/offense'

response = requests.get(url, headers=headers)

print(response.json())

x = []
y = []

for team in response.json():
    x.append(team['full_name'])
    y.append(team['average_offense_rating'])

print(x)
print(y)

plotly.offline.plot([go.Bar(x=x, y=y)], filename='test_chart.html')
