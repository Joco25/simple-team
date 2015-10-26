(function() {

    'use strict';

    angular.module('profile.social', [
        'profile.social.create',
        'profile.social.list',
        'profile.social.view'
    ])

        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('profile.social', {
                    url: '/social',
                    controller: 'SocialCtrl',
                    templateUrl: '/dist/apps/profile/social/social.tpl.html'
                });
        }])

        .controller('SocialCtrl', ['$scope', '$www', function($scope, $www) {
            $scope.main.mainNavHighlight = 'social';
            $scope.main.series = [];

            $scope.loadSeries = function() {
                $www.get('/api/series').success(function(data) {
                    $scope.main.series = data.series;
                    $scope.$broadcast('series:loaded');
                });
            };

            $scope.starTopic = function(topicId) {
                $www.post('/api/topics/' + topicId + '/star');
            };

            $scope.unstarTopic = function(topicId) {
                $www.delete('/api/topics/' + topicId + '/star');
            };

            $scope.toggleTopicUserStar = function(topic) {
                topic.is_starred ? $scope.unstarTopic(topic.id) : $scope.starTopic(topic.id);
                topic.is_starred = !topic.is_starred;
            };

            $scope.loadUsers = function() {
                $www.get('/api/users').success(function(data) {
                    $scope.main.users = data.users;
                    _.each($scope.main.users, function(user) {
                        user.handle = user.name.replace(/ /g,'').toLowerCase();
                    });
                });
            };

            $scope.loadUsers();
            $scope.loadSeries();
        }]);

})();