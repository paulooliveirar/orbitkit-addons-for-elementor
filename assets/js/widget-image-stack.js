(function ($) {
	'use strict';

	function initStack($scope) {
		$scope.find('.rocketkit-image-stack').each(function () {
			var $stack = $(this);
			if ($stack.data('rocketkit-stack-init')) {
				return;
			}
			$stack.data('rocketkit-stack-init', true);
		});
	}

	$(function () {
		initStack($(document));
	});

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}
		elementorFrontend.hooks.addAction('frontend/element_ready/rocketkit_image_stack.default', function ($scope) {
			initStack($scope);
		});
	});
})(jQuery);
