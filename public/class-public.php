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

	public function enqueue_assets(): void {
		if ( ! $this->has_shortcode ) {
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

		wp_enqueue_script(
			'sep-portfolio',
			SEP_PLUGIN_URL . 'public/js/portfolio.js',
			[],
			SEP_VERSION,
			true
		);
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
				'limit'    => 12,
				'status'   => '',
				'featured' => '',
			],
			$atts,
			'sep_projects'
		);

		$limit    = absint( $atts['limit'] );
		$status   = sanitize_key( $atts['status'] );
		$featured = sanitize_key( $atts['featured'] );

		$query_args = [
			'post_type'      => 'sep_project',
			'post_status'    => 'publish',
			'posts_per_page' => $limit ?: 12,
			'no_found_rows'  => true,
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
		$atts  = shortcode_atts( [ 'limit' => 20 ], $atts, 'sep_certificates' );
		$limit = absint( $atts['limit'] );

		$certs_query = new WP_Query( [
			'post_type'      => 'sep_certificate',
			'post_status'    => 'publish',
			'posts_per_page' => $limit ?: 20,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

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

		$projects_query = new WP_Query( [
			'post_type'      => 'sep_project',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		] );

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

		$certs_query = new WP_Query( [
			'post_type'      => 'sep_certificate',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		] );

		ob_start();
		include SEP_PLUGIN_DIR . 'public/partials/portfolio-full.php';
		wp_reset_postdata();
		return ob_get_clean();
	}
}
