angular
    .module 'simple.team.routes', []
    .config ['$stateProvider', ($stateProvider) ->
        $stateProvider
            .state 'projects',
                url: '/projects'
                template: require('./layouts/projects.html')
                controller: require('./controllers/projects.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'projects.card',
                url: '/card/?cardId'
                template: require('./layouts/card.html')
                controller: require('./controllers/card.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'tasklist',
                url: '/tasklist'
                template: require('./layouts/tasklist.html')
                controller: require('./controllers/tasklist.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'tasklist.card',
                url: '/card/?cardId'
                template: require('./layouts/card.html')
                controller: require('./controllers/card.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'dailySummary',
                url: '/dailySummary'
                template: require('./layouts/dailySummary.html')
                controller: require('./controllers/dailySummary.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'timeline',
                url: '/timeline'
                template: require('./layouts/timeline.html')
                controller: require('./controllers/timeline.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'settings',
                url: '/settings'
                template: require('./layouts/settings.html')
                controller: require('./controllers/settings.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'settings.account',
                url: '/account'
                template: require('./layouts/settings.account.html')
                controller: require('./controllers/settings.account.ctrl.coffee')
                controllerAs: 'ctrl'

            .state 'settings.teams',
                url: '/teams'
                template: require('./layouts/settings.teams.html')
                controller: require('./controllers/settings.teams.ctrl.coffee')
                controllerAs: 'ctrl'

            #
            # .state 'notes',
            #     url: '/notes'
            #     template: require('./simple/notes/view.html')
            #     controller: require('./simple/notes/index.coffee')
            #     controllerAs: 'ctrl'
            #     resolve: auth
            #
            # .state 'simple.notes.list',
            #     url: '/list'
            #     template: require('./simple/notes/list/view.html')
            #     controller: require('./simple/notes/list/index.coffee')
            #     controllerAs: 'ctrl'
            #     resolve: auth
            #
            # .state 'simple.notes.view',
            #     url: '/view?noteId'
            #     template: require('./simple/notes/view/view.html')
            #     controller: require('./simple/notes/view/index.coffee')
            #     controllerAs: 'ctrl'
            #     resolve: auth
            #
            # .state 'simple.chat',
            #     url: '/chat'
            #     template: require('./simple/chat/view.html')
            #     controller: require('./simple/chat/index.coffee')
            #     controllerAs: 'ctrl'
            #     resolve: auth
            #
            # .state 'simple.chat.channel',
            #     url: '/channel?channelId'
            #     template: require('./simple/chat/channel/view.html')
            #     controller: require('./simple/chat/channel/index.coffee')
            #     controllerAs: 'ctrl'
            #     resolve: auth
            #

    ]
