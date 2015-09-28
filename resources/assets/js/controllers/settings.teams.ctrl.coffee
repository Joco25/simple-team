'use strict'

module.exports = ['AuthService', (Auth) ->
    @teams = []
    @auth = Auth

    init = =>
        @authUser = angular.copy $rootScope.authUser
        @loadTeams()

    @loadTeams = ->
        $http
            .get '/api/teams'
            .success (data) =>
                @teams = data.teams

    @deleteTeam = (team) =>
        if ! confirm 'Delete this team and all it\'s data?' then return
        $http.delete '/api/teams/' + team.id
        _.remove @teams, team

    @createTeam = ->
        teamName = prompt 'New team name?'

        $http
            .post '/api/teams',
                name: teamName
            .success (data) =>
                @teams.push data.team

    init()

    return
]
