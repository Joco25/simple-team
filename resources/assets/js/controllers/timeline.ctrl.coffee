'use strict'

module.exports = ($http, $scope) ->
    @data = []
    date = new Date()
    @options =
        fromDate: new Date()
        toDate: new Date(date.setDate(date.getDate() + 10))

    init = =>
        @loadProjects()

    @registerApi = (api) =>
        api.core.on.ready $scope, (data) =>
            # Call API methods and register events.
            console.log data

        api.data.on.change $scope, (newData) =>
            console.log newData

        # api.tasks.on.add(task)
        # api.tasks.on.change(task)
        # api.tasks.on.remove(task)
        # api.tasks.on.rowChange(task, oldRow)
        # api.timespans.on.add(timespan)
        # api.timespans.on.change(timespan)
        # api.timespans.on.remove(timespan)
        # api.rows.on.add(row)
        # api.rows.on.change(row)
        # api.rows.on.remove(row)

    @loadProjects = ->
        $http
            .get '/api/projects'
            .success (data) =>
                @projects = data.projects
                @createGanttData()

    @createGanttData = =>
        @newData = []
        _.each @projects, (project) =>
            @data.push
                name: project.name

            children = []
            _.each project.stages, (stage) =>
                tasks = []
                _.each stage.cards, (card) =>
                    tasks.push {
                        name: card.name
                        color: '#F1C232'
                        from: new Date(2013, 10, 18, 8, 0, 0)
                        to: new Date(2013, 10, 18, 12, 0, 0)
                    }

                @data.push
                    name: stage.name
                    parent: project.name
                    tasks: tasks

    init()

    return
