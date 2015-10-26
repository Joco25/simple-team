'use strict'

module.exports = ['$www', ($www) ->
    init = ->
        @loadUsers()

    @starTopic = (topicId) ->
        $www.post '/api/topics/' + topicId + '/star'

    @unstarTopic = (topicId) ->
        $www.delete '/api/topics/' + topicId + '/star'

    @toggleTopicUserStar = (topic) ->
        if topic.is_starred then @unstarTopic(topic.id) else @starTopic(topic.id)
        topic.is_starred = !topic.is_starred

    @loadUsers = ->
        $www.get('/api/users').success (data) ->
            @main.users = data.users
            _.each @main.users, (user) ->
                user.handle = user.name.replace(RegExp(' ', 'g'), '').toLowerCase()

    return
]
