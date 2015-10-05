'use strict'

module.exports = ['$rootScope', '$http', ($rootScope, $http) ->
    @teams = []

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
                @teams = data.teams

    init()

    return
]
