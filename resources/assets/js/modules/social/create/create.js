(function() {

    'use strict';

    angular.module('profile.social.create', [])

        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('profile.social.create', {
                    url: '/topics/create',
                    controller: 'SocialCreateCtrl',
                    templateUrl: '/dist/apps/profile/social/create/create.tpl.html'
                });
        }])

        .controller('SocialCreateCtrl', ['$scope', '$www', '$state', function($scope, $www, $state) {
            $scope.newTopic = {};

            $scope.createTopic = function() {
                if (!$scope.newTopic.name || !$scope.newTopic.body) {
                    toastr.error("You are missing some info!");
                    return;
                }

                $www.post('/api/topics', $scope.newTopic).success(function(data) {
                    $state.go('profile.social.view', { topicId: data.topic.id });
                });
            };
        }]);

})();