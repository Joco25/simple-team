'use strict'

module.exports = function($scope, $www, $state) {
    $scope.newTopic = {}

    $scope.createTopic = function() {
        if (!$scope.newTopic.name || !$scope.newTopic.body) {
            alert('You are missing some info!')
            return
        }

        $www.post('/api/topics', $scope.newTopic).success(function(data) {
            $state.go('conversations.view', {
                topicId: data.topic.id
            })
        })
    }
}
