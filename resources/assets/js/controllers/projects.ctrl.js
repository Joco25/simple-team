module.exports = class ProjectsCtrl {
    constructor() {
        this.s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
        this.authUser = $rootScope.authUser
        this.filters = {
            tag: null,
            assignedTo: null,
            quick: null
        }
        this.team = $rootScope.authUser.team
        this.projects = []
        this.tags = []
        this.currentUser = null
        this.selectedStage = null
        this.selectedCommentBody = ''
        this.selectedTaskBody = ''
        this.searchInput = ''
        this.sortableOptions = {
            placeholder: "sortable-preview",
            connectWith: ".sortable",
            delay: 100,
            stop: (function(_this) {
                return function(evt, ui) {
                    var stage
                    if (ui.item.sortable.droptarget) {
                        stage = ui.item.sortable.droptarget.scope().stage
                        return _this.updateStageCards(stage)
                    }
                }
            })(this)
        }
    }

    openEditCard(card) {
        CardCacherService.set(card)
        return $state.go('projects.card', {
            cardId: card.id
        })
    }

    updateStageCards(stage) {
        var cardIds = _.pluck(stage.cards, 'id')
        $http.put('/api/cards/stageOrder', {
            card_ids: cardIds,
            stage_id: stage.id
        })
    }

    updateProjectOrder(projects) {
        var projectIds = _.pluck(projects, 'id')
        $http.post('/api/projects/order', {
            project_ids: projectIds
        })
    }
}



function($http, $state, $rootScope, $modal, CardCacherService, CardListFiltersService) {
    var init
    var ctrl = this

    $rootScope.$on('projects:reload', (function(_this) {
        return function() {
            return _this.loadProjects()
        }
    })(this))
    init = (function(_this) {
        return function() {
            _this.loadProjects()
            return _this.loadTags()
        }
    })(this)

    this.selectAssignedToFilter = (function(_this) {
        return function(newFilter) {
            _this.filters.assignedTo = newFilter
            return $rootScope.$broadcast('filters:update', _this.filters)
        }
    })(this)
    this.selectTagFilter = (function(_this) {
        return function(newFilter) {
            _this.filters.tag = newFilter
            return $rootScope.$broadcast('filters:update', _this.filters)
        }
    })(this)
    this.selectQuickFilter = (function(_this) {
        return function(newFilter) {
            _this.filters.quick = newFilter
            return $rootScope.$broadcast('filters:update', _this.filters)
        }
    })(this)
    this.updateSearchInput = (function(_this) {
        return function() {
            return $rootScope.$broadcast('search:update', _this.searchInput)
        }
    })(this)
    this.clearSearchInput = (function(_this) {
        return function() {
            _this.searchInput = ''
            return _this.updateSearchInput()
        }
    })(this)
    this.loadTags = (function(_this) {
        return function() {
            return $http.get('/api/tags').success(function(data) {
                return _this.tags = data.tags
            })
        }
    })(this)
    this.openSortableProjects = function() {
        return $modal.open({
            template: require('../layouts/sortableProjects.modal.html'),
            controller: require('./sortableProjects.modal.ctrl.js'),
            controllerAs: 'ctrl',
            size: 'md',
            resolve: {
                projects: (function(_this) {
                    return function() {
                        return _this.projects
                    }
                })(this)
            }
        }).result.then((function(_this) {
            return function(projects) {
                _this.projects = projects
                return _this.updateProjectOrder(projects)
            }
        })(this))
    }
    this.loadProjects = function() {
        return $http.get('/api/projects').success((function(_this) {
            return function(data) {
                return _this.projects = data.projects
            }
        })(this))
    }
    this.openSortStagesModal = (function(_this) {
        return function() {
            return _this.stagesCopy = angular.copy(_this.stages)
        }
    })(this)
    this.updateStagesOrder = (function(_this) {
        return function(stagesCopy) {
            return _.each(stagesCopy, function(stage, key) {
                var realStage
                realStage = _.find(_this.stages, {
                    $id: stage.$id
                })
                realStage.$priority = key
                return _this.stages.$save(realStage)
            })
        }
    })(this)
    this.deleteProject = (function(_this) {
        return function(project) {
            if (!confirm("Delete '" + project.name + "' and all it's contents?")) {
                return
            }
            _.remove(_this.projects, project)
            return $http["delete"]('/api/projects/' + project.id)
        }
    })(this)
    this.createProject = function() {
        var projectName
        projectName = prompt("New Project Name")
        if (!projectName) {
            return
        }
        return $http.post('/api/projects', {
            name: projectName,
            stages: [{
                name: 'Open'
            }, {
                name: 'In Progress'
            }, {
                name: 'Closed'
            }]
        }).success((function(_this) {
            return function(data) {
                return _this.projects = data.projects
            }
        })(this))
    }
    this.deleteStage = function(project, stageIndex) {
        if (confirm('Delete this stage?')) {
            return project.stages.splice(stageIndex, 1)
        }
    }
    this.toggleProjectVisibility = function(project) {
        return project.hidden = !project.hidden
    }
    this.editStage = function(stage) {
        var newStageName
        newStageName = prompt('Edit Stage Name', stage.name)
        if (!newStageName) {
            return
        }
        return stage.name = newStageName
    }
    this.deleteAllCardsInStage = function(stage) {
        if (!confirm('Delete all cards in this stage?')) {
            return
        }
        stage.cards = []
        return $http["delete"]('/api/stages/' + stage.id + '/cards')
    }
    this.createStage = (function(_this) {
        return function() {
            var newStage, result, stageName
            stageName = prompt("Stage name")
            if (stageName === null) {
                return
            }
            newStage = {
                id: createId(),
                name: stageName,
                createdAt: (new Date).getTime()
            }
            result = _this.stages.$add(newStage)
            return _.each(_this.projects, function(project) {
                project.stages = project.stages || []
                project.stages.push(newStage)
                return _this.projects.$save(project)
            })
        }
    })(this)
    this.createCard = (function(_this) {
        return function(project) {
            var newCardName
            newCardName = prompt('Task description')
            if (!newCardName) {
                return
            }
            $http.post('/api/cards', {
                stage_id: project.stages[0].id,
                name: newCardName
            }).success(function(data) {
                return project.stages[0].cards.push(data.card)
            })
            return _this.newCardName = ''
        }
    })(this)

    this.appliedFilters = function(card) {
        console.log(CardListFiltersService.check(ctrl.filters, ctrl.authUser, card))
        CardListFiltersService.check(ctrl.filters, ctrl.authUser, card)
    }

    this.editProject = function(project) {
        var projectName
        projectName = prompt("Project name", project.name)
        if (projectName === null) {
            return
        }
        project.name = projectName
        return $http.put('/api/projects/' + project.id, {
            name: project.name
        }).success((function(_this) {
            return function(data) {
                var projectIndex
                projectIndex = _.indexOf(_this.projects, _.find(_this.projects, {
                    id: project.id
                }))
                if (cardIndex > -1) {
                    return _this.projects.splice(projectIndex, 1, project)
                }
            }
        })(this))
    }
    init()
}
