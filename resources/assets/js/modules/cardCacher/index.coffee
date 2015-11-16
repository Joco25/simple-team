'use strict'

angular.module('simple.team.cardCacher', [])

    # Services
    .service 'CardCacherService', ->
        @card = null

        {
            set: (card) =>
                @card = card
            get: =>
                card = @card
                @card = null
                card
        }
