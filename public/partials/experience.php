<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $experience_query is a WP_Query passed from the shortcode handler.
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="experience">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Experience', 'se-portfolio' ); ?></h2>

		<?php if ( $experience_query->have_posts() ) : ?>
			<div class="sep-timeline">
				<?php while ( $experience_query->have_posts() ) : $experience_query->the_post(); ?>
					<?php
					$post_id         = get_the_ID();
					$company         = get_post_meta( $post_id, '_sep_company', true );
					$company_url     = get_post_meta( $post_id, '_sep_company_url', true );
					$employment_type = get_post_meta( $post_id, '_sep_employment_type', true );
					$start_date      = get_post_meta( $post_id, '_sep_start_date', true );
					$end_date        = get_post_meta( $post_id, '_sep_end_date', true );
					$is_present      = (bool) get_post_meta( $post_id, '_sep_is_present', true );
					$location        = get_post_meta( $post_id, '_sep_location', true );
					$technologies    = get_post_meta( $post_id, '_sep_technologies', true );
					?>
					<div class="sep-timeline-item">
						<div class="sep-timeline-header">
							<h3 class="sep-timeline-title"><?php the_title(); ?></h3>

							<p class="sep-timeline-subtitle">
								<?php if ( $company_url ) : ?>
									<a href="<?php echo esc_url( $company_url ); ?>" target="_blank" rel="noopener noreferrer">
										<?php echo esc_html( $company ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( $company ); ?>
								<?php endif; ?>
								<?php if ( $employment_type ) : ?>
									&mdash;
									<span class="sep-status sep-status-completed" style="vertical-align:middle;">
										<?php echo esc_html( ucfirst( str_replace( '-', ' ', $employment_type ) ) ); ?>
									</span>
								<?php endif; ?>
							</p>

							<p class="sep-timeline-date">
								<?php
								if ( $start_date ) {
									echo esc_html( date_i18n( 'M Y', strtotime( $start_date ) ) );
									echo ' &mdash; ';
									if ( $is_present ) {
										esc_html_e( 'Present', 'se-portfolio' );
									} elseif ( $end_date ) {
										echo esc_html( date_i18n( 'M Y', strtotime( $end_date ) ) );
									}
								}
								if ( $location ) {
									echo ' &nbsp;&bull;&nbsp; ' . esc_html( $location );
								}
								?>
							</p>
						</div>

						<?php if ( get_the_content() ) : ?>
							<div class="sep-timeline-body">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>

						<?php if ( $technologies ) : ?>
							<div class="sep-tags" style="margin-top:12px;">
								<?php
								$tags = array_map( 'trim', explode( ',', $technologies ) );
								foreach ( $tags as $tag ) :
									if ( '' === $tag ) {
										continue;
									}
									?>
									<span class="sep-tag"><?php echo esc_html( $tag ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No experience entries found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
