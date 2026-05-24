<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the Style Settings admin page, option registration, and sanitization.
 *
 * @since 1.1.0
 */
class SE_Portfolio_Style_Settings {

	/** @var string Hook suffix for the Style Settings submenu page. */
	private string $page_hook = '';

	// -------------------------------------------------------------------------
	// Defaults
	// -------------------------------------------------------------------------

	/**
	 * Returns the full default style values — the GitHub Dark theme.
	 * This is the single source of truth used by activation, reset, and injection.
	 *
	 * @since  1.1.0
	 * @return array<string, mixed>
	 */
	public static function get_defaults(): array {
		$empty_component = [
			'bg'      => '',
			'surface' => '',
			'accent'  => '',
			'border'  => '',
			'text'    => '',
			'muted'   => '',
		];

		return [
			'bg'        => '#0a0c10',
			'surface'   => '#0f1318',
			'surface2'  => '#141c24',
			'border'    => '#1d2535',
			'accent'    => '#00d26a',
			'green'     => '#00d26a',
			'text'      => '#cdd9e5',
			'muted'     => '#5c6773',
			'prompt'    => '#79c0ff',
			'warning'   => '#f0b429',
			'radius'    => '3px',
			'font_mono' => "'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace",
			'font_body' => "'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace",

			// Typography sizes
			'base_size'       => '15px',
			'hero_name_size'  => 'clamp(1.6rem, 4vw, 2.4rem)',

			// Spacing
			'section_py'      => '80px',
			'card_pad'        => '16px',
			'hero_pt'         => '160px',
			'container_max'   => '1100px',

			// Transition speed
			'transition'      => '0.15s',

			// Effect toggles (1 = on, 0 = off)
			'show_glows'      => 1,
			'show_scanlines'  => 1,
			'show_animations' => 1,
			'show_blink'      => 1,

			// Card style: 'terminal' | 'flat'
			'card_style'      => 'terminal',

			// Custom CSS (sanitized with wp_strip_all_tags on save)
			'custom_css'      => '',

			'components' => [
				'hero'         => $empty_component,
				'about'        => $empty_component,
				'skills'       => $empty_component,
				'projects'     => $empty_component,
				'experience'   => $empty_component,
				'education'    => $empty_component,
				'certificates' => $empty_component,
				'contact'      => $empty_component,
				'footer'       => $empty_component,
			],
		];
	}

	// -------------------------------------------------------------------------
	// Menu
	// -------------------------------------------------------------------------

	/**
	 * Registers the Style Settings submenu under SE Portfolio.
	 *
	 * @since 1.1.0
	 */
	public function register_menu(): void {
		$this->page_hook = (string) add_submenu_page(
			'se-portfolio',
			__( 'Style Settings', 'se-portfolio' ),
			__( 'Style Settings', 'se-portfolio' ),
			'manage_options',
			'sep-style-settings',
			[ $this, 'render_page' ]
		);
	}

	// -------------------------------------------------------------------------
	// Settings
	// -------------------------------------------------------------------------

	/**
	 * Registers the sep_style option with WordPress Settings API.
	 *
	 * @since 1.1.0
	 */
	public function register_settings(): void {
		register_setting(
			'sep_style_group',
			'sep_style',
			[
				'sanitize_callback' => [ $this, 'sanitize_style' ],
				'default'           => self::get_defaults(),
			]
		);
	}

	/**
	 * Sanitizes the style settings array on save.
	 *
	 * @since  1.1.0
	 * @param  array<string, mixed> $input Raw POST input.
	 * @return array<string, mixed>        Sanitized output.
	 */
	public function sanitize_style( array $input ): array {
		$defaults   = self::get_defaults();
		$output     = [];
		$color_keys = [ 'bg', 'surface', 'surface2', 'border', 'accent', 'green', 'text', 'muted', 'prompt', 'warning' ];

		foreach ( $color_keys as $key ) {
			$raw       = isset( $input[ $key ] ) ? (string) $input[ $key ] : '';
			$sanitized = sanitize_hex_color( $raw );
			// Fall back to default if value is invalid (sanitize_hex_color returns null for invalid).
			$output[ $key ] = ( null !== $sanitized ) ? $sanitized : $defaults[ $key ];
		}

		// Border radius — allow only digits, dots, and common CSS length units.
		$radius = preg_replace( '/[^0-9a-zA-Z.%]/', '', sanitize_text_field( $input['radius'] ?? '' ) );
		$output['radius'] = '' !== $radius ? substr( $radius, 0, 20 ) : $defaults['radius'];

		// Font families — sanitize as text; CSS injection chars stripped on output.
		$output['font_mono'] = sanitize_text_field( $input['font_mono'] ?? $defaults['font_mono'] );
		$output['font_body'] = sanitize_text_field( $input['font_body'] ?? $defaults['font_body'] );

		// Per-component color overrides — empty string means "inherit global".
		$allowed_components   = array_keys( $defaults['components'] );
		$color_override_keys  = [ 'bg', 'surface', 'accent', 'border', 'text', 'muted' ];
		$output['components'] = [];

		foreach ( $allowed_components as $component ) {
			$comp_input = isset( $input['components'][ $component ] ) && is_array( $input['components'][ $component ] )
				? $input['components'][ $component ]
				: [];

			$output['components'][ $component ] = [];
			foreach ( $color_override_keys as $key ) {
				$raw = isset( $comp_input[ $key ] ) ? (string) $comp_input[ $key ] : '';
				if ( '' === $raw ) {
					$output['components'][ $component ][ $key ] = '';
				} else {
					$sanitized = sanitize_hex_color( $raw );
					$output['components'][ $component ][ $key ] = ( null !== $sanitized ) ? $sanitized : '';
				}
			}
		}

		// CSS length/size values — allow digits, units, spaces, commas, parens (clamp syntax).
		$css_length_keys = [
			'base_size'     => 40,
			'section_py'    => 40,
			'card_pad'      => 40,
			'hero_pt'       => 40,
			'container_max' => 40,
			'transition'    => 40,
			'hero_name_size'=> 80,
		];
		foreach ( $css_length_keys as $key => $max_len ) {
			$raw = preg_replace( '/[^0-9a-zA-Z.%\s,()]/', '', sanitize_text_field( $input[ $key ] ?? '' ) );
			$output[ $key ] = '' !== $raw ? substr( $raw, 0, $max_len ) : $defaults[ $key ];
		}

		// Boolean effect toggles — 0 or 1.
		foreach ( [ 'show_glows', 'show_scanlines', 'show_animations', 'show_blink' ] as $key ) {
			$output[ $key ] = isset( $input[ $key ] ) ? 1 : 0;
		}

		// Card style — allowlisted.
		$card_style_raw    = sanitize_key( $input['card_style'] ?? 'terminal' );
		$output['card_style'] = in_array( $card_style_raw, [ 'terminal', 'flat' ], true ) ? $card_style_raw : 'terminal';

		// Custom CSS — strip HTML tags; raw CSS content is kept as-is.
		$output['custom_css'] = wp_strip_all_tags( $input['custom_css'] ?? '' );

		return $output;
	}

	// -------------------------------------------------------------------------
	// Page Render
	// -------------------------------------------------------------------------

	/**
	 * Renders the Style Settings admin page.
	 *
	 * @since 1.1.0
	 */
	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'se-portfolio' ) );
		}
		include SEP_PLUGIN_DIR . 'admin/partials/style-settings.php';
	}

	// -------------------------------------------------------------------------
	// Assets
	// -------------------------------------------------------------------------

	/**
	 * Enqueues the WordPress color picker and our style-settings assets,
	 * but only on the Style Settings admin page.
	 *
	 * @since 1.1.0
	 * @param string $hook_suffix Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( $this->page_hook !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'sep-style-settings',
			SEP_PLUGIN_URL . 'admin/css/style-settings.css',
			[ 'wp-color-picker' ],
			SEP_VERSION
		);

		wp_enqueue_script(
			'sep-style-settings',
			SEP_PLUGIN_URL . 'admin/js/style-settings.js',
			[ 'jquery', 'wp-color-picker' ],
			SEP_VERSION,
			true
		);

		wp_localize_script(
			'sep-style-settings',
			'sepStyleData',
			[
				'nonce'   => wp_create_nonce( 'sep_reset_style' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'presets' => self::get_presets(),
				'i18n'    => [
					'resetConfirm' => __( 'Reset all style settings to the GitHub Dark defaults?', 'se-portfolio' ),
					'resetSuccess' => __( 'Style reset to defaults.', 'se-portfolio' ),
					'resetError'   => __( 'Reset failed. Please try again.', 'se-portfolio' ),
				],
			]
		);
	}

	// -------------------------------------------------------------------------
	// AJAX: Reset to Defaults
	// -------------------------------------------------------------------------

	/**
	 * Handles the AJAX request to reset sep_style to its default values.
	 *
	 * @since 1.1.0
	 */
	public function ajax_reset_defaults(): void {
		check_ajax_referer( 'sep_reset_style', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Permission denied.', 'se-portfolio' ) ], 403 );
		}

		update_option( 'sep_style', self::get_defaults() );

		wp_send_json_success( [ 'defaults' => self::get_defaults() ] );
	}

	// -------------------------------------------------------------------------
	// Preset Data
	// -------------------------------------------------------------------------

	/**
	 * Returns the full palette data for each built-in preset theme.
	 * Passed to JavaScript so presets can be applied client-side.
	 *
	 * @since  1.1.0
	 * @return array<string, array<string, string>>
	 */
	public static function get_presets(): array {
		$shared = [
			'radius'         => '3px',
			'font_mono'      => "'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace",
			'font_body'      => "'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace",
			'base_size'      => '15px',
			'hero_name_size' => 'clamp(1.6rem, 4vw, 2.4rem)',
			'section_py'     => '80px',
			'card_pad'       => '16px',
			'hero_pt'        => '160px',
			'container_max'  => '1100px',
			'transition'     => '0.15s',
			'show_glows'     => 1,
			'show_scanlines' => 1,
			'show_animations'=> 1,
			'show_blink'     => 1,
			'card_style'     => 'terminal',
			'custom_css'     => '',
		];

		return [
			'github-dark' => array_merge( $shared, [
				'bg'       => '#0a0c10',
				'surface'  => '#0f1318',
				'surface2' => '#141c24',
				'border'   => '#1d2535',
				'accent'   => '#00d26a',
				'green'    => '#00d26a',
				'text'     => '#cdd9e5',
				'muted'    => '#5c6773',
				'prompt'   => '#79c0ff',
				'warning'  => '#f0b429',
			] ),
			'ocean-blue' => array_merge( $shared, [
				'bg'       => '#0a0e1a',
				'surface'  => '#0d1522',
				'surface2' => '#112030',
				'border'   => '#1a2d45',
				'accent'   => '#0969da',
				'green'    => '#0969da',
				'text'     => '#c9d1d9',
				'muted'    => '#586069',
				'prompt'   => '#58a6ff',
				'warning'  => '#e3b341',
			] ),
			'dracula' => array_merge( $shared, [
				'bg'       => '#282a36',
				'surface'  => '#21222c',
				'surface2' => '#1e1f2b',
				'border'   => '#44475a',
				'accent'   => '#bd93f9',
				'green'    => '#50fa7b',
				'text'     => '#f8f8f2',
				'muted'    => '#6272a4',
				'prompt'   => '#8be9fd',
				'warning'  => '#ffb86c',
			] ),
			'nord' => array_merge( $shared, [
				'bg'       => '#2e3440',
				'surface'  => '#3b4252',
				'surface2' => '#434c5e',
				'border'   => '#4c566a',
				'accent'   => '#88c0d0',
				'green'    => '#a3be8c',
				'text'     => '#eceff4',
				'muted'    => '#4c566a',
				'prompt'   => '#81a1c1',
				'warning'  => '#ebcb8b',
			] ),
			'purple-haze' => array_merge( $shared, [
				'bg'       => '#0d0b14',
				'surface'  => '#13111c',
				'surface2' => '#1a1727',
				'border'   => '#2d2845',
				'accent'   => '#a855f7',
				'green'    => '#a855f7',
				'text'     => '#e2d9f3',
				'muted'    => '#6b5c8b',
				'prompt'   => '#c084fc',
				'warning'  => '#fb923c',
			] ),
			'solarized-dark' => array_merge( $shared, [
				'bg'       => '#002b36',
				'surface'  => '#073642',
				'surface2' => '#0e4350',
				'border'   => '#1a5563',
				'accent'   => '#2aa198',
				'green'    => '#859900',
				'text'     => '#fdf6e3',
				'muted'    => '#657b83',
				'prompt'   => '#268bd2',
				'warning'  => '#cb4b16',
			] ),
		];
	}
}
