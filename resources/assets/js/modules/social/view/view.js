(function() {

    'use strict';

    angular.module('profile.social.view', [])

        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('profile.social.view', {
                    url: '/topics/:topicId',
                    controller: 'SocialViewCtrl',
                    templateUrl: '/dist/apps/profile/social/view/view.tpl.html'
                });
        }])

        .controller('SocialViewCtrl', ['$scope', '$stateParams', '$www', '$state', function($scope, $stateParams, $www, $state) {
            $scope.topicId = $stateParams.topicId;
            $scope.newPost = {};
            $scope.filters = {
                showNewPost: false
            };

            $scope.selectPost = function(post) {
                $scope.selectedPost = post;
                $scope.postCopy = angular.copy(post);
            };

            $scope.resetNewPost = function() {
                if ($scope.selectedPost) {
                    $scope.selectedPost.showNewPost = false;
                }

                $scope.filters.showNewPost = false;
                $scope.selectedPost = undefined;
                $scope.postCopy = angular.copy(undefined);
                $scope.newPost = {};
            };

            $scope.loadTopic = function() {
                $www.get('/api/topics/' + $scope.topicId).success(function(data) {
                    if (data.error) {
                        $state.go('profile.social.list');
                        return;
                    }

                    $scope.topic = data.topic;
                });
            };

            $scope.updatePost = function() {
                $scope.selectedPost.body = $scope.postCopy.body;
                $scope.selectedPost.editMode = false;
                $www.put('/api/posts/' + $scope.postCopy.id, {
                    body: $scope.postCopy.body
                }).success(function() {
                    $scope.postCopy = undefined;
                });
            };

            $scope.createPost = function() {
                $www.post('/api/topics/' + $scope.topicId + '/post', $scope.newPost).success(function(data) {
                    $scope.topic.posts.push(data.post);
                    if ($scope.selectedPost) {
                        $scope.selectedPost.posts = $scope.selectedPost.posts || [];
                        $scope.selectedPost.posts.push(data.post);
                    }

                    $scope.resetNewPost();
                });
            };

            $scope.deletePost = function(postId) {
                $www.delete('/api/posts/' + postId).success(function() {
                    $scope.topic.posts = _.reject($scope.topic.posts, { id: +postId });
                });
            };

            $scope.deleteTopic = function(topicId) {
                $www.delete('/api/topics/' + topicId).success(function() {
                    $state.go('profile.social.list');
                });
            };

            $scope.likePost = function(postId) {
                $www.post('/api/posts/' + postId + '/like');
            };

            $scope.unlikePost = function(postId) {
                $www.delete('/api/posts/' + postId + '/like');
            };

            $scope.togglePostUserLike = function(post) {
                post.is_liked ? $scope.unlikePost(post.id) : $scope.likePost(post.id);
                post.is_liked = !post.is_liked;
            };

            $scope.createTopicView = function() {
                $www.post('/api/topics/' + $scope.topicId + '/view');
            };

            $scope.loadUserNotification = function() {
                $www.get('/api/topics/' + $scope.topicId + '/users/' + $scope.main.authUser.id + '/notification').success(function(data) {
                    $scope.watchNotification = data.notification;
                });
            };

            $scope.createNotification = function() {
                $www.post('/api/topics/' + $scope.topicId + '/users/' + $scope.main.authUser.id + '/notification').success(function(data) {
                    $scope.watchNotification = data.notification;
                });
            };

            $scope.deleteNotification = function() {
                $www.delete('/api/topics/' + $scope.topicId + '/users/' + $scope.main.authUser.id + '/notification').success(function() {
                    $scope.watchNotification = false;
                });
            };

            $scope.toggleNotification = function() {
                $scope.watchNotification ? $scope.deleteNotification() : $scope.createNotification();
            };

            $scope.loadTopic();
            $scope.createTopicView();
            $scope.loadUserNotification();
        }]);

})();