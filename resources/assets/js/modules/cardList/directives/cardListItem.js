module.exports = class CardListItem {
    constructor() {
        this.scope = {
          data: '='
        }
        this.controllerAs = 'ctrl'
        this.template = require('./views/cardListItem.html')
    }

    controller($scope) {
        this.card = $scope.data
    }
}
