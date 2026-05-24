<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the admin menus, settings, and asset enqueueing.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Admin {

	/** @var string[] Hook suffixes for our admin pages. */
	private array $page_hooks = [];

	public function register_menus(): void {
		$this->page_hooks[] = add_menu_page(
			__( 'SE Portfolio', 'se-portfolio' ),
			__( 'SE Portfolio', 'se-portfolio' ),
			'manage_options',
			'se-portfolio',
			[ $this, 'render_dashboard' ],
			'dashicons-laptop',
			25
		);

		// About Me is the first submenu (replaces the auto-generated duplicate).
		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'About Me', 'se-portfolio' ),
			__( 'About Me', 'se-portfolio' ),
			'manage_options',
			'se-portfolio',
			[ $this, 'render_dashboard' ]
		);

		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'Projects', 'se-portfolio' ),
			__( 'Projects', 'se-portfolio' ),
			'manage_options',
			'edit.php?post_type=sep_project'
		);

		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'Skills', 'se-portfolio' ),
			__( 'Skills', 'se-portfolio' ),
			'manage_options',
			'edit.php?post_type=sep_skill'
		);

		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'Experience', 'se-portfolio' ),
			__( 'Experience', 'se-portfolio' ),
			'manage_options',
			'edit.php?post_type=sep_experience'
		);

		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'Education', 'se-portfolio' ),
			__( 'Education', 'se-portfolio' ),
			'manage_options',
			'edit.php?post_type=sep_education'
		);

		$this->page_hooks[] = add_submenu_page(
			'se-portfolio',
			__( 'Certificates', 'se-portfolio' ),
			__( 'Certificates', 'se-portfolio' ),
			'manage_options',
			'edit.php?post_type=sep_certificate'
		);
	}

	public function register_settings(): void {
		register_setting(
			'sep_about_group',
			'sep_about',
			[
				'sanitize_callback' => [ $this, 'sanitize_about' ],
				'default'           => [],
			]
		);

		add_settings_section(
			'sep_about_profile',
			__( 'Profile', 'se-portfolio' ),
			'__return_false',
			'se-portfolio'
		);

		add_settings_section(
			'sep_about_contact',
			__( 'Contact & Links', 'se-portfolio' ),
			'__return_false',
			'se-portfolio'
		);

		add_settings_section(
			'sep_about_display',
			__( 'Display Options', 'se-portfolio' ),
			'__return_false',
			'se-portfolio'
		);

		add_settings_section(
			'sep_about_contact_section',
			__( 'Contact Section', 'se-portfolio' ),
			function () {
				echo '<p class="description">' . esc_html__( 'Controls the Contact Me section shown at the bottom of the portfolio.', 'se-portfolio' ) . '</p>';
			},
			'se-portfolio'
		);

		$this->add_settings_field( 'photo_id',      __( 'Profile Photo', 'se-portfolio' ),        'sep_about_profile',         'media' );
		$this->add_settings_field( 'name',           __( 'Full Name', 'se-portfolio' ),             'sep_about_profile',         'text' );
		$this->add_settings_field( 'job_title',      __( 'Job Title', 'se-portfolio' ),             'sep_about_profile',         'text' );
		$this->add_settings_field( 'short_bio',      __( 'Short Bio', 'se-portfolio' ),             'sep_about_profile',         'editor' );
		$this->add_settings_field( 'long_bio',       __( 'Long Bio', 'se-portfolio' ),              'sep_about_profile',         'editor' );
		$this->add_settings_field( 'location',       __( 'Location', 'se-portfolio' ),              'sep_about_profile',         'text' );
		$this->add_settings_field( 'years_exp',      __( 'Years of Experience', 'se-portfolio' ),   'sep_about_profile',         'number' );
		$this->add_settings_field( 'available',      __( 'Available for Work', 'se-portfolio' ),    'sep_about_profile',         'checkbox' );
		$this->add_settings_field( 'email',          __( 'Email', 'se-portfolio' ),                 'sep_about_contact',         'email' );
		$this->add_settings_field( 'phone',          __( 'Phone (optional)', 'se-portfolio' ),      'sep_about_contact',         'text' );
		$this->add_settings_field( 'github_url',     __( 'GitHub URL', 'se-portfolio' ),            'sep_about_contact',         'url' );
		$this->add_settings_field( 'linkedin_url',   __( 'LinkedIn URL', 'se-portfolio' ),          'sep_about_contact',         'url' );
		$this->add_settings_field( 'cv_url',         __( 'CV / Resume URL', 'se-portfolio' ),       'sep_about_contact',         'url' );
		$this->add_settings_field( 'show_hire_me',   __( 'Show "Hire Me" button (topnav)', 'se-portfolio' ),    'sep_about_display', 'checkbox' );
		$this->add_settings_field( 'show_cv_btn',    __( 'Show "Download CV" button (hero)', 'se-portfolio' ),  'sep_about_display', 'checkbox' );
		$this->add_settings_field( 'show_contact',   __( 'Show Contact section', 'se-portfolio' ),              'sep_about_display', 'checkbox' );
		$this->add_settings_field( 'contact_heading', __( 'Contact Heading', 'se-portfolio' ),      'sep_about_contact_section', 'text' );
		$this->add_settings_field( 'contact_intro',  __( 'Contact Intro Text', 'se-portfolio' ),    'sep_about_contact_section', 'textarea' );
	}

	private function add_settings_field( string $key, string $label, string $section, string $type ): void {
		add_settings_field(
			'sep_about_' . $key,
			$label,
			[ $this, 'render_field_' . $type ],
			'se-portfolio',
			$section,
			[ 'key' => $key ]
		);
	}

	// -------------------------------------------------------------------------
	// Field Renderers
	// -------------------------------------------------------------------------

	public function render_field_text( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = $about[ $key ] ?? '';
		printf(
			'<input type="text" name="sep_about[%s]" value="%s" class="regular-text">',
			esc_attr( $key ),
			esc_attr( $value )
		);
	}

	public function render_field_url( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = $about[ $key ] ?? '';
		printf(
			'<input type="url" name="sep_about[%s]" value="%s" class="regular-text">',
			esc_attr( $key ),
			esc_attr( $value )
		);
	}

	public function render_field_email( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = $about[ $key ] ?? '';
		printf(
			'<input type="email" name="sep_about[%s]" value="%s" class="regular-text">',
			esc_attr( $key ),
			esc_attr( $value )
		);
	}

	public function render_field_number( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = isset( $about[ $key ] ) ? (int) $about[ $key ] : 0;
		printf(
			'<input type="number" name="sep_about[%s]" value="%d" min="0" class="small-text">',
			esc_attr( $key ),
			$value
		);
	}

	public function render_field_checkbox( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = ! empty( $about[ $key ] );
		printf(
			'<input type="checkbox" name="sep_about[%s]" value="1" %s> %s',
			esc_attr( $key ),
			checked( $value, true, false ),
			esc_html__( 'Yes', 'se-portfolio' )
		);
	}

	public function render_field_textarea( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = $about[ $key ] ?? '';
		printf(
			'<textarea name="sep_about[%s]" rows="4" class="large-text">%s</textarea>',
			esc_attr( $key ),
			esc_textarea( $value )
		);
	}

	public function render_field_editor( array $args ): void {
		$key   = sanitize_key( $args['key'] );
		$about = get_option( 'sep_about', [] );
		$value = $about[ $key ] ?? '';
		wp_editor(
			wp_kses_post( $value ),
			'sep_about_' . $key,
			[
				'textarea_name' => 'sep_about[' . $key . ']',
				'textarea_rows' => 6,
				'media_buttons' => false,
				'teeny'         => true,
			]
		);
	}

	public function render_field_media( array $args ): void {
		$key      = sanitize_key( $args['key'] );
		$about    = get_option( 'sep_about', [] );
		$image_id = isset( $about[ $key ] ) ? (int) $about[ $key ] : 0;
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
		?>
		<div class="sep-media-field">
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="" style="max-width:100px;display:block;margin-bottom:8px;">
			<?php endif; ?>
			<input type="hidden" name="sep_about[<?php echo esc_attr( $key ); ?>]"
				id="sep_about_<?php echo esc_attr( $key ); ?>"
				value="<?php echo esc_attr( $image_id ); ?>">
			<button type="button" class="button sep-upload-btn" data-target="sep_about_<?php echo esc_attr( $key ); ?>">
				<?php esc_html_e( 'Select Image', 'se-portfolio' ); ?>
			</button>
		</div>
		<?php
	}

	// -------------------------------------------------------------------------
	// Sanitization
	// -------------------------------------------------------------------------

	/**
	 * Sanitizes the About Me settings array on save.
	 *
	 * @since  1.0.0
	 * @param  array<string, mixed> $input Raw POST input.
	 * @return array<string, mixed>        Sanitized output.
	 */
	public function sanitize_about( array $input ): array {
		return [
			'name'            => sanitize_text_field( $input['name'] ?? '' ),
			'job_title'       => sanitize_text_field( $input['job_title'] ?? '' ),
			'short_bio'       => wp_kses_post( $input['short_bio'] ?? '' ),
			'long_bio'        => wp_kses_post( $input['long_bio'] ?? '' ),
			'location'        => sanitize_text_field( $input['location'] ?? '' ),
			'email'           => sanitize_email( $input['email'] ?? '' ),
			'phone'           => sanitize_text_field( $input['phone'] ?? '' ),
			'github_url'      => esc_url_raw( $input['github_url'] ?? '' ),
			'linkedin_url'    => esc_url_raw( $input['linkedin_url'] ?? '' ),
			'cv_url'          => esc_url_raw( $input['cv_url'] ?? '' ),
			'years_exp'       => absint( $input['years_exp'] ?? 0 ),
			'available'       => isset( $input['available'] ) ? 1 : 0,
			'photo_id'        => absint( $input['photo_id'] ?? 0 ),
			'show_hire_me'    => isset( $input['show_hire_me'] ) ? 1 : 0,
			'show_cv_btn'     => isset( $input['show_cv_btn'] ) ? 1 : 0,
			'show_contact'    => isset( $input['show_contact'] ) ? 1 : 0,
			'contact_heading' => sanitize_text_field( $input['contact_heading'] ?? '' ),
			'contact_intro'   => sanitize_textarea_field( $input['contact_intro'] ?? '' ),
		];
	}

	// -------------------------------------------------------------------------
	// Page Renderers
	// -------------------------------------------------------------------------

	public function render_dashboard(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'se-portfolio' ) );
		}
		include SEP_PLUGIN_DIR . 'admin/partials/about-settings.php';
	}

	// -------------------------------------------------------------------------
	// Asset Enqueueing
	// -------------------------------------------------------------------------

	public function enqueue_assets( string $hook_suffix ): void {
		// Load on our top-level page and on CPT edit screens.
		$is_our_page      = in_array( $hook_suffix, $this->page_hooks, true );
		$is_cpt_edit      = in_array(
			get_current_screen()?->post_type ?? '',
			[ 'sep_project', 'sep_skill', 'sep_experience', 'sep_education', 'sep_certificate' ],
			true
		);

		if ( ! $is_our_page && ! $is_cpt_edit ) {
			return;
		}

		wp_enqueue_style(
			'sep-admin',
			SEP_PLUGIN_URL . 'admin/css/admin.css',
			[],
			SEP_VERSION
		);

		wp_enqueue_media();

		wp_enqueue_script(
			'sep-admin',
			SEP_PLUGIN_URL . 'admin/js/admin.js',
			[ 'jquery', 'media-upload', 'thickbox' ],
			SEP_VERSION,
			true
		);
	}
}
