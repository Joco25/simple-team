'use strict'

module.exports = ['$stateParams', '$www', ($stateParams, $www) ->
        @topics = []
        @filters =
            type: $stateParams.type or 'latest'
            serieId: $stateParams.serieId or 'all'
            busy: false
            page: 1
            disableInfiniteScroll: false

        @loadTopics = ->
            if @filters.busy
                return
            @filters.busy = true
            $www.get('/api/topics/' + @filters.type,
                serie_id: @filters.serieId
                take: 50
                page: @filters.page).success (data) ->
                @topics = @topics.concat(data.topics)
                @filters.busy = false
                @filters.disableInfiniteScroll = if data.topics.length == 0 then true else false
                return
            return

        @selectSerieById = (serieId) ->
            @selectedSerie = _.findWhere(@main.series, id: +serieId)
            return

        @nextPage = ->
            if @filters.busy
                return
            @filters.page += 1
            @loadTopics()
            return

        @loadTopics()

        return
]
