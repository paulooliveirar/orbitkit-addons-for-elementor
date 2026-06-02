<?php
/**
 * Settings page template.
 *
 * @package RocketKit\Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render settings page markup.
 *
 * @param array<string, mixed> $rocketkit_context Template context.
 */
function rocketkit_elementor_render_settings_page_view( array $rocketkit_context ) {
	$rocketkit_active           = $rocketkit_context['active'];
	$rocketkit_integrations     = $rocketkit_context['integrations'];
	$rocketkit_option_key       = $rocketkit_context['option_key'];
	$rocketkit_integrations_key = $rocketkit_context['integrations_key'];
	$rocketkit_grouped          = $rocketkit_context['grouped'];
	$rocketkit_total_widgets    = $rocketkit_context['total_widgets'];
	$rocketkit_enabled_count    = $rocketkit_context['enabled_count'];
	$rocketkit_has_maps_key     = $rocketkit_context['has_maps_key'];

	$rocketkit_tabs = array(
		'general'      => array(
			'label' => __( 'General', 'rocketkit-addons-for-elementor' ),
			'desc'  => __( 'Overview & support', 'rocketkit-addons-for-elementor' ),
			'icon'  => 'dashicons-admin-home',
		),
		'elements'     => array(
			'label' => __( 'Elements', 'rocketkit-addons-for-elementor' ),
			'desc'  => __( 'Enable widgets', 'rocketkit-addons-for-elementor' ),
			'icon'  => 'dashicons-screenoptions',
		),
		'integrations' => array(
			'label' => __( 'Integrations', 'rocketkit-addons-for-elementor' ),
			'desc'  => __( 'API keys & services', 'rocketkit-addons-for-elementor' ),
			'icon'  => 'dashicons-admin-links',
		),
	);

	$rocketkit_section_labels = array(
		'content' => __( 'Content', 'rocketkit-addons-for-elementor' ),
		'media'   => __( 'Media', 'rocketkit-addons-for-elementor' ),
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
				<span class="rk-settings__logo-title"><?php esc_html_e( 'RocketKit Addons', 'rocketkit-addons-for-elementor' ); ?></span>
				<span class="rk-settings__logo-sub"><?php esc_html_e( 'For Elementor', 'rocketkit-addons-for-elementor' ); ?></span>
			</div>
			<span class="rk-pill">v<?php echo esc_html( ROCKETKIT_ELEMENTOR_VERSION ); ?></span>
		</div>
	</header>

	<form method="post" action="options.php" class="rk-settings__form" id="rk-settings-form">
		<?php settings_fields( 'rocketkit_elementor_settings_group' ); ?>

		<div class="rk-settings__shell">
			<aside class="rk-settings__aside">
				<p class="rk-settings__aside-label"><?php esc_html_e( 'Settings', 'rocketkit-addons-for-elementor' ); ?></p>
				<nav class="rk-settings__nav" role="tablist" aria-label="<?php esc_attr_e( 'Settings sections', 'rocketkit-addons-for-elementor' ); ?>">
					<?php foreach ( $rocketkit_tabs as $rocketkit_tab_id => $rocketkit_tab ) : ?>
						<button
							type="button"
							role="tab"
							id="rk-tab-<?php echo esc_attr( $rocketkit_tab_id ); ?>"
							class="rk-settings__nav-item<?php echo 'general' === $rocketkit_tab_id ? ' is-active' : ''; ?>"
							data-rk-tab="<?php echo esc_attr( $rocketkit_tab_id ); ?>"
							aria-selected="<?php echo 'general' === $rocketkit_tab_id ? 'true' : 'false'; ?>"
							aria-controls="rk-panel-<?php echo esc_attr( $rocketkit_tab_id ); ?>"
						>
							<span class="rk-settings__nav-icon dashicons <?php echo esc_attr( $rocketkit_tab['icon'] ); ?>" aria-hidden="true"></span>
							<span class="rk-settings__nav-copy">
								<span class="rk-settings__nav-label"><?php echo esc_html( $rocketkit_tab['label'] ); ?></span>
								<span class="rk-settings__nav-desc"><?php echo esc_html( $rocketkit_tab['desc'] ); ?></span>
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
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'General', 'rocketkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc"><?php esc_html_e( 'Plugin overview and quick links.', 'rocketkit-addons-for-elementor' ); ?></p>
					</header>

					<div class="rk-stat-row">
						<div class="rk-stat">
							<span class="rk-stat__value"><?php echo esc_html( (string) $rocketkit_total_widgets ); ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Total widgets', 'rocketkit-addons-for-elementor' ); ?></span>
						</div>
						<div class="rk-stat">
							<span class="rk-stat__value" id="rk-stat-enabled"><?php echo esc_html( (string) $rocketkit_enabled_count ); ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Enabled', 'rocketkit-addons-for-elementor' ); ?></span>
						</div>
						<div class="rk-stat">
							<span class="rk-stat__value rk-stat__value--sm"><?php echo $rocketkit_has_maps_key ? '✓' : '—'; ?></span>
							<span class="rk-stat__label"><?php esc_html_e( 'Google Maps', 'rocketkit-addons-for-elementor' ); ?></span>
						</div>
					</div>

					<div class="rk-card">
						<h2 class="rk-card__title"><?php esc_html_e( 'About RocketKit', 'rocketkit-addons-for-elementor' ); ?></h2>
						<p class="rk-card__text"><?php esc_html_e( 'Production-ready Elementor widgets with per-widget assets, region-based maps, and a focused editor experience.', 'rocketkit-addons-for-elementor' ); ?></p>
						<ul class="rk-link-list">
							<li>
								<span class="dashicons dashicons-email-alt"></span>
								<a href="mailto:suporte@orbittech.com.br">suporte@orbittech.com.br</a>
							</li>
							<li>
								<span class="dashicons dashicons-admin-users"></span>
								<a href="mailto:paulo.rodrigues@orbittech.com.br">Paulo Oliveira Rodrigues</a>
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
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'Elements', 'rocketkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc">
							<?php
							printf(
								/* translators: 1: enabled count, 2: total count */
								esc_html__( '%1$d of %2$d widgets active in Elementor.', 'rocketkit-addons-for-elementor' ),
								(int) $rocketkit_enabled_count,
								(int) $rocketkit_total_widgets
							);
							?>
						</p>
					</header>

					<div class="rk-toolbar">
						<div class="rk-toolbar__row">
							<div class="rk-search" role="search">
								<span class="dashicons dashicons-search" aria-hidden="true"></span>
								<label class="screen-reader-text" for="rk-widget-search"><?php esc_html_e( 'Search widgets', 'rocketkit-addons-for-elementor' ); ?></label>
								<input
									type="search"
									id="rk-widget-search"
									placeholder="<?php esc_attr_e( 'Search by name…', 'rocketkit-addons-for-elementor' ); ?>"
									autocomplete="off"
								/>
							</div>
							<div class="rk-toolbar__actions">
								<label class="screen-reader-text" for="rk-widget-filter"><?php esc_html_e( 'Filter category', 'rocketkit-addons-for-elementor' ); ?></label>
								<label class="rk-toggle rk-toggle--inline">
									<span class="rk-toggle__label" id="rk-enable-all-global-label"><?php esc_html_e( 'Disable all', 'rocketkit-addons-for-elementor' ); ?></span>
									<input type="checkbox" class="rk-toggle__input" id="rk-enable-all-global" checked />
									<span class="rk-toggle__track" aria-hidden="true"></span>
								</label>
							</div>
						</div>
						<div class="rk-chips" role="group" aria-label="<?php esc_attr_e( 'Quick filters', 'rocketkit-addons-for-elementor' ); ?>">
							<button type="button" class="rk-chip is-active" data-rk-cat="all"><?php esc_html_e( 'All', 'rocketkit-addons-for-elementor' ); ?></button>
							<button type="button" class="rk-chip" data-rk-cat="content"><?php esc_html_e( 'Content', 'rocketkit-addons-for-elementor' ); ?></button>
							<button type="button" class="rk-chip" data-rk-cat="media"><?php esc_html_e( 'Media', 'rocketkit-addons-for-elementor' ); ?></button>
						</div>
					</div>

					<?php
					foreach ( $rocketkit_grouped as $rocketkit_cat_slug => $rocketkit_cat_widgets ) :
						if ( empty( $rocketkit_cat_widgets ) ) {
							continue;
						}
						?>
						<div class="rk-section" data-rk-section="<?php echo esc_attr( $rocketkit_cat_slug ); ?>">
							<div class="rk-section__head">
								<h2 class="rk-section__title"><?php echo esc_html( $rocketkit_section_labels[ $rocketkit_cat_slug ] ?? ucfirst( $rocketkit_cat_slug ) ); ?></h2>
								<label class="rk-toggle rk-toggle--compact">
									<span class="rk-toggle__label"><?php esc_html_e( 'Enable section', 'rocketkit-addons-for-elementor' ); ?></span>
									<input type="checkbox" class="rk-toggle__input rk-enable-section" data-section="<?php echo esc_attr( $rocketkit_cat_slug ); ?>" checked />
									<span class="rk-toggle__track" aria-hidden="true"></span>
								</label>
							</div>
							<div class="rk-grid">
								<?php
								foreach ( $rocketkit_cat_widgets as $rocketkit_slug => $rocketkit_widget ) :
									$rocketkit_is_on  = ! empty( $rocketkit_active[ $rocketkit_slug ] );
									$rocketkit_badge  = isset( $rocketkit_widget['badge'] ) ? $rocketkit_widget['badge'] : '';
									$rocketkit_icon   = isset( $rocketkit_widget['icon'] ) ? $rocketkit_widget['icon'] : 'dashicons-admin-generic';
									$rocketkit_search = strtolower( $rocketkit_widget['title'] );
									?>
									<article
										class="rk-element-card<?php echo $rocketkit_is_on ? ' is-on' : ' is-off'; ?>"
										data-rk-widget="<?php echo esc_attr( $rocketkit_slug ); ?>"
										data-rk-category="<?php echo esc_attr( $rocketkit_cat_slug ); ?>"
										data-rk-search="<?php echo esc_attr( $rocketkit_search ); ?>"
									>
										<div class="rk-element-card__head">
											<div class="rk-element-card__icon-wrap">
												<span class="dashicons <?php echo esc_attr( $rocketkit_icon ); ?>" aria-hidden="true"></span>
											</div>
											<label class="rk-toggle rk-toggle--card" title="<?php esc_attr_e( 'Toggle widget', 'rocketkit-addons-for-elementor' ); ?>">
												<input
													type="checkbox"
													class="rk-toggle__input rk-widget-toggle"
													name="<?php echo esc_attr( $rocketkit_option_key . '[' . $rocketkit_slug . ']' ); ?>"
													value="1"
													<?php checked( $rocketkit_is_on ); ?>
													aria-label="<?php
													echo esc_attr(
														sprintf(
															/* translators: %s: widget title */
															__( 'Enable %s', 'rocketkit-addons-for-elementor' ),
															$rocketkit_widget['title']
														)
													);
													?>"
												/>
												<span class="rk-toggle__track" aria-hidden="true"></span>
											</label>
										</div>
										<div class="rk-element-card__body">
											<div class="rk-element-card__title-row">
												<h3 class="rk-element-card__title"><?php echo esc_html( $rocketkit_widget['title'] ); ?></h3>
												<?php if ( 'popular' === $rocketkit_badge ) : ?>
													<span class="rk-badge rk-badge--accent"><?php esc_html_e( 'Popular', 'rocketkit-addons-for-elementor' ); ?></span>
												<?php elseif ( 'new' === $rocketkit_badge ) : ?>
													<span class="rk-badge rk-badge--warn"><?php esc_html_e( 'New', 'rocketkit-addons-for-elementor' ); ?></span>
												<?php endif; ?>
											</div>
											<p class="rk-element-card__desc"><?php echo esc_html( $rocketkit_widget['description'] ); ?></p>
										</div>
										<div class="rk-element-card__foot">
											<span class="rk-status">
												<span class="rk-status__dot" aria-hidden="true"></span>
												<span class="rk-status__text"><?php echo $rocketkit_is_on ? esc_html__( 'Active', 'rocketkit-addons-for-elementor' ) : esc_html__( 'Inactive', 'rocketkit-addons-for-elementor' ); ?></span>
											</span>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>

					<div class="rk-empty" id="rk-elements-empty" hidden>
						<span class="rk-empty__icon dashicons dashicons-search" aria-hidden="true"></span>
						<h3 class="rk-empty__title"><?php esc_html_e( 'No widgets found', 'rocketkit-addons-for-elementor' ); ?></h3>
						<p class="rk-empty__text"><?php esc_html_e( 'Try a different search term or reset your filters.', 'rocketkit-addons-for-elementor' ); ?></p>
						<button type="button" class="rk-btn rk-btn--ghost" id="rk-clear-filters"><?php esc_html_e( 'Clear filters', 'rocketkit-addons-for-elementor' ); ?></button>
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
						<h1 class="rk-panel-head__title"><?php esc_html_e( 'Integrations', 'rocketkit-addons-for-elementor' ); ?></h1>
						<p class="rk-panel-head__desc"><?php esc_html_e( 'Connect third-party services used by your widgets.', 'rocketkit-addons-for-elementor' ); ?></p>
					</header>

					<div class="rk-card rk-card--integration">
						<div class="rk-integration__header">
							<div class="rk-integration__icon" aria-hidden="true">
								<span class="dashicons dashicons-location-alt"></span>
							</div>
							<div class="rk-integration__meta">
								<h2 class="rk-card__title"><?php esc_html_e( 'Google Maps', 'rocketkit-addons-for-elementor' ); ?></h2>
								<p class="rk-card__text"><?php esc_html_e( 'Required when the Interactive Map widget uses Google Maps as provider.', 'rocketkit-addons-for-elementor' ); ?></p>
							</div>
							<span class="rk-pill rk-pill--<?php echo $rocketkit_has_maps_key ? 'success' : 'muted'; ?>" id="rk-maps-status">
								<?php echo $rocketkit_has_maps_key ? esc_html__( 'Configured', 'rocketkit-addons-for-elementor' ) : esc_html__( 'Not set', 'rocketkit-addons-for-elementor' ); ?>
							</span>
						</div>
						<div class="rk-field">
							<label class="rk-field__label" for="rk-google-maps-api-key"><?php esc_html_e( 'API key', 'rocketkit-addons-for-elementor' ); ?></label>
							<input
								type="password"
								id="rk-google-maps-api-key"
								class="rk-field__input"
								name="<?php echo esc_attr( $rocketkit_integrations_key . '[google_maps_api_key]' ); ?>"
								value="<?php echo esc_attr( $rocketkit_integrations['google_maps_api_key'] ); ?>"
								placeholder="AIzaSy…"
								autocomplete="off"
								spellcheck="false"
							/>
							<p class="rk-field__hint">
								<?php
								echo wp_kses_post(
									sprintf(
										/* translators: %s: Google Cloud Console link */
										__( 'Enable Maps JavaScript API and Visualization API in the %s.', 'rocketkit-addons-for-elementor' ),
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
			<p class="rk-settings__footer-note"><?php esc_html_e( 'Changes apply after saving. Disabled widgets are hidden in Elementor.', 'rocketkit-addons-for-elementor' ); ?></p>
			<?php submit_button( __( 'Save settings', 'rocketkit-addons-for-elementor' ), 'primary rk-btn rk-btn--primary', 'submit', false ); ?>
		</footer>
	</form>
</div>
	<?php
}
