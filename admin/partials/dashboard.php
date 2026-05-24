<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to access this page.', 'se-portfolio' ) );
}

$project_count = wp_count_posts( 'sep_project' )->publish ?? 0;
$skill_count   = wp_count_posts( 'sep_skill' )->publish ?? 0;
$exp_count     = wp_count_posts( 'sep_experience' )->publish ?? 0;
$edu_count     = wp_count_posts( 'sep_education' )->publish ?? 0;
$cert_count    = wp_count_posts( 'sep_certificate' )->publish ?? 0;
?>
<div class="wrap sep-admin-wrap">
	<h1><?php esc_html_e( 'SE Portfolio', 'se-portfolio' ); ?></h1>

	<div class="sep-admin-stats" style="display:flex;gap:16px;flex-wrap:wrap;margin:24px 0;">
		<?php
		$items = [
			__( 'Projects', 'se-portfolio' )     => $project_count,
			__( 'Skills', 'se-portfolio' )       => $skill_count,
			__( 'Experience', 'se-portfolio' )   => $exp_count,
			__( 'Education', 'se-portfolio' )    => $edu_count,
			__( 'Certificates', 'se-portfolio' ) => $cert_count,
		];
		foreach ( $items as $label => $count ) :
			?>
			<div style="background:#1e1e2e;color:#e6edf3;border:1px solid #30363d;border-radius:6px;padding:16px 24px;min-width:120px;text-align:center;">
				<strong style="font-size:1.8rem;display:block;color:#58a6ff;"><?php echo esc_html( $count ); ?></strong>
				<span style="font-size:0.85rem;"><?php echo esc_html( $label ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>

	<h2><?php esc_html_e( 'Available Shortcodes', 'se-portfolio' ); ?></h2>
	<table class="widefat striped" style="max-width:600px;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Shortcode', 'se-portfolio' ); ?></th>
				<th><?php esc_html_e( 'Description', 'se-portfolio' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$shortcodes = [
				'[sep_portfolio]'    => __( 'Full one-page portfolio (all sections)', 'se-portfolio' ),
				'[sep_about]'        => __( 'About Me section', 'se-portfolio' ),
				'[sep_projects]'     => __( 'Projects grid with filter tabs', 'se-portfolio' ),
				'[sep_skills]'       => __( 'Skills grouped by category with progress bars', 'se-portfolio' ),
				'[sep_experience]'   => __( 'Work history timeline', 'se-portfolio' ),
				'[sep_education]'    => __( 'Education timeline', 'se-portfolio' ),
				'[sep_certificates]' => __( 'Certificates card grid', 'se-portfolio' ),
			];
			foreach ( $shortcodes as $code => $desc ) :
				?>
				<tr>
					<td><code><?php echo esc_html( $code ); ?></code></td>
					<td><?php echo esc_html( $desc ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
