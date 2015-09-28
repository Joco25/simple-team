'use strict'

module.exports = ['AuthService', '$rootScope', (Auth, $rootScope) ->
    init = =>
        @authUser = angular.copy $rootScope.authUser

    updateUser = =>
        $http
            .put '/api/me',
                name: @authUser.name
                email: @authUser.email

    init()

    return
]
