'use strict'

module.exports = ['$rootScope', '$http', '$modal', ($rootScope, $http, $modal) ->
    @teams = []

    init = =>
        @authUser = angular.copy $rootScope.authUser
        @loadTeams()

    @loadTeams = ->
        $http
            .get '/api/teams'
            .success (data) =>
                @teams = data.teams

    @openTeamUsers = (team) ->
        $modal
            .open({
                template: require '../layouts/settings.teams.users.modal.html'
                controller: require './settings.teams.users.modal.ctrl.coffee'
                controllerAs: 'ctrl'
                size: 'md'
                resolve: {
                    team: ->
                        team
                }
            })
            .result
            .then (team) ->
                console.log team

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
