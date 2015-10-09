'use strict'

module.exports = ($modalInstance, projects) ->

    init = =>
        @projects = angular.copy projects

    @ok = ->
        $modalInstance.close(@projects)

    @cancel = ->
        $modalInstance.dismiss 'cancel'

    init()

    return
