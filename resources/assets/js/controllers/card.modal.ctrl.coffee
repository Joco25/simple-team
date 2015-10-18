'use strict'

module.exports = ($state, $stateParams, $scope, $http, $rootScope, TagDataService, Upload, $modalInstance, cardId, $timeout) ->
    stageId = null
    projectId = null
    @selectedCard = null
    @files = null
    @file = null
    @tagData = TagDataService
    @s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
    @selectedCard =
        impact: 0

    @states =
        uploading: false

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
        @loadTags()
        @loadCard()
        @loadTeamUsers()

    updateCardTags = (tags) =>
        $http
            .post '/api/cards/tags',
                tags: tags
                card_id: @selectedCard.id
            .success ->
                $rootScope.$emit 'projects:reload'

    updateCardUsers = (userIds) =>
        $http
            .post '/api/cards/users',
                user_ids: userIds
                card_id: @selectedCard.id
            .success ->
                $rootScope.$emit 'projects:reload'

    @updateStage = (stage) =>
        if stage.id is @selectedCard.stage.id then return
        @selectedCard.stage = stage
        $http
            .put '/api/cards/' + @selectedCard.id + '/updateStage',
                stage_id: stage.id
            .success (data) =>
                @selectedCard = data.card
                @selectedCard.tagNames = _.pluck data.card.tags, 'name'
                @selectedCard.userIds = _.pluck data.card.users, 'id'
                @selectedCard.impact = @selectedCard.impact || 0
                $rootScope.$emit 'projects:reload'

    @loadTags = =>
        TagDataService
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
                @selectedCard.impact = @selectedCard.impact || 0

    cardImpactHandle = null
    @updateCardImpact = =>
        $timeout.cancel(cardImpactHandle)
        cardImpactHandle = $timeout =>
            @updateCard()
        , 500

    @updateCard = =>
        $http
            .put '/api/cards/' + cardId,
                name: @selectedCard.name
                description: @selectedCard.description
                blocked: @selectedCard.blocked
                impact: @selectedCard.impact
            .success ->
                $rootScope.$emit 'projects:reload'

    @deleteCard = =>
        if ! confirm 'Delete this card?' then return

        $http
            .delete '/api/cards/' + @selectedCard.id
            .success (data) =>
                $rootScope.$emit 'projects:reload'
                @ok()

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
        @newSubtaskBody = @newSubtaskBody.replace("\n", '')
        $http
            .post '/api/subtasks',
                body: @newSubtaskBody
                checked: false
                card_id: cardId
            .success (data) =>
                @selectedCard.subtasks.push data.subtask
                $rootScope.$emit 'projects:reload'

        @newSubtaskBody = ''

    @editSubtask = (task) ->
        task.editMode = true
        task.newBody = angular.copy task.body

    @updateSubtask = (task) ->
        task.editMode = false
        task.body = task.newBody.replace("\n", '')
        task.newBody = null
        $http
            .put '/api/subtasks/' + task.id, task
            .success (data) ->
                $rootScope.$emit 'projects:reload'

    @cancelSubtaskEdit = (task) ->
        task.editMode = false
        task.newBody = null

    @deleteSubtask = (task) =>
        if ! confirm 'Delete this subtask?' then return
        _.remove @selectedCard.subtasks, task
        $http
            .delete '/api/subtasks/' + task.id
            .success ->
                $rootScope.$emit 'projects:reload'

    @toggleSubtask = (task) =>
        task.checked = !task.checked
        @updateTask(task)

    @updateTask = (task) ->
        $http
            .put '/api/subtasks/' + task.id, task
            .success ->
                $rootScope.$emit 'projects:reload'

    @createComment = =>
        if ! @newCommentBody then return

        $http
            .post '/api/comments',
                body: @newCommentBody
                card_id: @selectedCard.id
            .success (data) =>
                @selectedCard.comments.push data.comment
                $rootScope.$emit 'projects:reload'
        @newCommentBody = ''

    @deleteComment = (comment) =>
        if ! confirm 'Delete this comment?' then return

        $http
            .delete '/api/comments/' + comment.id
            .success ->
                $rootScope.$emit 'projects:reload'

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
                url: '/api/attachments'
                fields: 'card_id': cardId
                file: file
            .progress (evt) =>
                @states.uploading = true
                progressPercentage = parseInt(100.0 * evt.loaded / evt.total)
                # console.log 'progress: ' + progressPercentage + '% ' + evt.config.file.name
            .success (data, status, headers, config) =>
                # console.log 'file ' + config.file.name + 'uploaded. Response: ' + data
                @states.uploading = false
                @selectedCard.attachments.push(data.attachment)
            .error (data, status, headers, config) ->
                @states.uploading = false
                console.log 'error status: ' + status

    # for multiple files:
    @uploadFiles = (files) =>
        console.log 'files', files
        if files and files.length
            i = 0
            while i < files.length
                @upload(files[i])
                i++

    @deleteAttachment = (attachment) =>
        if ! confirm('Delete this attachment?') then return
        $http.delete('/api/attachments/' + attachment.id)
        _.remove @selectedCard.attachments, attachment

    @downloadAttachment = (attachment) ->
        a = document.createElement("a")
        a.download = attachment.filename
        a.title = attachment.original_filename
        a.href = attachment.file_url
        a.click()
        return

    @ok = ->
        $modalInstance.close()

    @cancel = ->
        $modalInstance.dismiss 'cancel'

    init()

    return
