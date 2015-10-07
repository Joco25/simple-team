'use strict'

module.exports = ($state, $stateParams, $modal) ->
    cardId = $stateParams.cardId

    init = =>
        @openModal()

    @openModal = (size) ->
        modalInstance = $modal.open({
            animation: false
            template: require '../layouts/card.modal.html'
            controller: require './card.modal.ctrl.coffee'
            controllerAs: 'ctrl'
            size: 'lg'
            resolve: {
                cardId: ->
                    cardId
            }
        })

        modalInstance.result.then((selectedItem) =>
            @closeEditCard()
        , =>
            @closeEditCard()
        )

    @closeEditCard = ->
        if $state.current.name.indexOf('projects') > -1
            return $state.go 'projects'

        $state.go 'tasklist'

    init()

    return
