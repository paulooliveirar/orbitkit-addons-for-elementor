<?php
/**
 * Settings page template.
 *
 * @package OrbitKit\Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render settings page markup.
 *
 * @param array<string, mixed> $orbitkit_context Template context.
 */
function orbitkit_elementor_render_settings_page_view( array $orbitkit_context ) {
	$orbitkit_active           = $orbitkit_context['active'];
	$orbitkit_integrations     = $orbitkit_context['integrations'];
	$orbitkit_option_key       = $orbitkit_context['option_key'];
	$orbitkit_integrations_key = $orbitkit_context['integrations_key'];
	$orbitkit_grouped          = $orbitkit_context['grouped'];
	$orbitkit_total_widgets    = $orbitkit_context['total_widgets'];
	$orbitkit_enabled_count    = $orbitkit_context['enabled_count'];
	$orbitkit_has_maps_key     = $orbitkit_context['has_maps_key'];

	$orbitkit_tabs = array(
		'general'      => array(
			'label' => __( 'General', 'orbitkit-addons-for-elementor' ),
			'desc'  => __( 'Overview & support', 'orbitkit-addons-for-elementor' ),
			'icon'  => 'dashicons-admin-home',
		),
		'elements'     => array(
			'label' => __( 'Elements', 'orbitkit-addons-for-elementor' ),
			'desc'  => __( 'Enable widgets', 'orbitkit-addons-for-elementor' ),
			'icon'  => 'dashicons-screenoptions',
		),
		'integrations' => array(
			'label' => __( 'Integrations', 'orbitkit-addons-for-elementor' ),
			'desc'  => __( 'API keys & services', 'orbitkit-addons-for-elementor' ),
			'icon'  => 'dashicons-admin-links',
		),
	);

	$orbitkit_section_labels = array(
		'content' => __( 'Content', 'orbitkit-addons-for-elementor' ),
		'media'   => __( 'Media', 'orbitkit-addons-for-elementor' ),
	);
	?>
<div class="rk-settings" id="rk-settings-app">
	<header class="rk-settings__header">
		<div class="rk-settings__brand">
			<span class="rk-settings__logo-mark" aria-hidden="true">
				<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="32" height="32" rx="10" fill="currentColor"/>
					<path d="M10 16L14.5 20.5L22 11" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
			<div class="rk-settings__brand-text">
				<span class="rk-settings__logo-title"><?php esc_html_e( 'OrbitKit Addons', 'orbitkit-addons-for-elementor' ); ?></span>
				<span class="rk-settings__logo-sub"><?php esc_html_e( 'For Elementor', 'orbitkit-addons-for-elementor' ); ?></span>
			</div>
			<span class="rk-pill">v<?php echo esc_html( ORBITKIT_ELEMENTOR_VERSION ); ?></span>
		</div>
	</header>

	<form method="post" action="options.php" class="rk-settings__form" id="rk-settings-form">
		<?php settings_fields( 'orbitkit_elementor_settings_group' ); ?>

		<div class="rk-settings__shell">
			<aside class="rk-settings__aside">
				<p class="rk-settings__aside-label"><?php esc_html_e( 'Settings', 'orbitkit-addons-for-elementor' ); ?></p>
				<nav class="rk-settings__nav" role="tablist" aria-label="<?php esc_attr_e( 'Settings sections', 'orbitkit-addons-for-elementor' ); ?>">
					<?php foreach ( $orbitkit_tabs as $orbitkit_tab_id => $orbitkit_tab ) : ?>
						<button
							type="button"
							role="tab"
							id="rk-tab-<?php echo esc_attr( $orbitkit_tab_id ); ?>"
							class="rk-settings__nav-item<?php echo 'general' === $orbitkit_tab_id ? ' is-active' : ''; ?>"
							data-rk-tab="<?php echo esc_attr( $orbitkit_tab_id ); ?>"
							aria-selected="<?php echo 'general' === $orbitkit_tab_id ? 'true' : 'false'; ?>"
							aria-controls="rk-panel-<?php echo esc_attr( $orbitkit_tab_id ); ?>"
						>
							<span class="rk-settings__nav-icon dashicons <?php echo esc_attr( $orbitkit_tab['icon'] ); ?>" aria-hidden="true"></span>
							<span class="rk-settings__nav-copy">
								<span class="rk-settings__nav-label"><?php echo esc_html( $orbitkit_tab['label'] ); ?></span>
								<span class="rk-settings__nav-desc"><?php echo esc_html( $orbitkit_tab['desc'] ); ?></span>
							</span>
						</button>
					<?php endforeach; ?>
				</nav>
			</aside>

			<main class="rk-settings__main">
				<section
					class="rk-settings__panel is-active"
					id="rk-panel-general"
					data-rk-panel="general"
					role="tabpanel"
					aria-labelledby="rk-tab-general"
				>
					<header class="rk-panel-head">
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'General', 'orbitkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc"><?php esc_html_e( 'Plugin overview and quick links.', 'orbitkit-addons-for-elementor' ); ?></p>
					</header>

					<div class="rk-stat-row">
						<div class="rk-stat">
							<span class="rk-stat__value"><?php echo esc_html( (string) $orbitkit_total_widgets ); ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Total widgets', 'orbitkit-addons-for-elementor' ); ?></span>
						</div>
						<div class="rk-stat">
							<span class="rk-stat__value" id="rk-stat-enabled"><?php echo esc_html( (string) $orbitkit_enabled_count ); ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Enabled', 'orbitkit-addons-for-elementor' ); ?></span>
						</div>
						<div class="rk-stat">
							<span class="rk-stat__value rk-stat__value--sm"><?php echo $orbitkit_has_maps_key ? '✓' : '—'; ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Google Maps', 'orbitkit-addons-for-elementor' ); ?></span>
						</div>
					</div>

					<div class="rk-card">
						<h2 class="rk-card__title"><?php esc_html_e( 'About OrbitKit', 'orbitkit-addons-for-elementor' ); ?></h2>
						<p class="rk-card__text"><?php esc_html_e( 'Production-ready Elementor widgets with per-widget assets, region-based maps, and a focused editor experience.', 'orbitkit-addons-for-elementor' ); ?></p>
						<ul class="rk-link-list">
							<li>
								<span class="dashicons dashicons-email-alt"></span>
								<a href="mailto:suporte@orbittech.com.br">suporte@orbittech.com.br</a>
							</li>
						</ul>
					</div>
				</section>

				<section
					class="rk-settings__panel"
					id="rk-panel-elements"
					data-rk-panel="elements"
					role="tabpanel"
					aria-labelledby="rk-tab-elements"
					hidden
				>
					<header class="rk-panel-head">
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'Elements', 'orbitkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc">
							<?php
							printf(
								/* translators: 1: enabled count, 2: total count */
								esc_html__( '%1$d of %2$d widgets active in Elementor.', 'orbitkit-addons-for-elementor' ),
								(int) $orbitkit_enabled_count,
								(int) $orbitkit_total_widgets
							);
							?>
						</p>
					</header>

					<div class="rk-toolbar">
						<div class="rk-toolbar__row">
							<div class="rk-search" role="search">
								<span class="dashicons dashicons-search" aria-hidden="true"></span>
								<label class="screen-reader-text" for="rk-widget-search"><?php esc_html_e( 'Search widgets', 'orbitkit-addons-for-elementor' ); ?></label>
								<input
									type="search"
									id="rk-widget-search"
									placeholder="<?php esc_attr_e( 'Search by name…', 'orbitkit-addons-for-elementor' ); ?>"
									autocomplete="off"
								/>
							</div>
							<div class="rk-toolbar__actions">
								<label class="screen-reader-text" for="rk-widget-filter"><?php esc_html_e( 'Filter category', 'orbitkit-addons-for-elementor' ); ?></label>
								<label class="rk-toggle rk-toggle--inline">
									<span class="rk-toggle__label" id="rk-enable-all-global-label"><?php esc_html_e( 'Disable all', 'orbitkit-addons-for-elementor' ); ?></span>
									<input type="checkbox" class="rk-toggle__input" id="rk-enable-all-global" checked />
									<span class="rk-toggle__track" aria-hidden="true"></span>
								</label>
							</div>
						</div>
						<div class="rk-chips" role="group" aria-label="<?php esc_attr_e( 'Quick filters', 'orbitkit-addons-for-elementor' ); ?>">
							<button type="button" class="rk-chip is-active" data-rk-cat="all"><?php esc_html_e( 'All', 'orbitkit-addons-for-elementor' ); ?></button>
							<button type="button" class="rk-chip" data-rk-cat="content"><?php esc_html_e( 'Content', 'orbitkit-addons-for-elementor' ); ?></button>
							<button type="button" class="rk-chip" data-rk-cat="media"><?php esc_html_e( 'Media', 'orbitkit-addons-for-elementor' ); ?></button>
						</div>
					</div>

					<?php
					foreach ( $orbitkit_grouped as $orbitkit_cat_slug => $orbitkit_cat_widgets ) :
						if ( empty( $orbitkit_cat_widgets ) ) {
							continue;
						}
						?>
						<div class="rk-section" data-rk-section="<?php echo esc_attr( $orbitkit_cat_slug ); ?>">
							<div class="rk-section__head">
								<h2 class="rk-section__title"><?php echo esc_html( $orbitkit_section_labels[ $orbitkit_cat_slug ] ?? ucfirst( $orbitkit_cat_slug ) ); ?></h2>
								<label class="rk-toggle rk-toggle--compact">
									<span class="rk-toggle__label"><?php esc_html_e( 'Enable section', 'orbitkit-addons-for-elementor' ); ?></span>
									<input type="checkbox" class="rk-toggle__input rk-enable-section" data-section="<?php echo esc_attr( $orbitkit_cat_slug ); ?>" checked />
									<span class="rk-toggle__track" aria-hidden="true"></span>
								</label>
							</div>
							<div class="rk-grid">
								<?php
								foreach ( $orbitkit_cat_widgets as $orbitkit_slug => $orbitkit_widget ) :
									$orbitkit_is_on  = ! empty( $orbitkit_active[ $orbitkit_slug ] );
									$orbitkit_badge  = isset( $orbitkit_widget['badge'] ) ? $orbitkit_widget['badge'] : '';
									$orbitkit_icon   = isset( $orbitkit_widget['icon'] ) ? $orbitkit_widget['icon'] : 'dashicons-admin-generic';
									$orbitkit_search = strtolower( $orbitkit_widget['title'] );
									?>
									<article
										class="rk-element-card<?php echo $orbitkit_is_on ? ' is-on' : ' is-off'; ?>"
										data-rk-widget="<?php echo esc_attr( $orbitkit_slug ); ?>"
										data-rk-category="<?php echo esc_attr( $orbitkit_cat_slug ); ?>"
										data-rk-search="<?php echo esc_attr( $orbitkit_search ); ?>"
									>
										<div class="rk-element-card__head">
											<div class="rk-element-card__icon-wrap">
												<span class="dashicons <?php echo esc_attr( $orbitkit_icon ); ?>" aria-hidden="true"></span>
											</div>
											<label class="rk-toggle rk-toggle--card" title="<?php esc_attr_e( 'Toggle widget', 'orbitkit-addons-for-elementor' ); ?>">
												<input
													type="checkbox"
													class="rk-toggle__input rk-widget-toggle"
													name="<?php echo esc_attr( $orbitkit_option_key . '[' . $orbitkit_slug . ']' ); ?>"
													value="1"
													<?php checked( $orbitkit_is_on ); ?>
													aria-label="<?php
													echo esc_attr(
														sprintf(
															/* translators: %s: widget title */
															__( 'Enable %s', 'orbitkit-addons-for-elementor' ),
															$orbitkit_widget['title']
														)
													);
													?>"
												/>
												<span class="rk-toggle__track" aria-hidden="true"></span>
											</label>
										</div>
										<div class="rk-element-card__body">
											<div class="rk-element-card__title-row">
												<h3 class="rk-element-card__title"><?php echo esc_html( $orbitkit_widget['title'] ); ?></h3>
												<?php if ( 'popular' === $orbitkit_badge ) : ?>
													<span class="rk-badge rk-badge--accent"><?php esc_html_e( 'Popular', 'orbitkit-addons-for-elementor' ); ?></span>
												<?php elseif ( 'new' === $orbitkit_badge ) : ?>
													<span class="rk-badge rk-badge--warn"><?php esc_html_e( 'New', 'orbitkit-addons-for-elementor' ); ?></span>
												<?php endif; ?>
											</div>
											<p class="rk-element-card__desc"><?php echo esc_html( $orbitkit_widget['description'] ); ?></p>
										</div>
										<div class="rk-element-card__foot">
											<span class="rk-status">
												<span class="rk-status__dot" aria-hidden="true"></span>
												<span class="rk-status__text"><?php echo $orbitkit_is_on ? esc_html__( 'Active', 'orbitkit-addons-for-elementor' ) : esc_html__( 'Inactive', 'orbitkit-addons-for-elementor' ); ?></span>
											</span>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>

					<div class="rk-empty" id="rk-elements-empty" hidden>
						<span class="rk-empty__icon dashicons dashicons-search" aria-hidden="true"></span>
						<h3 class="rk-empty__title"><?php esc_html_e( 'No widgets found', 'orbitkit-addons-for-elementor' ); ?></h3>
						<p class="rk-empty__text"><?php esc_html_e( 'Try a different search term or reset your filters.', 'orbitkit-addons-for-elementor' ); ?></p>
						<button type="button" class="rk-btn rk-btn--ghost" id="rk-clear-filters"><?php esc_html_e( 'Clear filters', 'orbitkit-addons-for-elementor' ); ?></button>
					</div>
				</section>

				<section
					class="rk-settings__panel"
					id="rk-panel-integrations"
					data-rk-panel="integrations"
					role="tabpanel"
					aria-labelledby="rk-tab-integrations"
					hidden
				>
					<header class="rk-panel-head">
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'Integrations', 'orbitkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc"><?php esc_html_e( 'Connect third-party services used by your widgets.', 'orbitkit-addons-for-elementor' ); ?></p>
					</header>

					<div class="rk-card rk-card--integration">
						<div class="rk-integration__header">
							<div class="rk-integration__icon" aria-hidden="true">
								<span class="dashicons dashicons-location-alt"></span>
							</div>
							<div class="rk-integration__meta">
								<h2 class="rk-card__title"><?php esc_html_e( 'Google Maps', 'orbitkit-addons-for-elementor' ); ?></h2>
								<p class="rk-card__text"><?php esc_html_e( 'Required when the Interactive Map widget uses Google Maps as provider.', 'orbitkit-addons-for-elementor' ); ?></p>
							</div>
							<span class="rk-pill rk-pill--<?php echo $orbitkit_has_maps_key ? 'success' : 'muted'; ?>" id="rk-maps-status">
								<?php echo $orbitkit_has_maps_key ? esc_html__( 'Configured', 'orbitkit-addons-for-elementor' ) : esc_html__( 'Not set', 'orbitkit-addons-for-elementor' ); ?>
							</span>
						</div>
						<div class="rk-field">
							<label class="rk-field__label" for="rk-google-maps-api-key"><?php esc_html_e( 'API key', 'orbitkit-addons-for-elementor' ); ?></label>
							<input
								type="password"
								id="rk-google-maps-api-key"
								class="rk-field__input"
								name="<?php echo esc_attr( $orbitkit_integrations_key . '[google_maps_api_key]' ); ?>"
								value="<?php echo esc_attr( $orbitkit_integrations['google_maps_api_key'] ); ?>"
								placeholder="AIzaSy…"
								autocomplete="off"
								spellcheck="false"
							/>
							<p class="rk-field__hint">
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: %s: Google Cloud Console link */
										__( 'Enable Maps JavaScript API and Visualization API in the %s.', 'orbitkit-addons-for-elementor' ),
										'<a href="https://console.cloud.google.com/google/maps-apis" target="_blank" rel="noopener noreferrer">Google Cloud Console</a>'
									)
								);
								?>
							</p>
						</div>
					</div>
				</section>
			</main>
		</div>

		<footer class="rk-settings__footer">
			<p class="rk-settings__footer-note"><?php esc_html_e( 'Changes apply after saving. Disabled widgets are hidden in Elementor.', 'orbitkit-addons-for-elementor' ); ?></p>
			<?php submit_button( __( 'Save settings', 'orbitkit-addons-for-elementor' ), 'primary rk-btn rk-btn--primary', 'submit', false ); ?>
		</footer>
	</form>
</div>
	<?php
}
