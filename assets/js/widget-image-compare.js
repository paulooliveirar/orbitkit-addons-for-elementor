(function ($) {
	'use strict';

	function clamp(value, min, max) {
		return Math.min(max, Math.max(min, value));
	}

	function parseConfig($root) {
		try {
			return JSON.parse($root.attr('data-config') || '{}');
		} catch (e) {
			return {};
		}
	}

	function setPosition($root, percent) {
		var value = clamp(percent, 0, 100);
		$root.css('--rk-compare-pos', value + '%');
		$root.find('.orbitkit-image-compare__stage').attr('aria-valuenow', String(Math.round(value)));
	}

	function getPercentFromEvent($stage, event, config) {
		var rect = $stage[0].getBoundingClientRect();
		var clientX = event.clientX;
		var clientY = event.clientY;

		if (event.touches && event.touches.length) {
			clientX = event.touches[0].clientX;
			clientY = event.touches[0].clientY;
		}

		if (config.orientation === 'vertical') {
			if (rect.height <= 0) {
				return 50;
			}
			return ((clientY - rect.top) / rect.height) * 100;
		}

		if (rect.width <= 0) {
			return 50;
		}
		return ((clientX - rect.left) / rect.width) * 100;
	}

	function initCompare($root) {
		if ($root.data('orbitkit-compare-init')) {
			return;
		}
		$root.data('orbitkit-compare-init', true);

		var config = parseConfig($root);
		var $stage = $root.find('.orbitkit-image-compare__stage');
		var start = typeof config.start === 'number' ? config.start : 50;
		var dragging = false;

		setPosition($root, start);

		function updateFromEvent(event) {
			setPosition($root, getPercentFromEvent($stage, event, config));
		}

		function onPointerDown(event) {
			if ($root.hasClass('orbitkit-image-compare--move-hover') && event.type === 'mousedown') {
				return;
			}
			dragging = true;
			$stage[0].setPointerCapture && event.pointerId && $stage[0].setPointerCapture(event.pointerId);
			updateFromEvent(event);
			event.preventDefault();
		}

		function onPointerMove(event) {
			if ($root.hasClass('orbitkit-image-compare--move-hover') && !dragging) {
				updateFromEvent(event);
				return;
			}
			if (!dragging) {
				return;
			}
			updateFromEvent(event);
			event.preventDefault();
		}

		function onPointerUp() {
			dragging = false;
		}

		$stage.on('mousedown touchstart', onPointerDown);
		$(window).on('mousemove touchmove', onPointerMove);
		$(window).on('mouseup touchend touchcancel', onPointerUp);

		if ($root.hasClass('orbitkit-image-compare--move-hover')) {
			$stage.on('mousemove', onPointerMove);
		}

		$stage.on('keydown', function (event) {
			var step = event.shiftKey ? 10 : 2;
			var current = parseFloat($root.css('--rk-compare-pos')) || start;
			var next = current;

			if (config.orientation === 'vertical') {
				if (event.key === 'ArrowUp') {
					next = current - step;
				} else if (event.key === 'ArrowDown') {
					next = current + step;
				}
			} else if (event.key === 'ArrowLeft') {
				next = current - step;
			} else if (event.key === 'ArrowRight') {
				next = current + step;
			} else {
				return;
			}

			event.preventDefault();
			setPosition($root, next);
		});
	}

	function initScope($scope) {
		$scope.find('.orbitkit-image-compare').each(function () {
			initCompare($(this));
		});
	}

	$(function () {
		initScope($(document));
	});

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}
		elementorFrontend.hooks.addAction('frontend/element_ready/orbitkit_image_compare.default', function ($scope) {
			initScope($scope);
		});
	});
})(jQuery);
