module.exports = ['$firebaseArray', 'config', '$state', ($firebaseArray, config, $state) ->
    init = =>
        channelsRef = new Firebase(config.FIREBASE_URL + 'channels')
        @channels = $firebaseArray(channelsRef)

    @createChannel = ->
        @channels.$add({
            name: ''
        }).then (ref) ->
            id = ref.key()
            $state.go 'chat.channel', { channelId: id }

    init()

    return

]
