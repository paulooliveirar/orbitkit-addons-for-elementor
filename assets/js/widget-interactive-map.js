/* global L, google */
(function ($) {
	'use strict';

	var TILES = {
		osm: {
			url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		},
		light: {
			url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
			attribution: '&copy; OpenStreetMap &copy; <a href="https://carto.com/">CARTO</a>',
		},
		dark: {
			url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
			attribution: '&copy; OpenStreetMap &copy; <a href="https://carto.com/">CARTO</a>',
		},
	};

	function parseConfig($canvas) {
		var raw = $canvas.attr('data-config') || '{}';
		try {
			return JSON.parse(raw);
		} catch (e) {
			return {};
		}
	}

	function isValidRegionFeature(geojson) {
		return geojson && geojson.type === 'Feature' && geojson.geometry;
	}

	function syncRegionTooltipCaretFill(tooltipEl) {
		var shell = tooltipEl.querySelector('.rocketkit-region-tooltip-shell');
		var card = tooltipEl.querySelector('.rocketkit-region-tooltip');
		if (!shell || !card) {
			return;
		}
		var fill = window.getComputedStyle(card).backgroundColor;
		if (fill && fill !== 'rgba(0, 0, 0, 0)') {
			shell.style.setProperty('--rk-region-tooltip-fill', fill);
		}
	}

	function bindRegionLeafletTooltip(featureLayer, tooltipHtml) {
		featureLayer.bindTooltip(tooltipHtml, {
			sticky: true,
			className: 'rocketkit-region-tooltip-wrap',
			direction: 'top',
			offset: [0, -16],
			opacity: 1,
		});

		featureLayer.on('tooltipopen', function (e) {
			var el = e.tooltip && e.tooltip.getElement ? e.tooltip.getElement() : null;
			if (!el) {
				return;
			}
			syncRegionTooltipCaretFill(el);
			el.classList.remove('rocketkit-region-tooltip--show');
			window.requestAnimationFrame(function () {
				window.requestAnimationFrame(function () {
					el.classList.add('rocketkit-region-tooltip--show');
				});
			});
		});

		featureLayer.on('tooltipclose', function (e) {
			var el = e.tooltip && e.tooltip.getElement ? e.tooltip.getElement() : null;
			if (el) {
				el.classList.remove('rocketkit-region-tooltip--show');
			}
		});
	}

	function buildRegionTooltipHtml(region) {
		var parts = ['<div class="rocketkit-region-tooltip-shell">', '<div class="rocketkit-region-tooltip">'];
		if (region.label) {
			parts.push('<div class="rocketkit-region-tooltip__title">' + escapeHtml(region.label) + '</div>');
		}
		if (region.description) {
			parts.push(
				'<div class="rocketkit-region-tooltip__description">' +
					formatDescription(region.description) +
					'</div>'
			);
		}
		parts.push(
			'</div>',
			'<span class="rocketkit-region-tooltip__caret" aria-hidden="true"></span>',
			'</div>'
		);
		return parts.join('');
	}

	function getRegionPathStyle(fillColor, fillOpacity, strokeColor, weight) {
		return {
			fill: true,
			fillColor: fillColor,
			fillOpacity: fillOpacity,
			fillRule: 'nonzero',
			color: strokeColor || fillColor,
			weight: weight || 2,
			lineJoin: 'round',
			lineCap: 'round',
		};
	}

	function extendBoundsFromGeometry(bounds, geometry, isGoogle) {
		if (!geometry || !geometry.coordinates) {
			return;
		}

		function addCoord(coord) {
			if (coord.length < 2) {
				return;
			}
			if (isGoogle && typeof google !== 'undefined') {
				bounds.extend(new google.maps.LatLng(coord[1], coord[0]));
			} else {
				bounds.extend([coord[1], coord[0]]);
			}
		}

		if (geometry.type === 'Polygon') {
			geometry.coordinates.forEach(function (ring) {
				ring.forEach(addCoord);
			});
		} else if (geometry.type === 'MultiPolygon') {
			geometry.coordinates.forEach(function (poly) {
				poly.forEach(function (ring) {
					ring.forEach(addCoord);
				});
			});
		}
	}

	function initLeafletRegions(map, regions) {
		var allBounds = [];

		regions.forEach(function (region) {
			if (!isValidRegionFeature(region.geojson)) {
				return;
			}

			var fillColor = region.color || '#3388ff';
			var fillOpacity = typeof region.fill_opacity === 'number' ? region.fill_opacity : 0.45;
			var hoverBorder = region.hover_border_color || region.hover_color || '#ff6b35';
			var hoverFillOn = region.hover_fill !== false;
			var hoverFillColor = region.hover_fill_color || hoverBorder;
			var hoverFillOpacity = typeof region.hover_fill_opacity === 'number' ? region.hover_fill_opacity : 1;
			var tooltipHtml = buildRegionTooltipHtml(region);

			var layer = L.geoJSON(region.geojson, {
				style: function () {
					return getRegionPathStyle(fillColor, fillOpacity, fillColor, 2);
				},
				onEachFeature: function (feature, featureLayer) {
					if (tooltipHtml.replace(/<[^>]+>/g, '').trim()) {
						bindRegionLeafletTooltip(featureLayer, tooltipHtml);
					}
					featureLayer.on({
						mouseover: function (e) {
							var hoverStyle = getRegionPathStyle(
								hoverFillOn ? hoverFillColor : fillColor,
								hoverFillOn ? hoverFillOpacity : fillOpacity,
								hoverBorder,
								3
							);
							e.target.setStyle(hoverStyle);
							if (e.target.getTooltip()) {
								e.target.openTooltip();
							}
						},
						mouseout: function (e) {
							layer.resetStyle(e.target);
							if (e.target.getTooltip()) {
								e.target.closeTooltip();
							}
						},
					});
				},
			}).addTo(map);

			try {
				allBounds.push(layer.getBounds());
			} catch (err) {
				// ignore invalid bounds
			}
		});

		if (allBounds.length === 1) {
			map.fitBounds(allBounds[0], { padding: [20, 20] });
		} else if (allBounds.length > 1) {
			var combined = allBounds[0];
			for (var i = 1; i < allBounds.length; i++) {
				combined = combined.extend(allBounds[i]);
			}
			map.fitBounds(combined, { padding: [20, 20] });
		}
	}

	function initGoogleRegions(map, regions) {
		var bounds = new google.maps.LatLngBounds();
		var hasBounds = false;
		var infoWindow = new google.maps.InfoWindow({ disableAutoPan: true });

		regions.forEach(function (region) {
			if (!isValidRegionFeature(region.geojson)) {
				return;
			}

			var fillColor = region.color || '#3388ff';
			var fillOpacity = typeof region.fill_opacity === 'number' ? region.fill_opacity : 0.45;
			var hoverBorder = region.hover_border_color || region.hover_color || '#ff6b35';
			var hoverFillOn = region.hover_fill !== false;
			var hoverFillColor = region.hover_fill_color || hoverBorder;
			var hoverFillOpacity = typeof region.hover_fill_opacity === 'number' ? region.hover_fill_opacity : 1;
			var feature = JSON.parse(JSON.stringify(region.geojson));
			var tooltipHtml = buildRegionTooltipHtml(region);

			feature.properties = feature.properties || {};
			feature.properties._rkFill = fillColor;
			feature.properties._rkFillOpacity = fillOpacity;
			feature.properties._rkHoverBorder = hoverBorder;
			feature.properties._rkHoverFill = hoverFillOn;
			feature.properties._rkHoverFillColor = hoverFillColor;
			feature.properties._rkHoverFillOpacity = hoverFillOpacity;
			feature.properties._rkTooltip = tooltipHtml;

			map.data.addGeoJson(feature);

			extendBoundsFromGeometry(bounds, feature.geometry, true);
			hasBounds = true;
		});

		map.data.setStyle(function (feature) {
			return {
				fillColor: feature.getProperty('_rkFill') || '#3388ff',
				fillOpacity: feature.getProperty('_rkFillOpacity') || 0.45,
				strokeColor: feature.getProperty('_rkFill') || '#3388ff',
				strokeWeight: 2,
			};
		});

		if (!map._rocketkitRegionsListeners) {
			map.data.addListener('mouseover', function (event) {
				var hoverBorder = event.feature.getProperty('_rkHoverBorder') || '#ff6b35';
				var hoverFillOn = event.feature.getProperty('_rkHoverFill') !== false;
				var style = {
					strokeColor: hoverBorder,
					strokeWeight: 3,
				};
				if (hoverFillOn) {
					style.fillColor = event.feature.getProperty('_rkHoverFillColor') || hoverBorder;
					style.fillOpacity = event.feature.getProperty('_rkHoverFillOpacity') || 1;
				} else {
					style.fillColor = event.feature.getProperty('_rkFill') || '#3388ff';
					style.fillOpacity = event.feature.getProperty('_rkFillOpacity') || 0.45;
				}
				map.data.overrideStyle(event.feature, style);

				var content = event.feature.getProperty('_rkTooltip');
				if (content && content.replace(/<[^>]+>/g, '').trim()) {
					infoWindow.setContent(content);
					infoWindow.setPosition(event.latLng);
					infoWindow.open(map);
				}
			});

			map.data.addListener('mouseout', function () {
				map.data.revertStyle();
				infoWindow.close();
			});

			map._rocketkitRegionsListeners = true;
			map._rocketkitRegionInfoWindow = infoWindow;
		}

		if (hasBounds) {
			map.fitBounds(bounds, 20);
		}
	}

	function escapeHtml(text) {
		if (!text) {
			return '';
		}
		return String(text)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function formatDescription(description) {
		return escapeHtml(description).replace(/\r\n|\r|\n/g, '<br>');
	}

	var MARKER_SHAPES = ['pin', 'circle', 'square', 'diamond'];

	function normalizeMarkerIcon(markerIcon) {
		var icon = markerIcon || {};
		var shape = icon.shape || 'pin';
		if (MARKER_SHAPES.indexOf(shape) === -1) {
			shape = 'pin';
		}
		return {
			color: icon.color || '#03a84e',
			shape: shape,
			size: parseInt(icon.size, 10) || 36,
		};
	}

	function getMarkerDimensions(shape, size) {
		if (shape === 'pin') {
			return {
				width: size,
				height: Math.round(size * 1.28),
				anchorX: size / 2,
				anchorY: Math.round(size * 1.28),
			};
		}
		return {
			width: size,
			height: size,
			anchorX: size / 2,
			anchorY: size / 2,
		};
	}

	function buildMarkerSvg(shape, color, size) {
		var dims = getMarkerDimensions(shape, size);
		var w = dims.width;
		var h = dims.height;
		var stroke = '#ffffff';

		if (shape === 'circle') {
			return (
				'<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '" viewBox="0 0 24 24" aria-hidden="true">' +
				'<circle cx="12" cy="12" r="8.5" fill="' + color + '" stroke="' + stroke + '" stroke-width="2"/></svg>'
			);
		}
		if (shape === 'square') {
			return (
				'<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '" viewBox="0 0 24 24" aria-hidden="true">' +
				'<rect x="4.5" y="4.5" width="15" height="15" rx="3" fill="' + color + '" stroke="' + stroke + '" stroke-width="2"/></svg>'
			);
		}
		if (shape === 'diamond') {
			return (
				'<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '" viewBox="0 0 24 24" aria-hidden="true">' +
				'<polygon points="12,3 21,12 12,21 3,12" fill="' + color + '" stroke="' + stroke + '" stroke-width="2" stroke-linejoin="round"/></svg>'
			);
		}
		return (
			'<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '" viewBox="0 0 24 32" aria-hidden="true">' +
			'<path d="M12 2c-3.87 0-7 3.13-7 7 0 5.25 7 15 7 15s7-9.75 7-15c0-3.87-3.13-7-7-7z" fill="' + color + '" stroke="' + stroke + '" stroke-width="1.5" stroke-linejoin="round"/></svg>'
		);
	}

	function createLeafletMarkerIcon(markerIcon) {
		var icon = normalizeMarkerIcon(markerIcon);
		var dims = getMarkerDimensions(icon.shape, icon.size);
		var html =
			'<div class="rocketkit-map-marker rocketkit-map-marker--' + icon.shape + '">' +
			buildMarkerSvg(icon.shape, icon.color, icon.size) +
			'</div>';

		return L.divIcon({
			className: 'rocketkit-map-marker-wrap',
			html: html,
			iconSize: [dims.width, dims.height],
			iconAnchor: [dims.anchorX, dims.anchorY],
			popupAnchor: [0, -dims.anchorY + 4],
		});
	}

	function createGoogleMarkerIcon(markerIcon) {
		var icon = normalizeMarkerIcon(markerIcon);
		var dims = getMarkerDimensions(icon.shape, icon.size);
		var svg = buildMarkerSvg(icon.shape, icon.color, icon.size);

		return {
			url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
			scaledSize: new google.maps.Size(dims.width, dims.height),
			anchor: new google.maps.Point(dims.anchorX, dims.anchorY),
		};
	}

	function getCustomMarkerUrl(markerIcon) {
		if (!markerIcon) {
			return '';
		}
		var svg = (markerIcon.svg || '').trim();
		if (svg) {
			if (svg.indexOf('<svg') === -1) {
				return '';
			}
			return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
		}
		return markerIcon.url || '';
	}

	function getLeafletMarkerOptions(markerIcon) {
		if (!markerIcon || markerIcon.type === 'default') {
			return {};
		}

		if (markerIcon.type === 'preset') {
			return { icon: createLeafletMarkerIcon(markerIcon) };
		}

		if (markerIcon.type === 'custom') {
			var url = getCustomMarkerUrl(markerIcon);
			if (!url) {
				return {};
			}
			var w = parseInt(markerIcon.width, 10) || 40;
			var h = parseInt(markerIcon.height, 10) || 40;
			return {
				icon: L.icon({
					iconUrl: url,
					iconSize: [w, h],
					iconAnchor: [w / 2, h],
					popupAnchor: [0, -h + 4],
				}),
			};
		}

		return {};
	}

	function getGoogleMarkerIcon(markerIcon) {
		if (!markerIcon || markerIcon.type === 'default') {
			return undefined;
		}

		if (markerIcon.type === 'preset') {
			return createGoogleMarkerIcon(markerIcon);
		}

		if (markerIcon.type === 'custom') {
			var url = getCustomMarkerUrl(markerIcon);
			if (!url) {
				return undefined;
			}
			var w = parseInt(markerIcon.width, 10) || 40;
			var h = parseInt(markerIcon.height, 10) || 40;
			return {
				url: url,
				scaledSize: new google.maps.Size(w, h),
				anchor: new google.maps.Point(w / 2, h),
			};
		}

		return undefined;
	}

	function hexToRgba(hex, alpha) {
		if (!hex) {
			return 'rgba(0,0,0,' + alpha + ')';
		}
		var normalized = String(hex).replace('#', '').trim();
		if (normalized.length === 3) {
			normalized =
				normalized[0] +
				normalized[0] +
				normalized[1] +
				normalized[1] +
				normalized[2] +
				normalized[2];
		}
		if (normalized.length !== 6) {
			return 'rgba(0,0,0,' + alpha + ')';
		}
		var r = parseInt(normalized.substring(0, 2), 16);
		var g = parseInt(normalized.substring(2, 4), 16);
		var b = parseInt(normalized.substring(4, 6), 16);
		return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
	}

	function buildLeafletHeatGradient(colors) {
		colors = colors || {};
		return {
			0.0: colors.low || '#2b83ba',
			0.5: colors.mid || '#abdda4',
			1.0: colors.high || '#d7191c',
		};
	}

	function buildGoogleHeatGradient(colors) {
		colors = colors || {};
		return [
			hexToRgba(colors.low, 0),
			hexToRgba(colors.low, 0.55),
			hexToRgba(colors.mid, 0.75),
			hexToRgba(colors.high, 1),
		];
	}

	function addLeafletHeatmapLabels(map, heatmap) {
		if (!heatmap || !heatmap.length) {
			return;
		}
		heatmap.forEach(function (point) {
			if (!point.label) {
				return;
			}
			L.circleMarker([point.lat, point.lng], {
				radius: 10,
				weight: 0,
				opacity: 0,
				fillOpacity: 0,
			})
				.bindTooltip(point.label, {
					permanent: false,
					direction: 'top',
					offset: [0, -8],
				})
				.addTo(map);
		});
	}

	function addGoogleHeatmapLabels(map, heatmap) {
		if (!heatmap || !heatmap.length) {
			return;
		}
		heatmap.forEach(function (point) {
			if (!point.label) {
				return;
			}
			new google.maps.Marker({
				position: { lat: point.lat, lng: point.lng },
				map: map,
				title: point.label,
				opacity: 0,
				clickable: false,
				icon: {
					path: google.maps.SymbolPath.CIRCLE,
					scale: 0,
				},
			});
		});
	}

	function buildMarkerPopupHtml(marker) {
		var parts = ['<div class="rocketkit-marker-popup">'];

		if (marker.title) {
			parts.push(
				'<div class="rocketkit-marker-popup__title">' + escapeHtml(marker.title) + '</div>'
			);
		}
		if (marker.description) {
			parts.push(
				'<div class="rocketkit-marker-popup__description">' +
					formatDescription(marker.description) +
					'</div>'
			);
		}
		if (parts.length === 1) {
			parts.push('<div class="rocketkit-marker-popup__title"></div>');
		}
		parts.push('</div>');
		return parts.join('');
	}

	function initLeafletMap($widget, config) {
		var $canvas = $widget.find('.rocketkit-map-canvas');
		if (!$canvas.length || typeof L === 'undefined') {
			return;
		}

		if ($canvas.data('rocketkit-map-init')) {
			return;
		}
		$canvas.data('rocketkit-map-init', true);

		var scrollWheelZoom = config.scrollWheelZoom !== false;
		var map = L.map($canvas[0], {
			scrollWheelZoom: scrollWheelZoom,
		}).setView([config.center.lat, config.center.lng], config.zoom || 4);

		var tileKey = TILES[config.tileStyle] ? config.tileStyle : 'osm';
		L.tileLayer(TILES[tileKey].url, {
			attribution: TILES[tileKey].attribution,
			maxZoom: 19,
		}).addTo(map);

		if (config.displayMode === 'markers' && config.markers) {
			var leafletMarkerOptions = getLeafletMarkerOptions(config.markerIcon);
			config.markers.forEach(function (marker) {
				L.marker([marker.lat, marker.lng], leafletMarkerOptions)
					.addTo(map)
					.bindPopup(buildMarkerPopupHtml(marker), {
						className: 'rocketkit-leaflet-popup',
					});
			});
		}

		if (config.displayMode === 'heatmap' && config.heatmap && typeof L.heatLayer === 'function') {
			var points = config.heatmap.map(function (p) {
				return [p.lat, p.lng, p.intensity || 0.5];
			});
			L.heatLayer(points, {
				radius: config.heatRadius || 25,
				blur: config.heatBlur || 15,
				maxZoom: 17,
				gradient: buildLeafletHeatGradient(config.heatColors),
			}).addTo(map);
			addLeafletHeatmapLabels(map, config.heatmap);
		}

		if (config.displayMode === 'regions' && config.regions && config.regions.length) {
			initLeafletRegions(map, config.regions);
		}

		setTimeout(function () {
			map.invalidateSize();
		}, 200);
	}

	function initGoogleMap($widget, config) {
		var $canvas = $widget.find('.rocketkit-map-canvas');
		if (!$canvas.length || typeof google === 'undefined' || !google.maps) {
			return;
		}

		if ($canvas.data('rocketkit-map-init')) {
			return;
		}
		$canvas.data('rocketkit-map-init', true);

		var scrollWheelZoom = config.scrollWheelZoom !== false;
		var map = new google.maps.Map($canvas[0], {
			center: { lat: config.center.lat, lng: config.center.lng },
			zoom: config.zoom || 4,
			mapTypeControl: true,
			streetViewControl: false,
			gestureHandling: scrollWheelZoom ? 'greedy' : 'cooperative',
		});

		if (config.displayMode === 'markers' && config.markers) {
			var googleIcon = getGoogleMarkerIcon(config.markerIcon);
			config.markers.forEach(function (marker) {
				var info = new google.maps.InfoWindow({
					content: buildMarkerPopupHtml(marker),
				});
				var pinOptions = {
					position: { lat: marker.lat, lng: marker.lng },
					map: map,
					title: marker.title,
				};
				if (googleIcon) {
					pinOptions.icon = googleIcon;
				}
				var pin = new google.maps.Marker(pinOptions);
				pin.addListener('click', function () {
					info.open(map, pin);
				});
			});
		}

		if (config.displayMode === 'heatmap' && config.heatmap && google.maps.visualization) {
			var heatData = config.heatmap.map(function (p) {
				return {
					location: new google.maps.LatLng(p.lat, p.lng),
					weight: p.intensity || 0.5,
				};
			});
			new google.maps.visualization.HeatmapLayer({
				data: heatData,
				map: map,
				radius: config.heatRadius || 25,
				gradient: buildGoogleHeatGradient(config.heatColors),
			});
			addGoogleHeatmapLabels(map, config.heatmap);
		}

		if (config.displayMode === 'regions' && config.regions && config.regions.length) {
			initGoogleRegions(map, config.regions);
		}
	}

	function initMapWidget($widget) {
		var config = parseConfig($widget.find('.rocketkit-map-canvas'));
		if (!config.center) {
			return;
		}

		if (config.provider === 'google') {
			initGoogleMap($widget, config);
		} else {
			initLeafletMap($widget, config);
		}
	}

	function initAllMaps($scope) {
		$scope.find('.rocketkit-map-widget').each(function () {
			initMapWidget($(this));
		});
	}

	$(window).on('load', function () {
		initAllMaps($(document));
	});

	$(window).on('elementor/frontend/init', function () {
		if (typeof elementorFrontend === 'undefined') {
			return;
		}
		elementorFrontend.hooks.addAction('frontend/element_ready/rocketkit_interactive_map.default', function ($scope) {
			$scope.find('.rocketkit-map-canvas').removeData('rocketkit-map-init');
			initAllMaps($scope);
		});
	});
})(jQuery);
