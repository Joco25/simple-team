angular
    .module('simple.team.modal', [])

    .directive('modal', ['Modal', function(Modal){
        return {
            restrict: 'E,A',
            scope: {
                template: '=',
                data: '='
            },
            link: function postLink(scope, element, attrs) {
                element.bind("click", function() {
                    Modal.open({
                        template: scope.template,
                        data: scope.data
                    })
                })
            }
        }
    }])

    .factory('Modal', ['$http', '$compile', function ($http, $compile) {
        // Got the idea for this from a post I found. Tried to not have to make this
        // object but couldn't think of a way to get around this
        var self = {};

        // Get the popup
        self.getModal = function() {
            $('.modal-service').remove();

            // the popup-service class lets us tag this modal for future manipulation
            self.modalElement = $('<div class="modal-service modal fade"><div class="modal-dialog"><div class="modal-content"></div></div></div>' );
            self.modalElement.appendTo('body');

            return self.modalElement;
        };

        self.compileAndRunPopup = function (modal, scope) {
            $compile(modal)(scope);
            modal.modal();
        };

        // Loads the modal
        self.open = function(options) {
            var modal = self.getModal();

            modal.find('.modal-content').html(options.template);
            self.compileAndRunPopup(modal, options.scope);

            modal.on('hidden', function() {
                modal.remove();
            });

            modal.find(".btn-cancel").click(function () {
                self.close();
            });
        };

        self.close = function() {
            var modal = $('.modal-service');
            if (modal) {
                modal.modal('hide');
            }
        };

        return self;
    }]);
