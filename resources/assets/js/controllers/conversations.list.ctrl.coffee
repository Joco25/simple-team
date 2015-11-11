'use strict'

module.exports = [
    '$stateParams'
    '$www'
    ($stateParams, $www) ->
        @topics = []
        @filters =
            type: $stateParams.type or 'latest'
            busy: false
            page: 1
            disableInfiniteScroll: false

        @loadConversations = =>
            if @filters.busy
                return

            @filters.busy = true
            $www
                .get '/api/topics/' + @filters.type,
                    take: 50
                    page: @filters.page
                .success (data) =>
                    @topics = @topics.concat(data.topics)
                    @filters.busy = false
                    @filters.disableInfiniteScroll = if data.topics.length == 0 then true else false

        @nextPage = =>
            if @filters.busy
                return
            @filters.page += 1
            @loadConversations()

        @loadConversations()

        return
]
