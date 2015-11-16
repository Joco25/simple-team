'use strict'

module.exports = () ->
    class CtrlFunc
        constructor: ($scope) ->
            @card = $scope.data

    {
        scope:
            data: '='
        controller: CtrlFunc
        controllerAs: 'ctrl'
        template: require('./views/cardListItem.html')
    }
