'use strict'

module.exports = ['$http', '$rootScope', 'TagDataService', ($http, $rootScope, TagData) ->
    @newCard = {}
    @users = []
    @filters =
        users: []
        tags: []
        projects: []

    init = =>
        @loadProjects()
        @loadTeamUsers()
        @loadTags()

    $rootScope.$on 'card:deleted', =>
        @loadProjects()

    $rootScope.$on 'projects:reload', =>
        @loadProjects()

    @toggleFilter = (filterName, obj) =>
        @filters[filterName] = @filters[filterName] || []

        if _.find @filters[filterName], { id: obj.id }
            _.remove @filters[filterName], obj
        else
            @filters[filterName].push obj

        @createCardList()

    @isFilterObjActive = (filterName, obj) =>
        _.find @filters[filterName], obj

    @clearFilters = (filterName) =>
        @filters[filterName] = []
        @createCardList()

    @loadProjects = ->
        $http
            .get '/api/projects'
            .success (data) =>
                @projects = data.projects
                @createCardList()

    @loadTags = ->
        TagData
            .loadTags()
            .success (data) =>
                @tags = data.tags

    @loadTeamUsers = ->
        $http
            .get '/api/users'
            .success (data) =>
                @users = data.users

    @createCard = =>
        $http
            .post '/api/cards/withoutStage', @newCard
            .success (data) =>
                @projects = data.projects
                @createCardList()
                @newCard.name = ''

    @createCardList = =>
        @cards = []
        _.each @projects, (project) =>
            _.each project.stages, (stage) =>
                # Projects filters
                if @filters.projects.length > 0 && _.findIndex(@filters.projects, { id: stage.project_id }) is -1
                    return

                _.each stage.cards, (card) =>
                    if @filters.users.length > 0 && ! _hasFilteredUsers(card)
                        return

                    if @filters.tags.length > 0 && ! _hasFilteredTags(card)
                        return

                    @cards.push card

    _hasFilteredTags = (card) =>
        found = false
        _.each @filters.tags, (tag) ->
            if _.findIndex(card.tags, { id: tag.id }) > -1
                found = true

        found

    _hasFilteredUsers = (card) =>
        found = false
        _.each @filters.users, (user) ->
            if _.findIndex(card.users, { id: user.id }) > -1
                found = true

        found

    init()

    return
]
