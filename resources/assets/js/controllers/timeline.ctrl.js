'use strict';
module.exports = function($http, $scope) {
  var date, init;
  this.data = [];
  date = new Date();
  this.options = {
    fromDate: new Date(),
    toDate: new Date(date.setDate(date.getDate() + 10))
  };
  init = (function(_this) {
    return function() {
      return _this.loadProjects();
    };
  })(this);
  this.registerApi = (function(_this) {
    return function(api) {
      api.core.on.ready($scope, function(data) {
        return console.log(data);
      });
      return api.data.on.change($scope, function(newData) {
        return console.log(newData);
      });
    };
  })(this);
  this.loadProjects = function() {
    return $http.get('/api/projects').success((function(_this) {
      return function(data) {
        _this.projects = data.projects;
        return _this.createGanttData();
      };
    })(this));
  };
  this.createGanttData = (function(_this) {
    return function() {
      _this.newData = [];
      return _.each(_this.projects, function(project) {
        var children;
        _this.data.push({
          name: project.name
        });
        children = [];
        return _.each(project.stages, function(stage) {
          var tasks;
          tasks = [];
          _.each(stage.cards, function(card) {
            return tasks.push({
              name: card.name,
              color: '#F1C232',
              from: new Date(2013, 10, 18, 8, 0, 0),
              to: new Date(2013, 10, 18, 12, 0, 0)
            });
          });
          return _this.data.push({
            name: stage.name,
            parent: project.name,
            tasks: tasks
          });
        });
      });
    };
  })(this);
  init();
};

// ---
// generated by coffee-script 1.9.2
