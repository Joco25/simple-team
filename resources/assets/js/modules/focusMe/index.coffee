angular
    .module 'simple.team.focusMe', []

    .directive 'focusMe', ['$timeout', ($timeout) ->
        {
            scope:
                trigger: '=focusMe'
            link: (scope, element) ->
                scope.$watch 'trigger', (value) ->
                    if value is true
                        $timeout ->
                            element[0].focus()
        }
    ]
