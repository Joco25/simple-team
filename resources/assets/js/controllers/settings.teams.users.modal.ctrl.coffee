'use strict'

module.exports = ($modalInstance, team, $http) ->
    @newEmail = ''

    init = =>
        @team = angular.copy team

    @deleteUser = (user) =>
        if ! confirm("Remove this user from the team?") then return

        _.remove @team.users, user
        $http.delete '/api/teams/user/' + team.id + '?user_id=' + user.id

    @inviteEmail = =>
        $http
            .post '/api/teams/user',
                email: @newEmail
                team_id: @team.id
            .success (data) =>
                @team = data.team
                @newEmail = ''

    @ok = ->
        $modalInstance.close(@team)

    @cancel = ->
        $modalInstance.dismiss 'cancel'

    init()

    return
