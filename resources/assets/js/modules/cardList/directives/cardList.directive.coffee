'use strict'

module.exports = ($state, $http, $rootScope, CardListFiltersService, CardCacherService) ->
    class CtrlFunc
        constructor: ($scope) ->
            @authUser = $rootScope.authUser
            @filters =
                tag: null
                assignedTo: null
                quick: null
            @stage = $scope.data
            @searchInput = ''

            $rootScope.$on 'filters:update', (evt, data) =>
                @filters = data

            $rootScope.$on 'search:update', (evt, data) =>
                @searchInput = data

        appliedFilters: (card) =>
            CardListFiltersService.check(@filters, @authUser, card)

    {
        scope:
            data: '='
        controller: CtrlFunc
        controllerAs: 'ctrl'
    }
