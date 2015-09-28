'use strict'

module.exports = ['$state', '$stateParams', '$scope', '$http', '$rootScope', 'TagDataService', 'Upload', ($state, $stateParams, $scope, $http, $rootScope, TagData, UserData, Upload) ->
    cardId = null
    stageId = null
    projectId = null
    @selectedCard = null
    @files = null
    @file = null
    @tagData = TagData

    @tagsConfig =
        valueField: 'name'
        labelField: 'name'
        delimiter: '|'
        placeholder: 'Add a tag...'
        create: true
        onChange: (tags) ->
            updateCardTags tags.split('|')

    @usersConfig =
        valueField: 'id'
        labelField: 'name'
        delimiter: '|'
        placeholder: 'Assign a user...'
        create: false
        onChange: (users) ->
            updateCardUsers users.split('|')

    init = =>
        cardId = $stateParams.cardId
        @loadTags()
        @loadCard()
        @loadTeamUsers()

    updateCardTags = (tags) =>
        $http
            .post '/api/cards/tags',
                tags: tags
                card_id: @selectedCard.id

    updateCardUsers = (userIds) =>
        $http
            .post '/api/cards/users',
                user_ids: userIds
                card_id: @selectedCard.id

    @loadTags = =>
        TagData
            .loadTags()
            .success (data) =>
                @tags = data.tags

    @loadTeamUsers = ->
        $http
            .get '/api/users'
            .success (data) =>
                @users = data.users

    @loadCard = =>
        $http
            .get '/api/cards/' + cardId
            .success (data) =>
                @selectedCard = data.card
                @selectedCard.tagNames = _.pluck data.card.tags, 'name'
                @selectedCard.userIds = _.pluck data.card.users, 'id'

    @updateCard = =>
        $http
            .put '/api/cards/' + cardId,
                name: @selectedCard.name
                description: @selectedCard.description
                blocked: @selectedCard.blocked
                impact: @selectedCard.impact
            .success (data) =>
                $rootScope.$broadcast 'card:updated', @selectedCard

    @deleteCard = =>
        if ! confirm 'Delete this card?' then return

        $rootScope.$broadcast 'card:deleted', @selectedCard

        $http
            .delete '/api/cards/' + @selectedCard.id
            .success (data) =>
                @closeEditCard()

    @closeEditCard = ->
        if $state.current.name.indexOf('projects') > -1
            return $state.go 'projects'

        $state.go 'tasklist'

    @updateCardName = =>
        @selectedCard.name = angular.copy @selectedCardName.replace("\n", '')
        @selectedCard.editName = false
        @updateCard()

    @selectCardDescription = =>
        @selectedCard.description = @selectedCard.description || ''
        @selectedCardDescription = angular.copy @selectedCard.description
        @showCardDescription = true

    @updateCardDescription = =>
        if ! @selectedCardDescription then return
        @selectedCard.description = angular.copy @selectedCardDescription
        @selectedCardDescription = null
        @showCardDescription = false
        @updateCard()

    @createSubtask = =>
        $http
            .post '/api/subtasks',
                body: angular.copy @newSubtaskBody
                checked: false
                card_id: cardId
            .success (data) =>
                @selectedCard.subtasks.push data.subtask

        @newSubtaskBody = ''

    @editSubtask = (task) ->
        task.editMode = true
        task.newBody = angular.copy task.body

    @updateSubtask = (task) ->
        task.editMode = false
        task.body = task.newBody
        task.newBody = null
        $http
            .put '/api/subtasks/' + task.id, task
            .success (data) ->

    @cancelSubtaskEdit = (task) ->
        task.editMode = false
        task.newBody = null

    @deleteSubtask = (task) =>
        if ! confirm 'Delete this subtask?' then return
        _.remove @selectedCard.subtasks, task
        $http.delete '/api/subtasks/' + task.id

    @toggleSubtask = (task) =>
        task.checked = !task.checked
        @updateTask(task)

    @updateTask = (task) ->
        $http.put '/api/subtasks/' + task.id, task

    @createComment = =>
        if ! @newCommentBody then return

        $http
            .post '/api/comments',
                body: @newCommentBody
                card_id: @selectedCard.id
            .success (data) =>
                @selectedCard.comments.push data.comment
        @newCommentBody = ''

    @deleteComment = (comment) =>
        if ! confirm 'Delete this comment?' then return

        $http.delete '/api/comments/' + comment.id
        _.remove @selectedCard.comments, comment

    @cancelCommentEdit = (comment) ->
        comment.editMode = false
        comment.newBody = null

    @updateComment = (comment) ->
        comment.body = angular.copy comment.newBody
        comment.editMode = false
        comment.newBody = null
        $http.put '/api/comments/' + comment.id, comment

    @editComment = (comment) ->
        comment.editMode = true
        comment.newBody = angular.copy comment.body

    @blockToggleSelectedCard = =>
        @selectedCard.blocked = !@selectedCard.blocked
        @updateCard()



    # upload later on form submit or something similar

    @submit = =>
        if form.file.$valid and @file and !@file.$error
            @upload @file

    # upload on file select or drop

    @upload = (file) =>
        Upload
            .upload
                url: 'api/cards/attachment'
                fields: 'card_id': @selectedCard.id
                file: file
            .progress (evt) ->
                progressPercentage = parseInt(100.0 * evt.loaded / evt.total)
                console.log 'progress: ' + progressPercentage + '% ' + evt.config.file.name
            .success (data, status, headers, config) ->
                console.log 'file ' + config.file.name + 'uploaded. Response: ' + data
            .error (data, status, headers, config) ->
                console.log 'error status: ' + status

    # for multiple files:

    @upload = (files) ->
        if files and files.length
            i = 0
            while i < files.length
                Upload.upload file: files[i]
                i++
        # or send them all together for HTML5 browsers:
        Upload.upload file: files


    init()

    return
]
