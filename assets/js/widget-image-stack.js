(function ($) {
	'use strict';

	function initStack($scope) {
		$scope.find('.orbitkit-image-stack').each(function () {
			var $stack = $(this);
			if ($stack.data('orbitkit-stack-init')) {
				return;
			}
			$stack.data('orbitkit-stack-init', true);
		});
	}

	$(function () {
		initStack($(document));
	});

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}
		elementorFrontend.hooks.addAction('frontend/element_ready/orbitkit_image_stack.default', function ($scope) {
			initStack($scope);
		});
	});
})(jQuery);
