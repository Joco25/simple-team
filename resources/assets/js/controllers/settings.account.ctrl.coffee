'use strict'

module.exports = ['$http', '$rootScope', ($http, $rootScope) ->
    init = =>
        @authUser = angular.copy $rootScope.authUser

    @updateUser = =>
        $http
            .put '/api/me',
                name: @authUser.name
                email: @authUser.email

    @updatePassword = =>
        $http
            .put '/api/me/password',
                password: @password
                password_confirm: @passwordConfirm

    init()

    return
]
