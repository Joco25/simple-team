'use strict';
module.exports = [
  '$stateParams', '$www', '$state', '$rootScope', function($stateParams, $www, $state, $rootScope) {
    this.authUser = $rootScope.authUser;
    this.topicId = $stateParams.topicId;
    this.newPost = {};
    this.topic = null;
    this.filters = {
      showNewPost: false
    };
    this.selectPost = (function(_this) {
      return function(post) {
        _this.selectedPost = post;
        return _this.postCopy = angular.copy(post);
      };
    })(this);
    this.resetNewPost = (function(_this) {
      return function() {
        if (_this.selectedPost) {
          _this.selectedPost.showNewPost = false;
        }
        _this.filters.showNewPost = false;
        _this.selectedPost = void 0;
        _this.postCopy = angular.copy(void 0);
        return _this.newPost = {};
      };
    })(this);
    this.loadTopic = (function(_this) {
      return function() {
        return $www.get('/api/topics/' + _this.topicId).success(function(data) {
          if (data.error) {
            $state.go('conversations.list');
          }
          return _this.topic = data.topic;
        });
      };
    })(this);
    this.resetCurrentPost = (function(_this) {
      return function() {
        return _this.selectedPost.editMode = false;
      };
    })(this);
    this.updatePost = (function(_this) {
      return function() {
        _this.selectedPost.body = _this.postCopy.body;
        _this.selectedPost.editMode = false;
        return $www.put('/api/topicPosts/' + _this.postCopy.id, {
          body: _this.postCopy.body
        }).success(function() {
          return _this.postCopy = void 0;
        });
      };
    })(this);
    this.createPost = (function(_this) {
      return function() {
        _this.newPost.topic_id = _this.topicId;
        return $www.post('/api/topicPosts', _this.newPost).success(function(data) {
          _this.topic.posts.push(data.post);
          if (_this.selectedPost) {
            _this.selectedPost.posts = _this.selectedPost.posts || [];
            _this.selectedPost.posts.push(data.post);
          }
          return _this.resetNewPost();
        });
      };
    })(this);
    this.deletePost = (function(_this) {
      return function(postId) {
        return $www["delete"]('/api/topicPosts/' + postId).success(function() {
          return _this.topic.posts = _.reject(_this.topic.posts, {
            id: +postId
          });
        });
      };
    })(this);
    this.deleteTopic = function(topicId) {
      return $www["delete"]('/api/topics/' + topicId).success(function() {
        return $state.go('conversations.list');
      });
    };
    this.likePost = function(postId) {
      return $www.post('/api/topicPostLikes', {
        topic_post_id: postId
      });
    };
    this.unlikePost = function(postId) {
      return $www["delete"]('/api/topicPostLikes', {
        topic_post_id: postId
      });
    };
    this.togglePostUserLike = (function(_this) {
      return function(post) {
        if (post.is_liked) {
          _this.unlikePost(post.id);
        } else {
          _this.likePost(post.id);
        }
        return post.is_liked = !post.is_liked;
      };
    })(this);
    this.createTopicView = (function(_this) {
      return function() {
        return $www.post('/api/topicViews', {
          topic_id: _this.topicId
        });
      };
    })(this);
    this.loadUserNotification = (function(_this) {
      return function() {
        return $www.get('/api/topicNotifications/' + _this.topicId + '/users/' + _this.main.authUser.id + '/notification').success(function(data) {
          return _this.watchNotification = data.notification;
        });
      };
    })(this);
    this.createNotification = (function(_this) {
      return function() {
        return $www.post('/api/topicNotifications/' + _this.topicId + '/users/' + _this.main.authUser.id + '/notification').success(function(data) {
          return _this.watchNotification = data.notification;
        });
      };
    })(this);
    this.deleteNotification = (function(_this) {
      return function() {
        return $www["delete"]('/api/topicNotifications/' + _this.topicId + '/users/' + _this.main.authUser.id + '/notification').success(function() {
          return _this.watchNotification = false;
        });
      };
    })(this);
    this.toggleNotification = (function(_this) {
      return function() {
        if (_this.watchNotification) {
          return _this.deleteNotification();
        } else {
          return _this.createNotification();
        }
      };
    })(this);
    this.starTopic = function(topicId) {
      return $www.post('/api/topics/' + topicId + '/star');
    };
    this.unstarTopic = function(topicId) {
      return $www["delete"]('/api/topics/' + topicId + '/star');
    };
    this.toggleTopicUserStar = (function(_this) {
      return function(topic) {
        if (topic.is_starred) {
          _this.unstarTopic(topic.id);
        } else {
          _this.starTopic(topic.id);
        }
        return topic.is_starred = !topic.is_starred;
      };
    })(this);
    this.loadTopic();
    this.createTopicView();
  }
];

// ---
// generated by coffee-script 1.9.2
