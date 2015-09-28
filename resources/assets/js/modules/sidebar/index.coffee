angular
    .module 'simple.team.sidebar', []

    .directive 'sidebar', ->
        restrict: 'E'
        template: require('./view.html')
