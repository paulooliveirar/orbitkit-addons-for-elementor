(function ($) {
	'use strict';

	var $app = $('#rk-settings-app');
	if (!$app.length) {
		return;
	}

	var i18n = (window.orbitkitAdmin && window.orbitkitAdmin.i18n) || {};

	/* Tabs */
	$app.on('click', '.rk-settings__nav-item', function () {
		var tab = $(this).data('rk-tab');

		$app.find('.rk-settings__nav-item')
			.removeClass('is-active')
			.attr('aria-selected', 'false');
		$(this).addClass('is-active').attr('aria-selected', 'true');

		$app.find('.rk-settings__panel')
			.removeClass('is-active')
			.attr('hidden', true);
		$app.find('[data-rk-panel="' + tab + '"]')
			.addClass('is-active')
			.removeAttr('hidden');
	});

	/* Category chips */
	$app.on('click', '.rk-chip', function () {
		var cat = $(this).data('rk-cat');
		$app.find('.rk-chip').removeClass('is-active');
		$(this).addClass('is-active');
		$('#rk-widget-filter').val(cat === 'all' ? 'all' : cat);
		applyFilters();
	});

	$('#rk-widget-search, #rk-widget-filter').on('input change', applyFilters);

	$('#rk-clear-filters').on('click', function () {
		$('#rk-widget-search').val('');
		$('#rk-widget-filter').val('all');
		$app.find('.rk-chip').removeClass('is-active');
		$app.find('.rk-chip[data-rk-cat="all"]').addClass('is-active');
		applyFilters();
	});

	function applyFilters() {
		var query = ($('#rk-widget-search').val() || '').toLowerCase().trim();
		var filter = $('#rk-widget-filter').val() || 'all';
		var visible = 0;

		$app.find('.rk-element-card').each(function () {
			var $card = $(this);
			var search = ($card.data('rk-search') || '').toString().toLowerCase();
			var category = $card.data('rk-category') || '';
			var matchSearch = !query || search.indexOf(query) !== -1;
			var matchCat = filter === 'all' || category === filter;
			var show = matchSearch && matchCat;

			$card.toggleClass('is-hidden', !show);
			if (show) {
				visible++;
			}
		});

		$app.find('.rk-section').each(function () {
			var $section = $(this);
			var sectionCat = $section.data('rk-section');
			var hasVisible = $section.find('.rk-element-card:not(.is-hidden)').length > 0;
			var matchFilter = filter === 'all' || sectionCat === filter;
			$section.attr('hidden', !hasVisible || !matchFilter ? true : null);
		});

		$('#rk-elements-empty').prop('hidden', visible > 0);
		syncGlobalToggle();
	}

	function updateCardState($card, on) {
		$card.toggleClass('is-on', on).toggleClass('is-off', !on);
		$card.find('.rk-status__text').text(on ? i18n.active : i18n.inactive);
		updateEnabledStat();
	}

	function updateEnabledStat() {
		var count = $app.find('.rk-widget-toggle:checked').length;
		$('#rk-stat-enabled').text(count);
	}

	function updateGlobalToggleLabel() {
		var $global = $('#rk-enable-all-global');
		var $label = $('#rk-enable-all-global-label');
		if (!$global.length || !$label.length) {
			return;
		}
		$label.text($global.prop('checked') ? i18n.disableAll : i18n.enableAll);
	}

	$app.on('change', '.rk-widget-toggle', function () {
		var $card = $(this).closest('.rk-element-card');
		updateCardState($card, this.checked);
		syncSectionToggle($(this).closest('.rk-section'));
		syncGlobalToggle();
	});

	$app.on('change', '.rk-enable-section', function () {
		var section = $(this).data('section');
		var on = this.checked;
		$app.find('.rk-section[data-rk-section="' + section + '"] .rk-widget-toggle').each(function () {
			this.checked = on;
			updateCardState($(this).closest('.rk-element-card'), on);
		});
		syncGlobalToggle();
	});

	$('#rk-enable-all-global').on('change', function () {
		var on = this.checked;
		$app.find('.rk-widget-toggle').each(function () {
			this.checked = on;
			updateCardState($(this).closest('.rk-element-card'), on);
		});
		$app.find('.rk-enable-section').prop('checked', on);
		updateEnabledStat();
		updateGlobalToggleLabel();
	});

	function syncSectionToggle($section) {
		if (!$section || !$section.length) {
			return;
		}
		var $toggles = $section.find('.rk-widget-toggle');
		var allOn = $toggles.length && $toggles.filter(':checked').length === $toggles.length;
		$section.find('.rk-enable-section').prop('checked', allOn);
	}

	function syncGlobalToggle() {
		var $toggles = $app.find('.rk-element-card:not(.is-hidden) .rk-widget-toggle');
		if (!$toggles.length) {
			$toggles = $app.find('.rk-widget-toggle');
		}
		var allOn = $toggles.length && $toggles.filter(':checked').length === $toggles.length;
		$('#rk-enable-all-global').prop('checked', allOn);
		updateGlobalToggleLabel();
		$app.find('.rk-enable-section').each(function () {
			syncSectionToggle($app.find('.rk-section[data-rk-section="' + $(this).data('section') + '"]'));
		});
	}

	/* Google Maps key status */
	$('#rk-google-maps-api-key').on('input', function () {
		var hasKey = $.trim($(this).val()).length > 0;
		var $pill = $('#rk-maps-status');
		$pill
			.removeClass('rk-pill--success rk-pill--muted')
			.addClass(hasKey ? 'rk-pill--success' : 'rk-pill--muted')
			.text(hasKey ? i18n.configured : i18n.notSet);
	});

	$('#rk-settings-form').on('submit', function () {
		$app.find('.rk-widget-toggle').each(function () {
			if (!this.checked) {
				$('<input type="hidden">').attr('name', this.name).val('0').insertAfter(this);
			}
		});
	});

	applyFilters();
})(jQuery);
