'use strict'

module.exports = ['$http', '$rootScope', ($http, $rootScope) ->
    @dailySummaryBody = ''
    @dailySummaries = []
    @dailySummaryCopy = {}
    @usersById = {}

    init = =>
        @authUser = $rootScope.authUser
        @loadDailySummaries()

    groupDailySummariesUser = (dailySummaries) =>
        @dailySummaries = _.groupBy dailySummaries, (summary) =>
            @usersById[summary.user_id] = summary.user
            summary.user_id
        @selectedDailySummaries = @dailySummaries[@authUser.id]

    @loadDailySummaries = ->
        $http
            .get '/api/dailySummaries'
            .success (data) ->
                groupDailySummariesUser(data.dailySummaries)

    @deleteDailySummary = (userId, dailySummary) =>
        if ! confirm("Delete this daily summary?") then return
        $http.delete('/api/dailySummaries/' + dailySummary.id)
        _.remove(@dailySummaries[@authUser.id], dailySummary)

    @editDailySummary = (dailySummary) ->
        newDailySummaryBody = prompt('Edit daily summary', dailySummary.body)
        if ! newDailySummaryBody then return
        dailySummary.body = newDailySummaryBody
        @updateDailySummary(dailySummary)

    @updateDailySummary = (dailySummary) ->
        $http
            .put '/api/dailySummaries/' + dailySummary.id,
                body: dailySummary.body
            .success (data) ->
                groupDailySummariesUser data.dailySummaries

    @cancelDailySummary = =>
        @dailySummaryCopy = {}

    @createDailySummary = =>
        $http
            .post '/api/dailySummaries',
                body: @dailySummaryBody
            .success (data) ->
                groupDailySummariesUser(data.dailySummaries)
        @dailySummaryBody = ''

    init()

    return
]
