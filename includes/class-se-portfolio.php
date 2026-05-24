<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class — singleton orchestrator.
 *
 * @since 1.0.0
 */
class SE_Portfolio {

	private static ?self $instance = null;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_post_type_hooks();
		$this->define_meta_box_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_rest_hooks();
	}

	private function __clone() {}

	/**
	 * @throws \Exception Always — singletons cannot be unserialized.
	 */
	public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton.' );
	}

	private function load_dependencies(): void {
		require_once SEP_PLUGIN_DIR . 'includes/class-activator.php';
		require_once SEP_PLUGIN_DIR . 'includes/class-deactivator.php';
		require_once SEP_PLUGIN_DIR . 'includes/class-post-types.php';
		require_once SEP_PLUGIN_DIR . 'includes/class-meta-boxes.php';
		require_once SEP_PLUGIN_DIR . 'includes/class-rest-api.php';
		require_once SEP_PLUGIN_DIR . 'admin/class-admin.php';
		require_once SEP_PLUGIN_DIR . 'admin/class-style-settings.php';
		require_once SEP_PLUGIN_DIR . 'public/class-public.php';
	}

	private function set_locale(): void {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
	}

	public function load_textdomain(): void {
		load_plugin_textdomain(
			'se-portfolio',
			false,
			dirname( plugin_basename( SEP_PLUGIN_FILE ) ) . '/languages/'
		);
	}

	private function define_post_type_hooks(): void {
		$post_types = new SE_Portfolio_Post_Types();
		add_action( 'init', [ $post_types, 'register' ] );
		add_filter(
			'use_block_editor_for_post_type',
			static function ( bool $use, string $post_type ): bool {
				static $sep_types = [ 'sep_project', 'sep_skill', 'sep_experience', 'sep_education', 'sep_certificate' ];
				return in_array( $post_type, $sep_types, true ) ? false : $use;
			},
			10,
			2
		);
	}

	private function define_meta_box_hooks(): void {
		$meta_boxes = new SE_Portfolio_Meta_Boxes();
		add_action( 'add_meta_boxes', [ $meta_boxes, 'register' ] );
		add_action( 'save_post_sep_project',     [ $meta_boxes, 'save_project' ] );
		add_action( 'save_post_sep_skill',        [ $meta_boxes, 'save_skill' ] );
		add_action( 'save_post_sep_experience',   [ $meta_boxes, 'save_experience' ] );
		add_action( 'save_post_sep_education',    [ $meta_boxes, 'save_education' ] );
		add_action( 'save_post_sep_certificate',  [ $meta_boxes, 'save_certificate' ] );
	}

	private function define_admin_hooks(): void {
		$admin = new SE_Portfolio_Admin();
		add_action( 'admin_menu',             [ $admin, 'register_menus' ] );
		add_action( 'admin_init',             [ $admin, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts',  [ $admin, 'enqueue_assets' ] );
		add_action( 'admin_head',             [ $admin, 'inject_favicon' ], 1 );

		$style_settings = new SE_Portfolio_Style_Settings();
		add_action( 'admin_menu',             [ $style_settings, 'register_menu' ] );
		add_action( 'admin_init',             [ $style_settings, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts',  [ $style_settings, 'enqueue_assets' ] );
		add_action( 'wp_ajax_sep_reset_style', [ $style_settings, 'ajax_reset_defaults' ] );
	}

	private function define_public_hooks(): void {
		$public = new SE_Portfolio_Public();
		add_action( 'init',               [ $public, 'register_shortcodes' ] );
		add_action( 'wp_enqueue_scripts', [ $public, 'enqueue_assets' ] );
		add_filter( 'the_posts',          [ $public, 'detect_shortcodes' ] );
		add_action( 'wp_head',            [ $public, 'inject_page_overrides' ] );
		add_action( 'wp_head',            [ $public, 'inject_custom_styles' ], 15 );
		add_action( 'wp_head',            [ $public, 'inject_favicon' ], 1 );
		add_filter( 'template_include',   [ $public, 'template_404' ] );
		add_action( 'login_enqueue_scripts', [ $public, 'enqueue_login_assets' ] );
		add_filter( 'login_body_class',      [ $public, 'login_body_class' ] );
		add_filter( 'login_headerurl',       [ $public, 'login_header_url' ] );
		add_filter( 'login_headertext',      [ $public, 'login_header_text' ] );
	}

	private function define_rest_hooks(): void {
		$rest = new SE_Portfolio_Rest_API();
		add_action( 'rest_api_init', [ $rest, 'register_routes' ] );
	}

	public static function activate(): void {
		SE_Portfolio_Activator::activate();
	}

	public static function deactivate(): void {
		SE_Portfolio_Deactivator::deactivate();
	}
}
