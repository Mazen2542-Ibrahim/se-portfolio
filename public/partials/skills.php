<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $skills_query is a WP_Query passed from the shortcode handler.

$grouped = [];
if ( $skills_query->have_posts() ) {
	while ( $skills_query->have_posts() ) {
		$skills_query->the_post();
		$cat = get_post_meta( get_the_ID(), '_sep_category', true ) ?: 'tools';
		$grouped[ $cat ][] = get_the_ID();
	}
}

$category_labels = [
	'frontend'   => __( 'Frontend', 'se-portfolio' ),
	'backend'    => __( 'Backend', 'se-portfolio' ),
	'devops'     => __( 'DevOps', 'se-portfolio' ),
	'database'   => __( 'Database', 'se-portfolio' ),
	'tools'      => __( 'Tools', 'se-portfolio' ),
	'soft-skills' => __( 'Soft Skills', 'se-portfolio' ),
];
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="skills">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></h2>

		<?php if ( $grouped ) : ?>
			<?php foreach ( $grouped as $cat => $post_ids ) : ?>
				<div class="sep-skills-group">
					<div class="sep-skills-group-label">
						<?php echo esc_html( $category_labels[ $cat ] ?? ucfirst( $cat ) ); ?>
					</div>

					<?php foreach ( $post_ids as $pid ) : ?>
						<?php
						$proficiency = min( 100, max( 0, (int) get_post_meta( $pid, '_sep_proficiency', true ) ) );
						$years_exp   = (int) get_post_meta( $pid, '_sep_years_exp', true );
						$name        = get_the_title( $pid );
						?>
						<div class="sep-skill-item">
							<div class="sep-skill-header">
								<span class="sep-skill-name"><?php echo esc_html( $name ); ?></span>
								<span class="sep-skill-pct">
									<?php
									echo esc_html( $proficiency . '%' );
									if ( $years_exp ) {
										echo esc_html( ' · ' . $years_exp . ' ' . _n( 'yr', 'yrs', $years_exp, 'se-portfolio' ) );
									}
									?>
								</span>
							</div>
							<div class="sep-skill-track">
								<div
									class="sep-skill-bar"
									data-proficiency="<?php echo esc_attr( $proficiency ); ?>"
									role="progressbar"
									aria-valuenow="<?php echo esc_attr( $proficiency ); ?>"
									aria-valuemin="0"
									aria-valuemax="100"
									aria-label="<?php echo esc_attr( $name ); ?>"
								></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No skills found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
