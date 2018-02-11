from ggplot import *
import pandas as pd
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

# create the data frame for use in the plot
df = pd.DataFrame(data={'teams': x, 'ratings': y})

# create a bar chart with axis limits and maintaining list order vs. alphabetizing
g = ggplot(df, aes(x='teams', y='ratings', weight='ratings')) + \
    geom_bar() + \
    xlim(0, len(teams) + 0.5) + ylim(95, 115)

# print it!
print(g)
