'use strict'

module.exports = ($http, $state, $rootScope, $modal, CardCacherService) ->
    @s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
    @authUser = $rootScope.authUser
    @filters =
        tag: null
        assignedTo: null
        quick: null
    @team = $rootScope.authUser.team
    @projects = []
    @tags = []
    @currentUser = null
    @selectedStage = null
    @selectedCommentBody = ''
    @selectedTaskBody = ''
    @searchInput = ''
    @sortableOptions =
        placeholder: "sortable-preview"
        connectWith: ".sortable"
        delay: 100
        stop: (evt, ui) =>
            if ui.item.sortable.droptarget
                stage = ui.item.sortable.droptarget.scope().stage
                @updateStageCards(stage)

    $rootScope.$on 'projects:reload', =>
        @loadProjects()

    init = =>
        @loadProjects()
        @loadTags()

    @openEditCard = (card) ->
        CardCacherService.set(card)
        $state.go 'projects.card',
            cardId: card.id

    @updateStageCards = (stage) ->
        cardIds = _.pluck(stage.cards, 'id')
        $http
            .put '/api/cards/stageOrder',
                card_ids: cardIds
                stage_id: stage.id

    @updateProjectOrder = (projects) ->
        projectIds = _.pluck projects, 'id'
        $http
            .post '/api/projects/order',
                project_ids: projectIds

    @selectAssignedToFilter = (newFilter) =>
        @filters.assignedTo = newFilter
        $rootScope.$broadcast('filters:update', @filters)

    @selectTagFilter = (newFilter) =>
        @filters.tag = newFilter
        $rootScope.$broadcast('filters:update', @filters)

    @selectQuickFilter = (newFilter) =>
        @filters.quick = newFilter
        $rootScope.$broadcast('filters:update', @filters)

    @updateSearchInput = =>
        $rootScope.$broadcast('search:update', @searchInput)

    @clearSearchInput = =>
        @searchInput = ''
        @updateSearchInput()

    @loadTags = =>
        $http
            .get '/api/tags'
            .success (data) =>
                @tags = data.tags

    @openSortableProjects = ->
        $modal
            .open {
                template: require '../layouts/sortableProjects.modal.html'
                controller: require './sortableProjects.modal.ctrl.coffee'
                controllerAs: 'ctrl'
                size: 'md'
                resolve: {
                    projects: =>
                        @projects
                }
            }
            .result
            .then (projects) =>
                @projects = projects
                @updateProjectOrder(projects)

    @loadProjects = ->
        $http
            .get '/api/projects'
            .success (data) =>
                @projects = data.projects

    @openSortStagesModal = =>
        @stagesCopy = angular.copy @stages

    @updateStagesOrder = (stagesCopy) =>
        _.each stagesCopy, (stage, key) =>
            realStage = _.find(@stages, { $id: stage.$id })
            realStage.$priority = key
            @stages.$save(realStage)

    @deleteProject = (project) =>
        if ! confirm("Delete '" + project.name + "' and all it's contents?") then return
        _.remove @projects, project
        $http.delete '/api/projects/' + project.id

    @createProject = ->
        projectName = prompt("New Project Name")
        if ! projectName then return

        $http
            .post '/api/projects',
                name: projectName
                stages: [
                    {
                        name: 'Open'
                    },
                    {
                        name: 'In Progress'
                    },
                    {
                        name: 'Closed'
                    }
                ]
            .success (data) =>
                @projects = data.projects

    @deleteStage = (project, stageIndex) ->
        if confirm 'Delete this stage?'
            project.stages.splice stageIndex, 1

    @toggleProjectVisibility = (project) ->
        project.hidden = !project.hidden

    @editStage = (stage) ->
        newStageName = prompt('Edit Stage Name', stage.name)
        if (! newStageName) then return
        stage.name = newStageName

    @deleteAllCardsInStage = (stage) ->
        if ! confirm('Delete all cards in this stage?') then return
        stage.cards = []
        $http.delete '/api/stages/' + stage.id + '/cards'

    @createStage = =>
        stageName = prompt("Stage name")
        if (stageName is null) then return

        newStage = {
            id: createId()
            name: stageName
            createdAt: (new Date).getTime()
        }

        result = @stages.$add(newStage)

        _.each @projects, (project) =>
            project.stages = project.stages || []
            project.stages.push(newStage)
            @projects.$save(project)

    @createCard = (project) =>
        newCardName = prompt 'Task description'
        if ! newCardName then return

        $http
            .post '/api/cards',
                stage_id: project.stages[0].id
                name: newCardName
            .success (data) ->
                project.stages[0].cards.push data.card

        @newCardName = ''

    @editProject = (project) ->
        projectName = prompt("Project name", project.name)
        if (projectName is null) then return

        project.name = projectName

        $http
            .put '/api/projects/' + project.id,
                name: project.name
            .success (data) =>
                projectIndex = _.indexOf(@projects, _.find(@projects, { id: project.id }))
                if cardIndex > -1
                    @projects.splice(projectIndex, 1, project)

    init()

    return
