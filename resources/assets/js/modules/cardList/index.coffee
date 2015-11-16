'use strict'

angular.module('simple.team.cardList', [])

    # Directives
    .directive('cardList', require('./directives/cardList.directive.coffee'))
    .directive('cardListItem', require('./directives/cardListItem.directive.coffee'))

    # Services
    .service('CardListFiltersService', require('./services/cardList.filters.service.coffee'))
