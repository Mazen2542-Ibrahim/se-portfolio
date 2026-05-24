<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and handles meta boxes for all SE Portfolio CPTs.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Meta_Boxes {

	/** @var string[] Valid project status values. */
	private const PROJECT_STATUSES = [ 'in-progress', 'completed', 'archived' ];

	/** @var string[] Valid skill category values. */
	private const SKILL_CATEGORIES = [ 'frontend', 'backend', 'devops', 'database', 'tools', 'soft-skills' ];

	/** @var string[] Valid employment type values. */
	private const EMPLOYMENT_TYPES = [ 'full-time', 'part-time', 'contract', 'freelance', 'internship' ];

	public function register(): void {
		add_meta_box(
			'sep_project_details',
			__( 'Project Details', 'se-portfolio' ),
			[ $this, 'render_project_meta_box' ],
			'sep_project',
			'normal',
			'high'
		);

		add_meta_box(
			'sep_skill_details',
			__( 'Skill Details', 'se-portfolio' ),
			[ $this, 'render_skill_meta_box' ],
			'sep_skill',
			'normal',
			'high'
		);

		add_meta_box(
			'sep_experience_details',
			__( 'Experience Details', 'se-portfolio' ),
			[ $this, 'render_experience_meta_box' ],
			'sep_experience',
			'normal',
			'high'
		);

		add_meta_box(
			'sep_education_details',
			__( 'Education Details', 'se-portfolio' ),
			[ $this, 'render_education_meta_box' ],
			'sep_education',
			'normal',
			'high'
		);

		add_meta_box(
			'sep_certificate_details',
			__( 'Certificate Details', 'se-portfolio' ),
			[ $this, 'render_certificate_meta_box' ],
			'sep_certificate',
			'normal',
			'high'
		);
	}

	// -------------------------------------------------------------------------
	// Render Methods
	// -------------------------------------------------------------------------

	public function render_project_meta_box( WP_Post $post ): void {
		wp_nonce_field( 'sep_save_project_' . $post->ID, 'sep_project_nonce' );
		$m = $this->get_project_meta( $post->ID );
		include SEP_PLUGIN_DIR . 'admin/partials/meta-box-project.php';
	}

	public function render_skill_meta_box( WP_Post $post ): void {
		wp_nonce_field( 'sep_save_skill_' . $post->ID, 'sep_skill_nonce' );
		$m = $this->get_skill_meta( $post->ID );
		include SEP_PLUGIN_DIR . 'admin/partials/meta-box-skill.php';
	}

	public function render_experience_meta_box( WP_Post $post ): void {
		wp_nonce_field( 'sep_save_experience_' . $post->ID, 'sep_experience_nonce' );
		$m = $this->get_experience_meta( $post->ID );
		include SEP_PLUGIN_DIR . 'admin/partials/meta-box-experience.php';
	}

	public function render_education_meta_box( WP_Post $post ): void {
		wp_nonce_field( 'sep_save_education_' . $post->ID, 'sep_education_nonce' );
		$m = $this->get_education_meta( $post->ID );
		include SEP_PLUGIN_DIR . 'admin/partials/meta-box-education.php';
	}

	public function render_certificate_meta_box( WP_Post $post ): void {
		wp_nonce_field( 'sep_save_certificate_' . $post->ID, 'sep_certificate_nonce' );
		$m = $this->get_certificate_meta( $post->ID );
		include SEP_PLUGIN_DIR . 'admin/partials/meta-box-certificate.php';
	}

	// -------------------------------------------------------------------------
	// Meta Getters
	// -------------------------------------------------------------------------

	/**
	 * @return array<string, mixed>
	 */
	private function get_project_meta( int $post_id ): array {
		return [
			'short_desc'  => get_post_meta( $post_id, '_sep_short_desc', true ),
			'technologies' => get_post_meta( $post_id, '_sep_technologies', true ),
			'project_url' => get_post_meta( $post_id, '_sep_project_url', true ),
			'github_url'  => get_post_meta( $post_id, '_sep_github_url', true ),
			'status'      => get_post_meta( $post_id, '_sep_status', true ) ?: 'in-progress',
			'start_date'  => get_post_meta( $post_id, '_sep_start_date', true ),
			'end_date'    => get_post_meta( $post_id, '_sep_end_date', true ),
			'featured'    => (bool) get_post_meta( $post_id, '_sep_featured', true ),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function get_skill_meta( int $post_id ): array {
		return [
			'category'  => get_post_meta( $post_id, '_sep_category', true ),
			'proficiency' => (int) get_post_meta( $post_id, '_sep_proficiency', true ),
			'icon'      => get_post_meta( $post_id, '_sep_icon', true ),
			'years_exp' => (int) get_post_meta( $post_id, '_sep_years_exp', true ),
			'order'     => (int) get_post_meta( $post_id, '_sep_order', true ),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function get_experience_meta( int $post_id ): array {
		return [
			'company'         => get_post_meta( $post_id, '_sep_company', true ),
			'company_url'     => get_post_meta( $post_id, '_sep_company_url', true ),
			'employment_type' => get_post_meta( $post_id, '_sep_employment_type', true ) ?: 'full-time',
			'start_date'      => get_post_meta( $post_id, '_sep_start_date', true ),
			'end_date'        => get_post_meta( $post_id, '_sep_end_date', true ),
			'is_present'      => (bool) get_post_meta( $post_id, '_sep_is_present', true ),
			'location'        => get_post_meta( $post_id, '_sep_location', true ),
			'technologies'    => get_post_meta( $post_id, '_sep_technologies', true ),
			'order'           => (int) get_post_meta( $post_id, '_sep_order', true ),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function get_education_meta( int $post_id ): array {
		return [
			'degree'          => get_post_meta( $post_id, '_sep_degree', true ),
			'field'           => get_post_meta( $post_id, '_sep_field', true ),
			'institution'     => get_post_meta( $post_id, '_sep_institution', true ),
			'institution_url' => get_post_meta( $post_id, '_sep_institution_url', true ),
			'start_year'      => (int) get_post_meta( $post_id, '_sep_start_year', true ),
			'end_year'        => (int) get_post_meta( $post_id, '_sep_end_year', true ),
			'in_progress'     => (bool) get_post_meta( $post_id, '_sep_in_progress', true ),
			'grade'           => get_post_meta( $post_id, '_sep_grade', true ),
			'description'     => get_post_meta( $post_id, '_sep_description', true ),
			'order'           => (int) get_post_meta( $post_id, '_sep_order', true ),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function get_certificate_meta( int $post_id ): array {
		return [
			'issuer'         => get_post_meta( $post_id, '_sep_issuer', true ),
			'issue_date'     => get_post_meta( $post_id, '_sep_issue_date', true ),
			'expiry_date'    => get_post_meta( $post_id, '_sep_expiry_date', true ),
			'no_expiry'      => (bool) get_post_meta( $post_id, '_sep_no_expiry', true ),
			'credential_id'  => get_post_meta( $post_id, '_sep_credential_id', true ),
			'credential_url' => get_post_meta( $post_id, '_sep_credential_url', true ),
			'cert_image'     => (int) get_post_meta( $post_id, '_sep_cert_image', true ),
			'skills_covered' => get_post_meta( $post_id, '_sep_skills_covered', true ),
			'order'          => (int) get_post_meta( $post_id, '_sep_order', true ),
		];
	}

	// -------------------------------------------------------------------------
	// Save Handlers
	// -------------------------------------------------------------------------

	public function save_project( int $post_id ): void {
		if ( ! $this->can_save( $post_id, 'sep_project_nonce', 'sep_save_project_' . $post_id ) ) {
			return;
		}

		$status_allowlist = self::PROJECT_STATUSES;

		$raw_status = sanitize_key( $_POST['sep_status'] ?? '' );

		update_post_meta( $post_id, '_sep_short_desc',   sanitize_textarea_field( $_POST['sep_short_desc'] ?? '' ) );
		update_post_meta( $post_id, '_sep_technologies', sanitize_text_field( $_POST['sep_technologies'] ?? '' ) );
		update_post_meta( $post_id, '_sep_project_url',  esc_url_raw( $_POST['sep_project_url'] ?? '' ) );
		update_post_meta( $post_id, '_sep_github_url',   esc_url_raw( $_POST['sep_github_url'] ?? '' ) );
		update_post_meta( $post_id, '_sep_status',       in_array( $raw_status, $status_allowlist, true ) ? $raw_status : 'in-progress' );
		update_post_meta( $post_id, '_sep_start_date',   $this->sanitize_date( $_POST['sep_start_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_end_date',     $this->sanitize_date( $_POST['sep_end_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_featured',     isset( $_POST['sep_featured'] ) ? 1 : 0 );
	}

	public function save_skill( int $post_id ): void {
		if ( ! $this->can_save( $post_id, 'sep_skill_nonce', 'sep_save_skill_' . $post_id ) ) {
			return;
		}

		$raw_category = sanitize_key( $_POST['sep_category'] ?? '' );
		$proficiency  = min( 100, max( 0, absint( $_POST['sep_proficiency'] ?? 0 ) ) );

		update_post_meta( $post_id, '_sep_category',   in_array( $raw_category, self::SKILL_CATEGORIES, true ) ? $raw_category : 'tools' );
		update_post_meta( $post_id, '_sep_proficiency', $proficiency );
		update_post_meta( $post_id, '_sep_icon',        sanitize_text_field( $_POST['sep_icon'] ?? '' ) );
		update_post_meta( $post_id, '_sep_years_exp',   absint( $_POST['sep_years_exp'] ?? 0 ) );
		update_post_meta( $post_id, '_sep_order',       absint( $_POST['sep_order'] ?? 0 ) );
	}

	public function save_experience( int $post_id ): void {
		if ( ! $this->can_save( $post_id, 'sep_experience_nonce', 'sep_save_experience_' . $post_id ) ) {
			return;
		}

		$raw_type = sanitize_key( $_POST['sep_employment_type'] ?? '' );

		update_post_meta( $post_id, '_sep_company',         sanitize_text_field( $_POST['sep_company'] ?? '' ) );
		update_post_meta( $post_id, '_sep_company_url',     esc_url_raw( $_POST['sep_company_url'] ?? '' ) );
		update_post_meta( $post_id, '_sep_employment_type', in_array( $raw_type, self::EMPLOYMENT_TYPES, true ) ? $raw_type : 'full-time' );
		update_post_meta( $post_id, '_sep_start_date',      $this->sanitize_date( $_POST['sep_start_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_end_date',        $this->sanitize_date( $_POST['sep_end_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_is_present',      isset( $_POST['sep_is_present'] ) ? 1 : 0 );
		update_post_meta( $post_id, '_sep_location',        sanitize_text_field( $_POST['sep_location'] ?? '' ) );
		update_post_meta( $post_id, '_sep_technologies',    sanitize_text_field( $_POST['sep_technologies'] ?? '' ) );
		update_post_meta( $post_id, '_sep_order',           absint( $_POST['sep_order'] ?? 0 ) );
	}

	public function save_education( int $post_id ): void {
		if ( ! $this->can_save( $post_id, 'sep_education_nonce', 'sep_save_education_' . $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, '_sep_degree',          sanitize_text_field( $_POST['sep_degree'] ?? '' ) );
		update_post_meta( $post_id, '_sep_field',           sanitize_text_field( $_POST['sep_field'] ?? '' ) );
		update_post_meta( $post_id, '_sep_institution',     sanitize_text_field( $_POST['sep_institution'] ?? '' ) );
		update_post_meta( $post_id, '_sep_institution_url', esc_url_raw( $_POST['sep_institution_url'] ?? '' ) );
		update_post_meta( $post_id, '_sep_start_year',      absint( $_POST['sep_start_year'] ?? 0 ) );
		update_post_meta( $post_id, '_sep_end_year',        absint( $_POST['sep_end_year'] ?? 0 ) );
		update_post_meta( $post_id, '_sep_in_progress',     isset( $_POST['sep_in_progress'] ) ? 1 : 0 );
		update_post_meta( $post_id, '_sep_grade',           sanitize_text_field( $_POST['sep_grade'] ?? '' ) );
		update_post_meta( $post_id, '_sep_description',     sanitize_textarea_field( $_POST['sep_description'] ?? '' ) );
		update_post_meta( $post_id, '_sep_order',           absint( $_POST['sep_order'] ?? 0 ) );
	}

	public function save_certificate( int $post_id ): void {
		if ( ! $this->can_save( $post_id, 'sep_certificate_nonce', 'sep_save_certificate_' . $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, '_sep_issuer',         sanitize_text_field( $_POST['sep_issuer'] ?? '' ) );
		update_post_meta( $post_id, '_sep_issue_date',     $this->sanitize_date( $_POST['sep_issue_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_expiry_date',    $this->sanitize_date( $_POST['sep_expiry_date'] ?? '' ) );
		update_post_meta( $post_id, '_sep_no_expiry',      isset( $_POST['sep_no_expiry'] ) ? 1 : 0 );
		update_post_meta( $post_id, '_sep_credential_id',  sanitize_text_field( $_POST['sep_credential_id'] ?? '' ) );
		update_post_meta( $post_id, '_sep_credential_url', esc_url_raw( $_POST['sep_credential_url'] ?? '' ) );
		update_post_meta( $post_id, '_sep_cert_image',     absint( $_POST['sep_cert_image'] ?? 0 ) );
		update_post_meta( $post_id, '_sep_skills_covered', sanitize_text_field( $_POST['sep_skills_covered'] ?? '' ) );
		update_post_meta( $post_id, '_sep_order',          absint( $_POST['sep_order'] ?? 0 ) );
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Verifies nonce and capability before saving.
	 *
	 * @since 1.0.0
	 */
	private function can_save( int $post_id, string $nonce_field, string $nonce_action ): bool {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ?? '' ) );
		if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sanitizes a date string; returns empty string if format is invalid.
	 *
	 * @since 1.0.0
	 */
	private function sanitize_date( string $value ): string {
		$value = sanitize_text_field( $value );
		if ( '' === $value ) {
			return '';
		}
		// Accept YYYY-MM-DD only.
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			return '';
		}
		return $value;
	}
}
