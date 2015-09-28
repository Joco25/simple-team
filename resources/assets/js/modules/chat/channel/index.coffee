module.exports = ['$firebaseArray', 'config', '$state', '$stateParams', ($firebaseArray, config, $state, $stateParams) ->
    @newMessageBody = ''

    init = =>
        channelId = $stateParams.channelId
        channelRef = new Firebase(config.FIREBASE_URL + 'channels')
        @channels = $firebaseArray(channelRef)
        @channels.$loaded (ref) =>
            @channel = ref.$getRecord channelId

    @createMessage = =>
        @channel.messages = @channel.messages || []
        @channel.messages.push {
            body: @newMessageBody
        }
        @newMessageBody = ''
        @channels.$save(@channel)

    @updateChannel = =>
        @channels.$save(@channel)

    init()

    return

]
