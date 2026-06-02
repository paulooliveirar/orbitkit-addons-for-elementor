(function ($) {
	'use strict';

	function pad(n) {
		return n < 10 ? '0' + n : String(n);
	}

	function updateCountdown($el) {
		var config;
		try {
			config = JSON.parse($el.attr('data-config') || '{}');
		} catch (e) {
			return;
		}

		if (!config.dueDate) {
			return;
		}

		var due = new Date(config.dueDate.replace(' ', 'T'));
		var now = new Date();
		var diff = due - now;

		if (diff <= 0) {
			$el.find('.rocketkit-countdown-grid').hide();
			$el.find('.rocketkit-countdown-expired').prop('hidden', false);
			return;
		}

		var seconds = Math.floor(diff / 1000);
		var days = Math.floor(seconds / 86400);
		seconds -= days * 86400;
		var hours = Math.floor(seconds / 3600);
		seconds -= hours * 3600;
		var minutes = Math.floor(seconds / 60);
		seconds -= minutes * 60;

		$el.find('[data-unit="days"]').text(pad(days));
		$el.find('[data-unit="hours"]').text(pad(hours));
		$el.find('[data-unit="minutes"]').text(pad(minutes));
		$el.find('[data-unit="seconds"]').text(pad(seconds));
	}

	function initCountdown($scope) {
		$scope.find('.rocketkit-countdown').each(function () {
			var $el = $(this);
			updateCountdown($el);
			if ($el.data('rocketkit-countdown-interval')) {
				clearInterval($el.data('rocketkit-countdown-interval'));
			}
			var interval = setInterval(function () {
				updateCountdown($el);
			}, 1000);
			$el.data('rocketkit-countdown-interval', interval);
		});
	}

	$(function () {
		initCountdown($(document));
	});

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}
		elementorFrontend.hooks.addAction('frontend/element_ready/rocketkit_countdown.default', function ($scope) {
			initCountdown($scope);
		});
	});
})(jQuery);
