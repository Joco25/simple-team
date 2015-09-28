'use strict'

angular
    .module 'simple.team.auth', []
    .service 'AuthService', ['$http', ($http) ->
        @user

        @loadUser = ->
            $http
                .get '/api/users/me'
                .success (data) =>
                    @user = data.user

        return
    ]
