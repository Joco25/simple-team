'use strict'

angular
    .module 'simple.team.userData', []
    .service 'UserDataService', ['$http', ($http) ->
        @loadUsers = ->
            $http.get '/api/team/users'

        return
    ]
