'use strict'

module.exports = ($state, $rootScope, CardListFiltersService, CardCacherService) ->
    class CtrlFunc
        constructor: ($scope) ->
            @authUser = $rootScope.authUser
            @cards = $scope.data
            @filters =
                tag: null
                assignedTo: null
                quick: null
            @searchInput = ''

            $rootScope.$on 'filters:update', (evt, data) =>
                @filters = data

            $rootScope.$on 'search:update', (evt, data) =>
                @searchInput = data

        openEditCard: (card) ->
            CardCacherService.set(card)
            $state.go 'projects.card',
                cardId: card.id


        appliedFilters: (card) =>
            CardListFiltersService.check(@filters, @authUser, card)

    {
        scope:
            data: '='
        controller: CtrlFunc
        controllerAs: 'ctrl'
        template: require('./views/cardList.html')
    }
