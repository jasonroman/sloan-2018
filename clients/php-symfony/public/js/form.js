// redirect to the given league/ratings page based on the form input
function getLeaguePage()
{
    var form = $("form[name='league_ratings']");

    var league      = $("select[name='league_ratings[league]']", form).val();
    var ratingsType = $("select[name='league_ratings[ratingsType]']", form).val();

    window.location = '/teams/' + league + '/' + ratingsType;
}

// redirect to the given team/ratings page based on the form input
function getTeamPage()
{
    var form = $("form[name='team_ratings']");

    var team        = $("select[name='team_ratings[team]']", form).val();
    var ratingsType = $("select[name='team_ratings[ratingsType]']", form).val();

    window.location = '/teams/' + team + '/' + ratingsType;
}