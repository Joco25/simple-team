'use strict'

angular
    .module 'simple.team.tagData', []
    .service 'TagDataService', ['$http', ($http) ->
        @loadTags = ->
            $http.get '/api/tags'

        return
    ]
