<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin activation.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Activator {

	public static function activate(): void {
		// Register CPTs so rewrite rules flush correctly.
		require_once SEP_PLUGIN_DIR . 'includes/class-post-types.php';
		$post_types = new SE_Portfolio_Post_Types();
		$post_types->register();

		// Seed the About Me option with empty defaults.
		if ( false === get_option( 'sep_about' ) ) {
			add_option( 'sep_about', self::default_about() );
		}

		// Seed the Style Settings option with GitHub Dark defaults.
		if ( false === get_option( 'sep_style' ) ) {
			require_once SEP_PLUGIN_DIR . 'admin/class-style-settings.php';
			add_option( 'sep_style', SE_Portfolio_Style_Settings::get_defaults() );
		}

		add_option( 'sep_version', SEP_VERSION );

		flush_rewrite_rules();
	}

	/**
	 * Returns an array of empty defaults for the About Me settings.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private static function default_about(): array {
		return [
			'name'       => '',
			'job_title'  => '',
			'short_bio'  => '',
			'long_bio'   => '',
			'location'   => '',
			'email'      => '',
			'github_url' => '',
			'linkedin_url' => '',
			'cv_url'     => '',
			'years_exp'  => 0,
			'available'  => 0,
			'photo_id'   => 0,
		];
	}
}
