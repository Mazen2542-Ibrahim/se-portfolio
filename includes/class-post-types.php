<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers all SE Portfolio custom post types.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Post_Types {

	public function register(): void {
		$this->register_projects();
		$this->register_skills();
		$this->register_experience();
		$this->register_education();
		$this->register_certificates();
	}

	private function register_projects(): void {
		$labels = [
			'name'               => __( 'Projects', 'se-portfolio' ),
			'singular_name'      => __( 'Project', 'se-portfolio' ),
			'add_new'            => __( 'Add New', 'se-portfolio' ),
			'add_new_item'       => __( 'Add New Project', 'se-portfolio' ),
			'edit_item'          => __( 'Edit Project', 'se-portfolio' ),
			'new_item'           => __( 'New Project', 'se-portfolio' ),
			'view_item'          => __( 'View Project', 'se-portfolio' ),
			'search_items'       => __( 'Search Projects', 'se-portfolio' ),
			'not_found'          => __( 'No projects found.', 'se-portfolio' ),
			'not_found_in_trash' => __( 'No projects found in Trash.', 'se-portfolio' ),
			'menu_name'          => __( 'Projects', 'se-portfolio' ),
		];

		register_post_type( 'sep_project', [
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => false,
			'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
			'has_archive'  => false,
			'rewrite'      => [ 'slug' => 'sep-projects' ],
			'menu_icon'    => 'dashicons-portfolio',
		] );
	}

	private function register_skills(): void {
		$labels = [
			'name'               => __( 'Skills', 'se-portfolio' ),
			'singular_name'      => __( 'Skill', 'se-portfolio' ),
			'add_new'            => __( 'Add New', 'se-portfolio' ),
			'add_new_item'       => __( 'Add New Skill', 'se-portfolio' ),
			'edit_item'          => __( 'Edit Skill', 'se-portfolio' ),
			'new_item'           => __( 'New Skill', 'se-portfolio' ),
			'not_found'          => __( 'No skills found.', 'se-portfolio' ),
			'not_found_in_trash' => __( 'No skills found in Trash.', 'se-portfolio' ),
			'menu_name'          => __( 'Skills', 'se-portfolio' ),
		];

		register_post_type( 'sep_skill', [
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => false,
			'supports'     => [ 'title' ],
			'has_archive'  => false,
			'rewrite'      => [ 'slug' => 'sep-skills' ],
			'menu_icon'    => 'dashicons-chart-bar',
		] );
	}

	private function register_experience(): void {
		$labels = [
			'name'               => __( 'Experience', 'se-portfolio' ),
			'singular_name'      => __( 'Experience Entry', 'se-portfolio' ),
			'add_new'            => __( 'Add New', 'se-portfolio' ),
			'add_new_item'       => __( 'Add New Experience', 'se-portfolio' ),
			'edit_item'          => __( 'Edit Experience', 'se-portfolio' ),
			'new_item'           => __( 'New Experience Entry', 'se-portfolio' ),
			'not_found'          => __( 'No experience entries found.', 'se-portfolio' ),
			'not_found_in_trash' => __( 'No experience entries found in Trash.', 'se-portfolio' ),
			'menu_name'          => __( 'Experience', 'se-portfolio' ),
		];

		register_post_type( 'sep_experience', [
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => false,
			'supports'     => [ 'title', 'editor' ],
			'has_archive'  => false,
			'rewrite'      => [ 'slug' => 'sep-experience' ],
			'menu_icon'    => 'dashicons-businessman',
		] );
	}

	private function register_education(): void {
		$labels = [
			'name'               => __( 'Education', 'se-portfolio' ),
			'singular_name'      => __( 'Education Entry', 'se-portfolio' ),
			'add_new'            => __( 'Add New', 'se-portfolio' ),
			'add_new_item'       => __( 'Add New Education', 'se-portfolio' ),
			'edit_item'          => __( 'Edit Education', 'se-portfolio' ),
			'new_item'           => __( 'New Education Entry', 'se-portfolio' ),
			'not_found'          => __( 'No education entries found.', 'se-portfolio' ),
			'not_found_in_trash' => __( 'No education entries found in Trash.', 'se-portfolio' ),
			'menu_name'          => __( 'Education', 'se-portfolio' ),
		];

		register_post_type( 'sep_education', [
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => false,
			'supports'     => [ 'title' ],
			'has_archive'  => false,
			'rewrite'      => [ 'slug' => 'sep-education' ],
			'menu_icon'    => 'dashicons-welcome-learn-more',
		] );
	}

	private function register_certificates(): void {
		$labels = [
			'name'               => __( 'Certificates', 'se-portfolio' ),
			'singular_name'      => __( 'Certificate', 'se-portfolio' ),
			'add_new'            => __( 'Add New', 'se-portfolio' ),
			'add_new_item'       => __( 'Add New Certificate', 'se-portfolio' ),
			'edit_item'          => __( 'Edit Certificate', 'se-portfolio' ),
			'new_item'           => __( 'New Certificate', 'se-portfolio' ),
			'not_found'          => __( 'No certificates found.', 'se-portfolio' ),
			'not_found_in_trash' => __( 'No certificates found in Trash.', 'se-portfolio' ),
			'menu_name'          => __( 'Certificates', 'se-portfolio' ),
		];

		register_post_type( 'sep_certificate', [
			'labels'       => $labels,
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => false,
			'supports'     => [ 'title', 'thumbnail' ],
			'has_archive'  => false,
			'rewrite'      => [ 'slug' => 'sep-certificates' ],
			'menu_icon'    => 'dashicons-awards',
		] );
	}
}
