angular.module('simple.team.mediaComment', [])

	.factory('Parser', [function() {
		var self = {},
			//youtubeCache = {},
			//youtubeIdRegex = /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/i,
			//youtubeLinkRegex = /(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/ig,
			// bbYoutubeRegex = /\[youtube\](.*?)\[\/youtube\]/g,
			linkRegex = /(http|https|ftp|ftps|mailto)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?/ig,
			bbLinkRegex = /\[link\](.*?)\[\/link\]/g,
			imageRegex = /(https?:\/\/.*\.(?:png|jpg|jpeg|gif))/ig,
			bbImageRegex = /\[img\](.*?)\[\/img\]/g

		self.linksToBB = function(value) {
			return value.replace(linkRegex, function(link) {
				if (! link.match(imageRegex) && link.indexOf('youtube') === -1) {
					return '[link]' + link + '[/link]'
				}

				return link
			})
		}

		self.imagesToBB = function(value) {
			return value.replace(linkRegex, function(link) {
				if (link.match(imageRegex)) {
					return '[img]' + link + '[/img]'
				}

				return link
			})
		}

		/**
		self.youtubesToBB = function(value) {
			return String(value).replace(youtubeLinkRegex, function(link) {
				return '[youtube]' + link + '[/youtube]'
			})
		}
		*/

		self.toBB = function(value) {
			// value = self.youtubesToBB(value)
			value = self.linksToBB(value)
			value = self.imagesToBB(value)

			return value
		}

		self.linksToDisplay = function(value) {
			return value.replace(bbLinkRegex, '<a class="media-link" target="_blank" href="$1">$1</a>')
		}

		self.imagesToDisplay = function(value) {
			return value.replace(bbImageRegex, function(img) {
				img = img.replace('[img]', '')
				img = img.replace('[/img]', '')

				return '<a class="media-image" target="_blank" href="' + img + '"><img src="' + img + '"></a>'
			})
		}

		/*
		self.youtubesToDisplay = function(value) {
			return value.replace(bbYoutubeRegex, function(link) {
				link = link.replace('[youtube]', '')
				link = link.replace('[/youtube]', '')

				var videoId = link.match(youtubeIdRegex)

				if (videoId[1] === undefined) {
					return link
				}

				var text = '<div class="youtube' + videoId[1] + '"></div>'

				if (youtubeCache[videoId[1]] === undefined) {
					youtubeCache[videoId[1]] = {}
					$.getJSON('http://gdata.youtube.com/feeds/api/videos/' + videoId[1] + '?v=2&alt=jsonc',function(data){
						youtubeCache[videoId[1]] = data
						self.createYoutubePreview(data, videoId[1])
					})
				}

				return text
			})
		}

		self.createYoutubePreview = function(data, videoId) {
			var	descLimit = 110,
				description = data.data.description.length > descLimit ? data.data.description.substring(0, descLimit) + '...' : data.data.description

			$('.youtube' + videoId).each(function() {
				$(this).html('<a href="https://youtube.com/watch?v=' + data.data.id + '" target="_blank">' +
					'<div class="media media-youtube">' +
						'<span class="pull-left">' +
							'<img class="media-object" src="' + data.data.thumbnail.hqDefault + '">' +
						'</span>' +
						'<div class="media-body">' +
							'<h5>' + data.data.title + '</h5>' +
							'<p>' + nl2br(description) + '</p>' +
						'</div>' +
					'</div>' +
				'</a>')
			})
		}
		*/

		self.toDisplay = function(value) {
			// value = self.youtubesToDisplay(value)
			value = self.linksToDisplay(value)
			value = self.imagesToDisplay(value)

			return value
		}

		self.render = function(value) {
			value = self.toBB(value)
			value = self.toDisplay(value)

			return nl2br(value)
		}

		return self
	}])

	.directive('mediaComment', ['Parser', function(Parser){
		return {
			scope: {
				ngModel: '='
			},
			link: function (scope, element) {
				var value = scope.ngModel
				value = Parser.render(value)
				element.html(value)
			}
		}
	}])
