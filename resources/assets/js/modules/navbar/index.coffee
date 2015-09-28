'use strict'

angular
    .module 'simple.team.navbar', []
    .directive 'navbar', ->
        restrict: 'E'
        template: require('./view.html')
        controllerAs: 'navCtrl',
        controller: ['$firebaseArray', '$firebaseObject', 'config', '$state', 'Auth', 'localStorageService', ($firebaseArray, $firebaseObject, config, $state, Auth, localStorageService) ->
            @teams = []
            @selectedTeamId = localStorageService.get('selectedTeamId')
            @authUser = null

            init = =>
                Auth.$onAuth (authData) =>
                    @authData = authData
                    @loadTeams()

            @loadTeams = =>
                userTeamsRef = new Firebase(config.FIREBASE_URL + 'users/' + @authData.uid + '/teams')
                @userTeams = $firebaseArray(userTeamsRef)
                @userTeams.$loaded =>
                    _.each @userTeams, (userTeam) =>
                        teamRef = new Firebase(config.FIREBASE_URL + 'teams/' + userTeam.$id)
                        team = $firebaseObject(teamRef)
                        team.$loaded =>
                            @selectedTeam = if team.$id is @selectedTeamId then team else @selectedTeam
                            @teams.push team

            @setCurrentTeam = (team) =>
                @selectedTeam = team
                localStorageService.set('selectedTeamId', team.$id)
                userRef = new Firebase(config.FIREBASE_URL + 'users/' + @authData.uid)
                user = $firebaseObject(userRef)
                user.$loaded ->
                    user.team = team.$id
                    user.$save(user)
                    $state.go($state.current, {}, {reload: true})

            @deleteTeam = (team) =>
                if ! confirm 'Delete this team and all it\'s data?' then return
                @teams.$remove team

            @createTeam = ->
                teamName = prompt 'New team name?'
                users = {}
                users[@authData.uid] = true

                teamsRef = new Firebase(config.FIREBASE_URL + 'teams')
                teams = $firebaseArray(teamsRef)

                teams.$add
                    user: @authData.uid
                    name: teamName
                    users: users

            init()

            return

        ]
