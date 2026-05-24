<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles shortcode registration and front-end asset enqueueing.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Public {

	/** @var bool Whether any portfolio shortcode was found on the current page. */
	private bool $has_shortcode = false;

	/** @var string[] All registered shortcode tags. */
	private const SHORTCODES = [
		'sep_about',
		'sep_projects',
		'sep_skills',
		'sep_experience',
		'sep_education',
		'sep_certificates',
		'sep_portfolio',
	];

	public function register_shortcodes(): void {
		add_shortcode( 'sep_about',        [ $this, 'shortcode_about' ] );
		add_shortcode( 'sep_projects',     [ $this, 'shortcode_projects' ] );
		add_shortcode( 'sep_skills',       [ $this, 'shortcode_skills' ] );
		add_shortcode( 'sep_experience',   [ $this, 'shortcode_experience' ] );
		add_shortcode( 'sep_education',    [ $this, 'shortcode_education' ] );
		add_shortcode( 'sep_certificates', [ $this, 'shortcode_certificates' ] );
		add_shortcode( 'sep_portfolio',    [ $this, 'shortcode_portfolio' ] );

		add_action( 'wp_ajax_sep_load_more',        [ $this, 'ajax_load_more' ] );
		add_action( 'wp_ajax_nopriv_sep_load_more', [ $this, 'ajax_load_more' ] );
	}

	/**
	 * Scans posts in the main query for any SE Portfolio shortcode.
	 * Runs on the_posts filter so assets can be enqueued via wp_enqueue_scripts.
	 *
	 * @param  WP_Post[] $posts
	 * @return WP_Post[]
	 */
	public function detect_shortcodes( array $posts ): array {
		if ( $this->has_shortcode ) {
			return $posts;
		}

		foreach ( $posts as $post ) {
			foreach ( self::SHORTCODES as $tag ) {
				if ( has_shortcode( $post->post_content, $tag ) ) {
					$this->has_shortcode = true;
					break 2;
				}
			}
		}

		return $posts;
	}

	/**
	 * Injects a small <style> block in <head> that hides the WordPress page
	 * title and removes extra padding added by the active theme, so the
	 * portfolio hero renders flush from the top of the content area.
	 *
	 * Targets the most common theme selectors; harmless if they don't match.
	 *
	 * @since 1.0.0
	 */
	public function inject_page_overrides(): void {
		if ( ! $this->has_shortcode ) {
			return;
		}
		?>
		<style id="sep-page-overrides">
			/* Kill the theme's white background so nothing bleeds through */
			html, body {
				overflow-x:       hidden !important;
				width:            100% !important;
				background-color: #0d1117 !important;
			}

			/* ---- Hide the theme header ---- */
			header.site-header,
			.site-header,
			#masthead,
			.wp-site-blocks > header,
			header.wp-block-template-part {
				display: none !important;
			}

			/* ---- Hide the theme footer ---- */
			footer.site-footer,
			.site-footer,
			#colophon,
			.wp-site-blocks > footer,
			footer.wp-block-template-part {
				display: none !important;
			}

			/* ---- Hide WordPress page title ---- */
			.page-title,
			.entry-title,
			h1.entry-title,
			h1.wp-block-post-title,
			.wp-block-post-title,
			.singular .entry-header,
			.entry-header,
			.post-header {
				display: none !important;
			}

			/* ---- Force the full content chain to be 100% wide ---- */
			.wp-site-blocks {
				padding-top:    0 !important;
				padding-bottom: 0 !important;
			}

			.wp-site-blocks > main,
			.wp-block-post-content,
			.entry-content,
			.page-content,
			.site-main,
			main, #main, #primary,
			.content-area,
			article.page,
			.hentry,
			.singular .entry-content,
			article.page > .entry-content,
			#page, #content, #wrap, #wrapper,
			.site-content, .content-wrapper, .page-wrapper,
			.wp-block-group, .wp-block-template-part,
			.is-layout-constrained, .is-layout-flow {
				max-width: 100% !important;
				width:     100% !important;
				padding:   0 !important;
				margin:    0 !important;
			}

			/* Remove WP core auto-centering on constrained layout children */
			.is-layout-constrained > *,
			.is-layout-flow > * {
				max-width:     100% !important;
				width:         100% !important;
				padding-left:  0 !important;
				padding-right: 0 !important;
				margin-left:   0 !important;
				margin-right:  0 !important;
			}
		</style>
		<?php
	}

	/**
	 * Outputs a <style> block that overrides the portfolio CSS custom properties
	 * with any values saved in the sep_style option. Runs after inject_page_overrides
	 * so variable overrides take precedence over the stylesheet defaults.
	 *
	 * All values are pre-validated at save time; font strings have CSS injection
	 * characters stripped before output.
	 *
	 * @since 1.1.0
	 */
	/**
	 * Builds the :root{} CSS custom-properties block from the saved sep_style option.
	 * Shared by inject_custom_styles() and enqueue_login_assets().
	 *
	 * @since  1.1.0
	 * @return string The :root{...} CSS declaration block.
	 */
	private function build_css_variables(): string {
		$style    = get_option( 'sep_style', [] );
		$defaults = SE_Portfolio_Style_Settings::get_defaults();

		$color_vars = [
			'bg'       => '--sep-bg',
			'surface'  => '--sep-surface',
			'surface2' => '--sep-surface2',
			'border'   => '--sep-border',
			'accent'   => '--sep-accent',
			'green'    => '--sep-green',
			'text'     => '--sep-text',
			'muted'    => '--sep-muted',
			'prompt'   => '--sep-prompt',
			'warning'  => '--sep-warning',
		];

		$lines = [];
		foreach ( $color_vars as $key => $var ) {
			$value   = isset( $style[ $key ] ) && '' !== $style[ $key ] ? $style[ $key ] : $defaults[ $key ];
			$lines[] = $var . ':' . $value;
		}

		$radius  = isset( $style['radius'] ) && '' !== $style['radius'] ? $style['radius'] : $defaults['radius'];
		$lines[] = '--sep-radius:' . $radius;

		$font_mono = preg_replace( '/[{}<>]/', '', $style['font_mono'] ?? $defaults['font_mono'] );
		$font_body = preg_replace( '/[{}<>]/', '', $style['font_body'] ?? $defaults['font_body'] );
		$lines[]   = '--sep-font-mono:' . $font_mono;
		$lines[]   = '--sep-font-body:' . $font_body;

		$design_vars = [
			'base_size'      => '--sep-base-size',
			'section_py'     => '--sep-section-py',
			'card_pad'       => '--sep-card-pad',
			'hero_pt'        => '--sep-hero-pt',
			'container_max'  => '--sep-container-max',
			'hero_name_size' => '--sep-hero-name-size',
			'transition'     => '--sep-transition',
		];
		foreach ( $design_vars as $key => $var ) {
			$value   = isset( $style[ $key ] ) && '' !== $style[ $key ] ? $style[ $key ] : $defaults[ $key ];
			$lines[] = $var . ':' . preg_replace( '/[{}<>]/', '', $value );
		}

		return ':root{' . implode( ';', $lines ) . '}';
	}

	public function inject_custom_styles(): void {
		if ( ! $this->has_shortcode && ! is_404() ) {
			return;
		}

		$style    = get_option( 'sep_style', [] );
		$defaults = SE_Portfolio_Style_Settings::get_defaults();

		$css = $this->build_css_variables();

		// Per-component overrides — scoped to each section's data attribute or class.
		$component_selectors = [
			'hero'         => '[data-sep-section="hero"]',
			'about'        => '[data-sep-section="about"]',
			'skills'       => '[data-sep-section="skills"]',
			'projects'     => '[data-sep-section="projects"]',
			'experience'   => '[data-sep-section="experience"]',
			'education'    => '[data-sep-section="education"]',
			'certificates' => '[data-sep-section="certificates"]',
			'contact'      => '[data-sep-section="contact"]',
			'footer'       => '.sep-footer',
		];
		$component_var_map = [
			'bg'      => '--sep-bg',
			'surface' => '--sep-surface',
			'accent'  => '--sep-accent',
			'border'  => '--sep-border',
			'text'    => '--sep-text',
			'muted'   => '--sep-muted',
		];

		$components = isset( $style['components'] ) && is_array( $style['components'] ) ? $style['components'] : [];
		foreach ( $components as $component => $overrides ) {
			if ( ! isset( $component_selectors[ $component ] ) || ! is_array( $overrides ) ) {
				continue;
			}
			$block = [];
			foreach ( $overrides as $key => $value ) {
				if ( '' !== $value && isset( $component_var_map[ $key ] ) ) {
					$block[] = $component_var_map[ $key ] . ':' . $value;
				}
			}
			if ( ! empty( $block ) ) {
				$css .= $component_selectors[ $component ] . '{' . implode( ';', $block ) . '}';
			}
		}

		// ---- Effect toggles — inject canned override blocks ----

		if ( ! ( $style['show_glows'] ?? $defaults['show_glows'] ) ) {
			$css .= '.sep-card:hover,.sep-contact-card:hover{box-shadow:none!important}'
				. '.sep-btn-primary,.sep-btn-primary:hover,.sep-topnav-cta,.sep-topnav-cta:hover,.sep-load-more:hover{box-shadow:none!important}'
				. '.sep-section-heading::before{text-shadow:none!important}'
				. '.sep-stat-number{text-shadow:none!important}'
				. '.sep-timeline-item-dot,.sep-badge-dot,.sep-nav-dot{box-shadow:none!important}';
		}

		if ( ! ( $style['show_scanlines'] ?? $defaults['show_scanlines'] ) ) {
			$css .= '.sep-hero::after{display:none!important}';
		}

		if ( ! ( $style['show_animations'] ?? $defaults['show_animations'] ) ) {
			$css .= '.sep-portfolio *{animation:none!important}';
		}

		if ( ! ( $style['show_blink'] ?? $defaults['show_blink'] ) ) {
			$css .= '.sep-blink::after{animation:none!important;opacity:1}';
		}

		$card_style = $style['card_style'] ?? $defaults['card_style'];
		if ( 'flat' === $card_style ) {
			$css .= '.sep-card-chrome{display:none!important}'
				. '.sep-card-body{border-radius:var(--sep-radius);border-top:1px solid var(--sep-border)}';
		}

		// Custom CSS — already stripped of HTML tags at save time.
		$custom_css = trim( $style['custom_css'] ?? '' );
		if ( '' !== $custom_css ) {
			$css .= $custom_css;
		}

		echo '<style id="sep-custom-styles">' . "\n" . $css . "\n" . '</style>' . "\n";
	}

	public function enqueue_assets(): void {
		$is_404 = is_404();

		if ( ! $this->has_shortcode && ! $is_404 ) {
			return;
		}

		// Google Fonts — JetBrains Mono + Inter
		wp_enqueue_style(
			'sep-google-fonts',
			'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Inter:wght@400;500;600&display=swap',
			[],
			null
		);

		wp_enqueue_style(
			'sep-portfolio',
			SEP_PLUGIN_URL . 'public/css/portfolio.css',
			[ 'sep-google-fonts' ],
			SEP_VERSION
		);

		if ( ! $is_404 ) {
			wp_enqueue_script(
				'sep-portfolio',
				SEP_PLUGIN_URL . 'public/js/portfolio.js',
				[],
				SEP_VERSION,
				true
			);

			wp_localize_script( 'sep-portfolio', 'sepAjax', [
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'sep_load_more' ),
			] );
		}

		if ( $is_404 ) {
			wp_enqueue_style(
				'sep-404',
				SEP_PLUGIN_URL . 'public/css/page-404.css',
				[ 'sep-portfolio' ],
				SEP_VERSION
			);
		}
	}

	/**
	 * Replaces WordPress's 404 template with the plugin's terminal-themed 404 page.
	 *
	 * @since  1.1.0
	 * @param  string $template Resolved template file path.
	 * @return string           Plugin's 404 template path when is_404(), otherwise unchanged.
	 */
	public function template_404( string $template ): string {
		if ( is_404() ) {
			$custom = SEP_PLUGIN_DIR . 'public/partials/page-404.php';
			if ( file_exists( $custom ) ) {
				return $custom;
			}
		}
		return $template;
	}

	// -------------------------------------------------------------------------
	// Shortcode Handlers
	// -------------------------------------------------------------------------

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_about( array $atts ): string {
		$about = get_option( 'sep_about', [] );
		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/about.php';
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_projects( array $atts ): string {
		$atts = shortcode_atts(
			[
				'limit'    => 6,
				'status'   => '',
				'featured' => '',
			],
			$atts,
			'sep_projects'
		);

		$limit    = absint( $atts['limit'] );
		$status   = sanitize_key( $atts['status'] );
		$featured = sanitize_key( $atts['featured'] );
		$per_page = $limit ?: 6;

		$query_args = [
			'post_type'      => 'sep_project',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => 1,
			'no_found_rows'  => false,
		];

		$meta_query = [];
		if ( $status ) {
			$meta_query[] = [ 'key' => '_sep_status', 'value' => $status ];
		}
		if ( 'true' === $featured ) {
			$meta_query[] = [ 'key' => '_sep_featured', 'value' => '1' ];
		}
		if ( $meta_query ) {
			$query_args['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery
		}

		$projects_query = new WP_Query( $query_args );
		$max_pages      = (int) $projects_query->max_num_pages;

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/projects.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_skills( array $atts ): string {
		$atts = shortcode_atts(
			[ 'category' => '' ],
			$atts,
			'sep_skills'
		);

		$category = sanitize_key( $atts['category'] );

		$query_args = [
			'post_type'      => 'sep_skill',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		];

		if ( $category ) {
			$query_args['meta_query'] = [ [ 'key' => '_sep_category', 'value' => $category ] ]; // phpcs:ignore WordPress.DB.SlowDBQuery
		}

		$skills_query = new WP_Query( $query_args );

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/skills.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_experience( array $atts ): string {
		$atts  = shortcode_atts( [ 'limit' => 20 ], $atts, 'sep_experience' );
		$limit = absint( $atts['limit'] );

		$experience_query = new WP_Query( [
			'post_type'      => 'sep_experience',
			'post_status'    => 'publish',
			'posts_per_page' => $limit ?: 20,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/experience.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_education( array $atts ): string {
		$atts  = shortcode_atts( [ 'limit' => 20 ], $atts, 'sep_education' );
		$limit = absint( $atts['limit'] );

		$education_query = new WP_Query( [
			'post_type'      => 'sep_education',
			'post_status'    => 'publish',
			'posts_per_page' => $limit ?: 20,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/education.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_certificates( array $atts ): string {
		$atts     = shortcode_atts( [ 'limit' => 6 ], $atts, 'sep_certificates' );
		$limit    = absint( $atts['limit'] );
		$per_page = $limit ?: 6;

		$certs_query = new WP_Query( [
			'post_type'      => 'sep_certificate',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => 1,
			'no_found_rows'  => false,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );
		$max_pages = (int) $certs_query->max_num_pages;

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/certificates.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * @param  array<string, string> $atts
	 */
	public function shortcode_portfolio( array $atts ): string {
		$about = get_option( 'sep_about', [] );

		$projects_per_page = 6;
		$projects_query    = new WP_Query( [
			'post_type'      => 'sep_project',
			'post_status'    => 'publish',
			'posts_per_page' => $projects_per_page,
			'paged'          => 1,
			'no_found_rows'  => false,
		] );
		$projects_max_pages = (int) $projects_query->max_num_pages;

		$skills_query = new WP_Query( [
			'post_type'      => 'sep_skill',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		$experience_query = new WP_Query( [
			'post_type'      => 'sep_experience',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		$education_query = new WP_Query( [
			'post_type'      => 'sep_education',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		$certs_per_page = 6;
		$certs_query    = new WP_Query( [
			'post_type'      => 'sep_certificate',
			'post_status'    => 'publish',
			'posts_per_page' => $certs_per_page,
			'paged'          => 1,
			'no_found_rows'  => false,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );
		$certs_max_pages = (int) $certs_query->max_num_pages;

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/portfolio-full.php';
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * AJAX handler: loads the next page of project or certificate cards.
	 *
	 * @since 1.0.0
	 */
	public function ajax_load_more(): void {
		check_ajax_referer( 'sep_load_more', 'nonce' );

		$section  = sanitize_key( wp_unslash( $_POST['section'] ?? '' ) );
		$page     = absint( $_POST['page'] ?? 1 );
		$per_page = absint( $_POST['per_page'] ?? 6 );

		if ( ! in_array( $section, [ 'projects', 'certificates' ], true ) || $page < 1 || $per_page < 1 ) {
			wp_send_json_error( [ 'message' => 'Invalid parameters.' ], 400 );
		}

		if ( 'projects' === $section ) {
			$query        = new WP_Query( [
				'post_type'      => 'sep_project',
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'no_found_rows'  => false,
			] );
			$card_partial = SEP_PLUGIN_DIR . 'public/partials/card-project.php';
		} else {
			$query        = new WP_Query( [
				'post_type'      => 'sep_certificate',
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'paged'          => $page,
				'no_found_rows'  => false,
				'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
			] );
			$card_partial = SEP_PLUGIN_DIR . 'public/partials/card-certificate.php';
		}

		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				include $card_partial; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
			}
		}
		$html = ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( [
			'html'      => $html,
			'max_pages' => (int) $query->max_num_pages,
		] );
	}

	// -------------------------------------------------------------------------
	// Login Page
	// -------------------------------------------------------------------------

	/**
	 * Enqueues the portfolio CSS and login-specific override CSS on wp-login.php,
	 * and injects the CSS custom-property block as an inline style.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_login_assets(): void {
		wp_enqueue_style(
			'sep-google-fonts',
			'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Inter:wght@400;500;600&display=swap',
			[],
			null
		);

		wp_enqueue_style(
			'sep-portfolio',
			SEP_PLUGIN_URL . 'public/css/portfolio.css',
			[ 'sep-google-fonts' ],
			SEP_VERSION
		);

		wp_add_inline_style( 'sep-portfolio', $this->build_css_variables() );

		wp_enqueue_style(
			'sep-login',
			SEP_PLUGIN_URL . 'public/css/page-login.css',
			[ 'sep-portfolio' ],
			SEP_VERSION
		);
	}

	/**
	 * Adds a body class to the login page for scoping.
	 *
	 * @since  1.1.0
	 * @param  string[] $classes
	 * @return string[]
	 */
	public function login_body_class( array $classes ): array {
		$classes[] = 'sep-login-page';
		return $classes;
	}

	/**
	 * Points the login logo link to the site home page.
	 *
	 * @since  1.1.0
	 * @return string
	 */
	public function login_header_url(): string {
		return home_url( '/' );
	}

	/**
	 * Replaces "Powered by WordPress" logo alt text with the site name.
	 *
	 * @since  1.1.0
	 * @return string
	 */
	public function login_header_text(): string {
		return get_bloginfo( 'name' );
	}

	/**
	 * Outputs a favicon <link> in <head> and removes WP core's site icon
	 * so only one favicon is ever present.
	 *
	 * @since 1.0.5
	 */
	public function inject_favicon(): void {
		remove_action( 'wp_head', 'wp_site_icon' );

		$about      = get_option( 'sep_about', [] );
		$favicon_id = (int) ( $about['favicon_id'] ?? 0 );

		if ( $favicon_id ) {
			$url = wp_get_attachment_image_url( $favicon_id, 'thumbnail' );
			if ( $url ) {
				printf( '<link rel="icon" href="%s">' . "\n", esc_url( $url ) );
				printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $url ) );
				return;
			}
		}

		$default = SEP_PLUGIN_URL . 'public/img/favicon.svg';
		printf( '<link rel="icon" type="image/svg+xml" href="%s">' . "\n", esc_url( $default ) );
	}
}
