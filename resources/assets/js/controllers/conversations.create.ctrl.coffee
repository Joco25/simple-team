'use strict'

module.exports = [
    '$scope'
    '$www'
    '$state'
    ($scope, $www, $state) ->
        $scope.newTopic = {}

        $scope.createTopic = ->
            if !$scope.newTopic.name or !$scope.newTopic.body
                toastr.error 'You are missing some info!'
                return
            $www.post('/api/topics', $scope.newTopic).success (data) ->
                $state.go 'profile.social.view', topicId: data.topic.id
                return
            return

        return
]
