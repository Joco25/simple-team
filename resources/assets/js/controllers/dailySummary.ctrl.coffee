'use strict'

module.exports = ['$http', ($http) ->
    @dailySummaryBody = ''
    @dailySummaries = []
    @dailySummaryCopy = {}
    @usersById = {}

    init = =>
        @loadDailySummaries()

    groupDailySummariesUser = (dailySummaries) =>
        @dailySummaries = _.groupBy dailySummaries, (summary) =>
            @usersById[summary.user_id] = summary.user
            summary.user_id

    @loadDailySummaries = ->
        $http
            .get '/api/dailySummaries'
            .success (data) ->
                groupDailySummariesUser(data.dailySummaries)

    @deleteDailySummary = (dailySummary) ->
        if ! confirm("Delete this daily summary?") then return

        $http.delete('/api/dailySummaries/' + dailySummary.id)

        _.remove(@dailySummaries, dailySummary)

    @editDailySummary = (dailySummary) ->
        @dailySummaryCopy = dailySummary

    @updateDailySummary = (dailySummary) ->
        $http
            .put '/api/dailySummaries/' + dailySummary.id,
                body: dailySummary.body
            .success (data) =>
                @dailySummaries = data.dailySummaries
                @dailySummaryCopy = {}

    @cancelDailySummary = =>
        @dailySummaryCopy = {}

    @createDailySummary = =>
        $http
            .post '/api/dailySummaries',
                body: this.dailySummaryBody
            , (data) =>
                @dailySummaries.unshift(data.dailySummary)
                @dailySummaryBody = ''

    init()

    return
]
