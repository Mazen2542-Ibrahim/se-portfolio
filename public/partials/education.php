<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $education_query is a WP_Query passed from the shortcode handler.
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="education">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Education', 'se-portfolio' ); ?></h2>

		<?php if ( $education_query->have_posts() ) : ?>
			<div class="sep-timeline">
				<?php while ( $education_query->have_posts() ) : $education_query->the_post(); ?>
					<?php
					$post_id         = get_the_ID();
					$degree          = get_post_meta( $post_id, '_sep_degree', true );
					$field           = get_post_meta( $post_id, '_sep_field', true );
					$institution     = get_post_meta( $post_id, '_sep_institution', true );
					$institution_url = get_post_meta( $post_id, '_sep_institution_url', true );
					$start_year      = (int) get_post_meta( $post_id, '_sep_start_year', true );
					$end_year        = (int) get_post_meta( $post_id, '_sep_end_year', true );
					$in_progress     = (bool) get_post_meta( $post_id, '_sep_in_progress', true );
					$grade           = get_post_meta( $post_id, '_sep_grade', true );
					$description     = get_post_meta( $post_id, '_sep_description', true );
					?>
					<div class="sep-timeline-item">
						<div class="sep-timeline-header">
							<h3 class="sep-timeline-title">
								<?php echo esc_html( $degree ); ?>
								<?php if ( $field ) : ?>
									<span style="color:var(--sep-muted);font-size:0.9em;"><?php echo ' — ' . esc_html( $field ); ?></span>
								<?php endif; ?>
							</h3>

							<p class="sep-timeline-subtitle">
								<?php if ( $institution_url ) : ?>
									<a href="<?php echo esc_url( $institution_url ); ?>" target="_blank" rel="noopener noreferrer">
										<?php echo esc_html( $institution ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( $institution ); ?>
								<?php endif; ?>
							</p>

							<p class="sep-timeline-date">
								<?php
								if ( $start_year ) {
									echo esc_html( $start_year );
									echo ' &mdash; ';
									if ( $in_progress ) {
										esc_html_e( 'Present', 'se-portfolio' );
									} elseif ( $end_year ) {
										echo esc_html( $end_year );
									}
								}
								if ( $grade ) {
									echo ' &nbsp;&bull;&nbsp; ' . esc_html( $grade );
								}
								?>
							</p>
						</div>

						<?php if ( $description ) : ?>
							<div class="sep-timeline-body">
								<?php echo esc_html( $description ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No education entries found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
