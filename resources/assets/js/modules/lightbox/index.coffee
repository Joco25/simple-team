angular
    .module('simple.team.lightbox', [])
    .directive 'simpleLightbox', ->

        linkFunc = ($scope, $element) ->
            $element.on 'click', ->
                src = $element.attr('src')
                console.log src
                link = $('<a href="#_" class="lightbox" id="img1"><img src="https://s3.amazonaws.com/gschierBlog/images/pig-big.jpg"></a>')
                $('body').append(link)

        return {
            restrict: 'A'
            link: linkFunc
            controllerAs: 'ctrl'
        }
