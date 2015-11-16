'use strict'

module.exports = [
    '$stateParams'
    '$www'
    '$state'
    '$rootScope'
    ($stateParams, $www, $state, $rootScope) ->
        @authUser = $rootScope.authUser
        @topicId = $stateParams.topicId
        @newPost = {}
        @topic = null
        @filters = showNewPost: false

        @selectPost = (post) =>
            @selectedPost = post
            @postCopy = angular.copy(post)

        @resetNewPost = =>
            if @selectedPost then @selectedPost.showNewPost = false
            @filters.showNewPost = false
            @selectedPost = undefined
            @postCopy = angular.copy(undefined)
            @newPost = {}

        @loadTopic = =>
            $www
                .get '/api/topics/' + @topicId
                .success (data) =>
                    if data.error then $state.go 'conversations.list'
                    @topic = data.topic

        @resetCurrentPost = =>
            @selectedPost.editMode = false

        @updatePost = =>
            @selectedPost.body = @postCopy.body
            @selectedPost.editMode = false
            $www
                .put '/api/topicPosts/' + @postCopy.id,
                    body: @postCopy.body
                .success =>
                    @postCopy = undefined

        @createPost = =>
            @newPost.topic_id = @topicId
            $www
                .post '/api/topicPosts', @newPost
                .success (data) =>
                    @topic.posts.push data.post
                    if @selectedPost
                        @selectedPost.posts = @selectedPost.posts or []
                        @selectedPost.posts.push data.post
                    @resetNewPost()

        @deletePost = (postId) =>
            $www
                .delete '/api/topicPosts/' + postId
                .success =>
                    @topic.posts = _.reject(@topic.posts, id: +postId)

        @deleteTopic = (topicId) =>
            $www
                .delete '/api/topics/' + topicId
                .success =>
                    $state.go 'conversations.list'

        @likePost = (postId) =>
            $www.post '/api/topicPostLikes/' + postId

        @unlikePost = (postId) =>
            $www.delete '/api/topicPostLikes/' + postId

        @togglePostUserLike = (post) =>
            if post.is_liked then @unlikePost(post.id) else @likePost(post.id)
            post.is_liked = !post.is_liked

        @createTopicView = =>
            $www.post '/api/topicViews',
                topic_id: @topicId

        @loadUserNotification = =>
            $www
                .get('/api/topicNotifications/' + @topicId + '/users/' + @main.authUser.id + '/notification')
                .success (data) =>
                    @watchNotification = data.notification

        @createNotification = =>
            $www
                .post('/api/topicNotifications/' + @topicId + '/users/' + @main.authUser.id + '/notification')
                .success (data) =>
                    @watchNotification = data.notification

        @deleteNotification = =>
            $www
                .delete('/api/topicNotifications/' + @topicId + '/users/' + @main.authUser.id + '/notification')
                .success =>
                    @watchNotification = false

        @toggleNotification = =>
            if @watchNotification then @deleteNotification() else @createNotification()

        @starTopic = (topicId) ->
            $www.post('/api/topics/' + topicId + '/star')

        @unstarTopic = (topicId) ->
            $www.delete('/api/topics/' + topicId + '/star')

        @toggleTopicUserStar = (topic) =>
            if topic.is_starred then @unstarTopic(topic.id) else @starTopic(topic.id)
            topic.is_starred = !topic.is_starred

        @loadTopic()
        @createTopicView()
        # @loadUserNotification()

        return
]
