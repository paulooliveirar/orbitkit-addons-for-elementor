(function ($) {
	'use strict';

	var cfg = window.orbitkitMapEditor || {};
	var i18n = cfg.i18n || {};
	var MIN_CHARS = cfg.minChars || 5;
	var MAX_RESULTS = cfg.maxResults || 3;
	var debounceTimer = null;
	var activeRequest = null;

	function isGoogleMapsProvider() {
		var $provider = $('.elementor-control-map_provider');
		if (!$provider.length) {
			return false;
		}
		var selectVal = $provider.find('select').val();
		if (selectVal) {
			return selectVal === 'google';
		}
		return $provider.find('input[value="google"]:checked').length > 0;
	}

	function getRepeaterRow($el) {
		return $el.closest('.elementor-repeater-fields');
	}

	function setStatus($row, message) {
		$row.find('.orbitkit-marker-geocode-status').text(message || '');
	}

	function setCoords($row, lat, lng) {
		$row.find('.elementor-control-marker_lat input').val(lat).trigger('input');
		$row.find('.elementor-control-marker_lng input').val(lng).trigger('input');
	}

	function ensureSuggestionsUi($row) {
		var $wrapper = $row.find('.elementor-control-marker_place_search .elementor-control-input-wrapper');
		if (!$wrapper.length) {
			return null;
		}
		$wrapper.addClass('orbitkit-place-search-wrap');
		if (!$wrapper.find('.orbitkit-place-suggestions').length) {
			$wrapper.append(
				'<ul class="orbitkit-place-suggestions" role="listbox" hidden></ul>'
			);
		}
		return $wrapper.find('.orbitkit-place-suggestions');
	}

	function hideSuggestions($row) {
		$row.find('.orbitkit-place-suggestions').attr('hidden', true).empty();
	}

	function showSuggestions($row, results) {
		var $list = ensureSuggestionsUi($row);
		if (!$list || !$list.length) {
			return;
		}

		$list.empty();

		if (!results || !results.length) {
			hideSuggestions($row);
			return;
		}

		results.slice(0, MAX_RESULTS).forEach(function (item) {
			$('<li>', {
				role: 'option',
				tabindex: 0,
				class: 'orbitkit-place-suggestions__item',
				text: item.display,
			})
				.data('place', item)
				.appendTo($list);
		});

		$list.removeAttr('hidden');
	}

	function abortActiveRequest() {
		if (activeRequest && activeRequest.readyState !== 4) {
			activeRequest.abort();
		}
		activeRequest = null;
	}

	function fetchSuggestions($row, query) {
		if (!cfg.restUrl || !cfg.nonce) {
			setStatus($row, i18n.error);
			return;
		}

		abortActiveRequest();
		setStatus($row, i18n.searching);

		activeRequest = $.ajax({
			url: cfg.restUrl,
			method: 'GET',
			data: {
				q: query,
				limit: MAX_RESULTS,
			},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', cfg.nonce);
			},
		})
			.done(function (data) {
				var results = data && data.results ? data.results : [];
				if (!results.length) {
					hideSuggestions($row);
					setStatus($row, i18n.notFound);
					return;
				}
				showSuggestions($row, results);
				setStatus($row, '');
			})
			.fail(function (xhr) {
				hideSuggestions($row);
				if (xhr && xhr.statusText === 'abort') {
					return;
				}
				if (xhr && xhr.status === 404) {
					setStatus($row, i18n.notFound);
					return;
				}
				setStatus($row, i18n.error);
			})
			.always(function () {
				activeRequest = null;
			});
	}

	function scheduleSearch($input) {
		if (!isGoogleMapsProvider()) {
			return;
		}

		var $row = getRepeaterRow($input);
		var query = $.trim($input.val());

		clearTimeout(debounceTimer);

		if (query.length < MIN_CHARS) {
			abortActiveRequest();
			hideSuggestions($row);
			setStatus($row, query.length ? i18n.typeMore : '');
			return;
		}

		debounceTimer = setTimeout(function () {
			fetchSuggestions($row, query);
		}, 350);
	}

	function selectPlace($item) {
		var place = $item.data('place');
		var $row = getRepeaterRow($item);
		if (!place || !$row.length) {
			return;
		}

		$row.find('.elementor-control-marker_place_search input').val(place.display);
		setCoords($row, place.lat, place.lng);
		hideSuggestions($row);
		setStatus($row, i18n.found);
	}

	$(document).on('input', '.elementor-control-marker_place_search input', function () {
		if (!isGoogleMapsProvider()) {
			return;
		}
		scheduleSearch($(this));
	});

	$(document).on('focus', '.elementor-control-marker_place_search input', function () {
		if (!isGoogleMapsProvider()) {
			return;
		}
		var $input = $(this);
		var $row = getRepeaterRow($input);
		ensureSuggestionsUi($row);
		if ($.trim($input.val()).length >= MIN_CHARS) {
			scheduleSearch($input);
		}
	});

	$(document).on('click', '.orbitkit-place-suggestions__item', function (e) {
		e.preventDefault();
		selectPlace($(this));
	});

	$(document).on('keydown', '.orbitkit-place-suggestions__item', function (e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			selectPlace($(this));
		}
	});

	$(document).on('keydown', '.elementor-control-marker_place_search input', function (e) {
		var $row = getRepeaterRow($(this));
		var $list = $row.find('.orbitkit-place-suggestions');
		var $items = $list.find('.orbitkit-place-suggestions__item');
		var $active = $items.filter('.is-active');

		if (!$items.length || $list.is('[hidden]')) {
			return;
		}

		if (e.key === 'ArrowDown') {
			e.preventDefault();
			if (!$active.length) {
				$items.first().addClass('is-active');
			} else {
				$active.removeClass('is-active').next().addClass('is-active');
				if (!$items.filter('.is-active').length) {
					$items.first().addClass('is-active');
				}
			}
		} else if (e.key === 'ArrowUp') {
			e.preventDefault();
			if (!$active.length) {
				$items.last().addClass('is-active');
			} else {
				$active.removeClass('is-active').prev().addClass('is-active');
				if (!$items.filter('.is-active').length) {
					$items.last().addClass('is-active');
				}
			}
		} else if (e.key === 'Enter' && $active.length) {
			e.preventDefault();
			selectPlace($active);
		} else if (e.key === 'Escape') {
			hideSuggestions($row);
		}
	});

	$(document).on('blur', '.elementor-control-marker_place_search input', function () {
		var $row = getRepeaterRow($(this));
		setTimeout(function () {
			hideSuggestions($row);
			$row.find('.orbitkit-place-suggestions__item').removeClass('is-active');
		}, 200);
	});

	$(document).on('elementor/repeater/insert', function () {
		abortActiveRequest();
		hideSuggestions($(document));
	});

	$(document).on('change', '.elementor-control-map_provider select, .elementor-control-map_provider input', function () {
		abortActiveRequest();
		hideSuggestions($(document));
		if (!isGoogleMapsProvider()) {
			setStatus($(document), '');
		}
	});
})(jQuery);
