(function() {

    'use strict';

    angular.module('conversations.list', [
        'infinite-scroll'
    ])

        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('conversations.list', {
                    url: '/topics?serieId&type',
                    controller: 'SocialListCtrl',
                    templateUrl: '/dist/apps/profile/social/list/list.tpl.html'
                });
        }])

        .controller('SocialListCtrl', ['$scope', '$stateParams', '$www', function($scope, $stateParams, $www) {
            $scope.topics = [];
            $scope.filters = {
                type: $stateParams.type || 'latest',
                serieId: $stateParams.serieId || 'all',
                busy: false,
                page: 1,
                disableInfiniteScroll: false
            };

            $scope.loadTopics = function() {
                if ($scope.filters.busy) {
                    return;
                }
                $scope.filters.busy = true;

                $www.get('/api/topics/' + $scope.filters.type, {
                    serie_id: $scope.filters.serieId,
                    take: 50,
                    page: $scope.filters.page
                }).success(function(data) {
                    $scope.topics = $scope.topics.concat(data.topics);
                    $scope.filters.busy = false;
                    $scope.filters.disableInfiniteScroll = data.topics.length === 0 ? true : false;
                });
            };

            $scope.selectSerieById = function(serieId) {
                $scope.selectedSerie = _.findWhere($scope.main.series, { id: +serieId });
            };

            $scope.nextPage = function() {
                if ($scope.filters.busy) {
                    return;
                }

                $scope.filters.page += 1;
                $scope.loadTopics();
            };

            $scope.loadTopics();
            if ($scope.main.series) {
                $scope.selectSerieById($scope.filters.serieId);
            }
            $scope.$on('series:loaded', function() {
                $scope.selectSerieById($scope.filters.serieId);
            });
        }]);

})();