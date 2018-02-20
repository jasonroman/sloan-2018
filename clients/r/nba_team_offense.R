require("httr")
require("jsonlite")
require("ggplot2")

# get the response from the api request, passing in the appropriate authentication headers
response = GET("http://sloan.jayroman.com/api/stats/league-teams/nba/offense", add_headers(
  'X-Api-Username' = 'sloan',
  'X-Api-Key' = 'sloan2018'
))

# convert the teams to a list from the JSON response
teams = fromJSON(
  content(response, "text", encoding="UTF-8"),
  simplifyVector = FALSE
)

# initialize where the team names and ratings will be stored
teamNames = c()
ratings = c()

# loop through each team and add the name and rating to the corresponding lists
for(i in 1:length(teams)) {
  teamNames[i] = teams[[i]]['full_name']
  ratings[i] = teams[[i]]['average_offense_rating']
}

# create the data frame for use in the plot
df = data.frame(teams = unlist(teamNames), ratings = unlist(ratings))

# create a bar chart with axis limits and maintaining list order vs. alphabetizing
ggplot(df, aes(teams, ratings)) +
  geom_bar(stat="identity") +
  coord_cartesian(xlim=c(1, length(teams)), ylim=c(95, 115)) +
  scale_x_discrete(limits=df$teams)
