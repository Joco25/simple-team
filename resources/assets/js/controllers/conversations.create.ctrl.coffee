'use strict'

module.exports = [
    '$scope'
    '$www'
    '$state'
    ($scope, $www, $state) ->
        $scope.newTopic = {}

        $scope.createTopic = ->
            if !$scope.newTopic.name or !$scope.newTopic.body
                alert 'You are missing some info!'

            $www
                .post('/api/topics', $scope.newTopic)
                .success (data) ->
                    $state.go 'conversations.view', topicId: data.topic.id
]
