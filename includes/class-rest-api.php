<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and handles all SE Portfolio REST API endpoints.
 *
 * Namespace: sep/v1
 * All endpoints return: { "success": true, "data": [], "count": 0 }
 *
 * @since 1.0.0
 */
class SE_Portfolio_Rest_API {

	private const NAMESPACE = 'sep/v1';

	public function register_routes(): void {
		register_rest_route( self::NAMESPACE, '/about', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_about' ],
			'permission_callback' => '__return_true',
		] );

		register_rest_route( self::NAMESPACE, '/projects', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_projects' ],
			'permission_callback' => '__return_true',
			'args'                => $this->pagination_args() + $this->status_arg(),
		] );

		register_rest_route( self::NAMESPACE, '/projects/(?P<id>\d+)', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_project' ],
			'permission_callback' => '__return_true',
		] );

		register_rest_route( self::NAMESPACE, '/skills', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_skills' ],
			'permission_callback' => '__return_true',
			'args'                => $this->pagination_args() + $this->category_arg(),
		] );

		register_rest_route( self::NAMESPACE, '/experience', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_experience' ],
			'permission_callback' => '__return_true',
			'args'                => $this->pagination_args(),
		] );

		register_rest_route( self::NAMESPACE, '/education', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_education' ],
			'permission_callback' => '__return_true',
			'args'                => $this->pagination_args(),
		] );

		register_rest_route( self::NAMESPACE, '/certificates', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_certificates' ],
			'permission_callback' => '__return_true',
			'args'                => $this->pagination_args(),
		] );
	}

	// -------------------------------------------------------------------------
	// Callbacks
	// -------------------------------------------------------------------------

	public function get_about( WP_REST_Request $request ): WP_REST_Response {
		$about = get_option( 'sep_about', [] );
		$data  = [
			'name'         => esc_html( $about['name'] ?? '' ),
			'job_title'    => esc_html( $about['job_title'] ?? '' ),
			'short_bio'    => wp_kses_post( $about['short_bio'] ?? '' ),
			'long_bio'     => wp_kses_post( $about['long_bio'] ?? '' ),
			'location'     => esc_html( $about['location'] ?? '' ),
			'email'        => sanitize_email( $about['email'] ?? '' ),
			'github_url'   => esc_url( $about['github_url'] ?? '' ),
			'linkedin_url' => esc_url( $about['linkedin_url'] ?? '' ),
			'cv_url'       => esc_url( $about['cv_url'] ?? '' ),
			'years_exp'    => (int) ( $about['years_exp'] ?? 0 ),
			'available'    => (bool) ( $about['available'] ?? false ),
			'photo_url'    => isset( $about['photo_id'] ) && $about['photo_id']
				? esc_url( wp_get_attachment_image_url( (int) $about['photo_id'], 'medium' ) )
				: '',
		];
		return rest_ensure_response( $this->wrap( $data ) );
	}

	public function get_projects( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type'      => 'sep_project',
			'post_status'    => 'publish',
			'posts_per_page' => min( 100, (int) $request->get_param( 'limit' ) ?: 10 ),
			'offset'         => (int) $request->get_param( 'offset' ),
			'no_found_rows'  => false,
		];

		$status = sanitize_key( (string) $request->get_param( 'status' ) );
		if ( $status ) {
			$args['meta_query'] = [ [ 'key' => '_sep_status', 'value' => $status ] ]; // phpcs:ignore WordPress.DB.SlowDBQuery
		}

		$query = new WP_Query( $args );
		$items = [];

		foreach ( $query->posts as $post ) {
			$items[] = $this->format_project( $post );
		}

		return rest_ensure_response( $this->wrap( $items, $query->found_posts ) );
	}

	public function get_project( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$post = get_post( (int) $request->get_param( 'id' ) );

		if ( ! $post || 'sep_project' !== $post->post_type || 'publish' !== $post->post_status ) {
			return new WP_Error( 'not_found', __( 'Project not found.', 'se-portfolio' ), [ 'status' => 404 ] );
		}

		return rest_ensure_response( $this->wrap( $this->format_project( $post ) ) );
	}

	public function get_skills( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type'      => 'sep_skill',
			'post_status'    => 'publish',
			'posts_per_page' => min( 100, (int) $request->get_param( 'limit' ) ?: 100 ),
			'offset'         => (int) $request->get_param( 'offset' ),
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		];

		$category = sanitize_key( (string) $request->get_param( 'category' ) );
		if ( $category ) {
			$args['meta_query'] = [ [ 'key' => '_sep_category', 'value' => $category ] ]; // phpcs:ignore WordPress.DB.SlowDBQuery
		}

		$query = new WP_Query( $args );
		$items = [];

		foreach ( $query->posts as $post ) {
			$items[] = [
				'id'          => $post->ID,
				'name'        => esc_html( $post->post_title ),
				'category'    => esc_html( get_post_meta( $post->ID, '_sep_category', true ) ),
				'proficiency' => (int) get_post_meta( $post->ID, '_sep_proficiency', true ),
				'icon'        => esc_html( get_post_meta( $post->ID, '_sep_icon', true ) ),
				'years_exp'   => (int) get_post_meta( $post->ID, '_sep_years_exp', true ),
				'order'       => (int) get_post_meta( $post->ID, '_sep_order', true ),
			];
		}

		return rest_ensure_response( $this->wrap( $items ) );
	}

	public function get_experience( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type'      => 'sep_experience',
			'post_status'    => 'publish',
			'posts_per_page' => min( 100, (int) $request->get_param( 'limit' ) ?: 50 ),
			'offset'         => (int) $request->get_param( 'offset' ),
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		];

		$query = new WP_Query( $args );
		$items = [];

		foreach ( $query->posts as $post ) {
			$items[] = [
				'id'              => $post->ID,
				'job_title'       => esc_html( $post->post_title ),
				'company'         => esc_html( get_post_meta( $post->ID, '_sep_company', true ) ),
				'company_url'     => esc_url( get_post_meta( $post->ID, '_sep_company_url', true ) ),
				'employment_type' => esc_html( get_post_meta( $post->ID, '_sep_employment_type', true ) ),
				'start_date'      => esc_html( get_post_meta( $post->ID, '_sep_start_date', true ) ),
				'end_date'        => esc_html( get_post_meta( $post->ID, '_sep_end_date', true ) ),
				'is_present'      => (bool) get_post_meta( $post->ID, '_sep_is_present', true ),
				'location'        => esc_html( get_post_meta( $post->ID, '_sep_location', true ) ),
				'description'     => wp_kses_post( $post->post_content ),
				'technologies'    => esc_html( get_post_meta( $post->ID, '_sep_technologies', true ) ),
				'order'           => (int) get_post_meta( $post->ID, '_sep_order', true ),
			];
		}

		return rest_ensure_response( $this->wrap( $items ) );
	}

	public function get_education( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type'      => 'sep_education',
			'post_status'    => 'publish',
			'posts_per_page' => min( 100, (int) $request->get_param( 'limit' ) ?: 50 ),
			'offset'         => (int) $request->get_param( 'offset' ),
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		];

		$query = new WP_Query( $args );
		$items = [];

		foreach ( $query->posts as $post ) {
			$items[] = [
				'id'              => $post->ID,
				'degree'          => esc_html( get_post_meta( $post->ID, '_sep_degree', true ) ),
				'field'           => esc_html( get_post_meta( $post->ID, '_sep_field', true ) ),
				'institution'     => esc_html( get_post_meta( $post->ID, '_sep_institution', true ) ),
				'institution_url' => esc_url( get_post_meta( $post->ID, '_sep_institution_url', true ) ),
				'start_year'      => (int) get_post_meta( $post->ID, '_sep_start_year', true ),
				'end_year'        => (int) get_post_meta( $post->ID, '_sep_end_year', true ),
				'in_progress'     => (bool) get_post_meta( $post->ID, '_sep_in_progress', true ),
				'grade'           => esc_html( get_post_meta( $post->ID, '_sep_grade', true ) ),
				'description'     => sanitize_textarea_field( get_post_meta( $post->ID, '_sep_description', true ) ),
				'order'           => (int) get_post_meta( $post->ID, '_sep_order', true ),
			];
		}

		return rest_ensure_response( $this->wrap( $items ) );
	}

	public function get_certificates( WP_REST_Request $request ): WP_REST_Response {
		$args = [
			'post_type'      => 'sep_certificate',
			'post_status'    => 'publish',
			'posts_per_page' => min( 100, (int) $request->get_param( 'limit' ) ?: 50 ),
			'offset'         => (int) $request->get_param( 'offset' ),
			'no_found_rows'  => true,
			'meta_key'       => '_sep_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		];

		$query = new WP_Query( $args );
		$items = [];

		foreach ( $query->posts as $post ) {
			$cert_image_id  = (int) get_post_meta( $post->ID, '_sep_cert_image', true );
			$items[] = [
				'id'             => $post->ID,
				'name'           => esc_html( $post->post_title ),
				'issuer'         => esc_html( get_post_meta( $post->ID, '_sep_issuer', true ) ),
				'issue_date'     => esc_html( get_post_meta( $post->ID, '_sep_issue_date', true ) ),
				'expiry_date'    => esc_html( get_post_meta( $post->ID, '_sep_expiry_date', true ) ),
				'no_expiry'      => (bool) get_post_meta( $post->ID, '_sep_no_expiry', true ),
				'credential_id'  => esc_html( get_post_meta( $post->ID, '_sep_credential_id', true ) ),
				'credential_url' => esc_url( get_post_meta( $post->ID, '_sep_credential_url', true ) ),
				'cert_image_url' => $cert_image_id ? esc_url( wp_get_attachment_image_url( $cert_image_id, 'medium' ) ) : '',
				'skills_covered' => esc_html( get_post_meta( $post->ID, '_sep_skills_covered', true ) ),
				'order'          => (int) get_post_meta( $post->ID, '_sep_order', true ),
			];
		}

		return rest_ensure_response( $this->wrap( $items ) );
	}

	// -------------------------------------------------------------------------
	// Helpers
	// -------------------------------------------------------------------------

	/**
	 * Formats a sep_project post into an array.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	private function format_project( WP_Post $post ): array {
		$thumb_id = get_post_thumbnail_id( $post->ID );
		return [
			'id'            => $post->ID,
			'title'         => esc_html( $post->post_title ),
			'excerpt'       => esc_html( get_the_excerpt( $post ) ),
			'short_desc'    => esc_html( get_post_meta( $post->ID, '_sep_short_desc', true ) ),
			'technologies'  => esc_html( get_post_meta( $post->ID, '_sep_technologies', true ) ),
			'project_url'   => esc_url( get_post_meta( $post->ID, '_sep_project_url', true ) ),
			'github_url'    => esc_url( get_post_meta( $post->ID, '_sep_github_url', true ) ),
			'status'        => esc_html( get_post_meta( $post->ID, '_sep_status', true ) ),
			'start_date'    => esc_html( get_post_meta( $post->ID, '_sep_start_date', true ) ),
			'end_date'      => esc_html( get_post_meta( $post->ID, '_sep_end_date', true ) ),
			'featured'      => (bool) get_post_meta( $post->ID, '_sep_featured', true ),
			'thumbnail_url' => $thumb_id ? esc_url( wp_get_attachment_image_url( $thumb_id, 'medium' ) ) : '',
		];
	}

	/**
	 * Wraps data in the standard response envelope.
	 *
	 * @since  1.0.0
	 * @param  mixed $data  The payload.
	 * @param  int   $count Total count (for paginated lists).
	 * @return array<string, mixed>
	 */
	private function wrap( mixed $data, int $count = 0 ): array {
		$computed_count = is_array( $data ) ? count( $data ) : 1;
		return [
			'success' => true,
			'data'    => $data,
			'count'   => $count > 0 ? $count : $computed_count,
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function pagination_args(): array {
		return [
			'limit'  => [
				'default'           => 10,
				'sanitize_callback' => 'absint',
				'validate_callback' => fn( $v ) => is_numeric( $v ) && $v > 0 && $v <= 100,
			],
			'offset' => [
				'default'           => 0,
				'sanitize_callback' => 'absint',
			],
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function status_arg(): array {
		return [
			'status' => [
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => fn( $v ) => '' === $v || in_array( $v, [ 'completed', 'in-progress', 'archived' ], true ),
			],
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function category_arg(): array {
		return [
			'category' => [
				'default'           => '',
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => fn( $v ) => '' === $v || in_array( $v, [ 'frontend', 'backend', 'devops', 'database', 'tools', 'soft-skills' ], true ),
			],
		];
	}
}
