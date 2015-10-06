'use strict'

require 'moment'
require 'angular-ui-router'
require 'angular-ui-sortable'
require 'angular-sanitize'
require 'angular-gravatar'
require 'angular-elastic'
require 'angular-local-storage'
require 'angular-loading-bar'
require 'ng-file-upload'
require 'ng-showdown'

require './routes.coffee'
require './modules/focusMe/index.coffee'
require './modules/selectize'
require './modules/tagData/index.coffee'
require './modules/userData/index.coffee'
require './modules/auth/index.coffee'
require './modules/sidebar/index.coffee'
require './modules/navbar/index.coffee'

angular
    .module 'simple.team', [
        'ngSanitize'
        'ui.router'
        'ui.sortable'
        'ui.gravatar'
        'ui.bootstrap'
        'selectize'
        'ngFileUpload'
        'ng-showdown'
        'LocalStorageModule'
        'angular-loading-bar'
        'monospaced.elastic'
        'simple.team.init'
        'simple.team.routes'
        'simple.team.focusMe'
        'simple.team.sidebar'
        'simple.team.navbar'
        'simple.team.auth'
        'simple.team.tagData'
        'simple.team.userData'
    ]

    .config ['$urlRouterProvider', 'cfpLoadingBarProvider', ($urlRouterProvider, cfpLoadingBarProvider) ->
        $urlRouterProvider.otherwise '/projects'
        cfpLoadingBarProvider.includeSpinner = false
        return
    ]

    .controller 'AppCtrl', ['$state', '$http', 'InitService', '$rootScope', ($state, $http, Init, $rootScope) ->
        $rootScope.teams = angular.copy Init.teams
        $rootScope.authUser = angular.copy Init.authUser
        @state = $state

        @teams = $rootScope.teams
        $rootScope.$broadcast 'teams:loaded', @teams
        @authUser = $rootScope.authUser
        $rootScope.$broadcast 'user:loaded', @authUser
        $rootScope.$on 'teams:reload', @loadTeams

        init = =>
            @loadTeams()

        @loadTeams = ->
            $http
                .get '/api/teams'
                .success (data) =>
                    @teams = data.teams

        @setCurrentTeam = (team) ->
            previousTeam = angular.copy @authUser.team
            @authUser.team = team
            $http
                .put '/api/me/team',
                    team_id: team.id
                .success (data) =>
                    if ! data.success then return @authUser.team = previousTeam

                    $state.go($state.current, {}, {reload: true})
                    $rootScope.$broadcast 'team:changed'

        init()

        return
    ]
