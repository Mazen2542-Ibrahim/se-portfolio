<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $projects_query is a WP_Query passed from the shortcode handler.
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="projects">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></h2>

		<?php if ( $projects_query->have_posts() ) : ?>

			<div class="sep-filter-tabs">
				<button class="sep-filter-tab is-active" data-filter="all"><?php esc_html_e( 'All', 'se-portfolio' ); ?></button>
				<button class="sep-filter-tab" data-filter="completed"><?php esc_html_e( 'Completed', 'se-portfolio' ); ?></button>
				<button class="sep-filter-tab" data-filter="in-progress"><?php esc_html_e( 'In Progress', 'se-portfolio' ); ?></button>
				<button class="sep-filter-tab" data-filter="archived"><?php esc_html_e( 'Archived', 'se-portfolio' ); ?></button>
			</div>

			<div class="sep-cards-grid">
				<?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
					<?php
					$post_id      = get_the_ID();
					$status       = esc_attr( get_post_meta( $post_id, '_sep_status', true ) ?: 'in-progress' );
					$project_url  = get_post_meta( $post_id, '_sep_project_url', true );
					$github_url   = get_post_meta( $post_id, '_sep_github_url', true );
					$technologies = get_post_meta( $post_id, '_sep_technologies', true );
					$thumb_url    = get_the_post_thumbnail_url( $post_id, 'medium' );
					$featured     = (bool) get_post_meta( $post_id, '_sep_featured', true );
					?>
					<article
						class="sep-card sep-project-card"
						data-status="<?php echo esc_attr( $status ); ?>"
					>
						<?php if ( $thumb_url ) : ?>
							<img
								class="sep-card-thumbnail"
								src="<?php echo esc_url( $thumb_url ); ?>"
								alt="<?php the_title_attribute(); ?>"
								loading="lazy"
							>
						<?php endif; ?>

						<div class="sep-card-header">
							<h3 class="sep-card-title"><?php the_title(); ?></h3>
							<span class="sep-status sep-status-<?php echo esc_attr( $status ); ?>">
								<?php echo esc_html( ucfirst( str_replace( '-', ' ', $status ) ) ); ?>
							</span>
							<?php if ( $featured ) : ?>
								<span class="sep-status sep-status-completed" style="margin-left:4px;">
									<?php esc_html_e( 'Featured', 'se-portfolio' ); ?>
								</span>
							<?php endif; ?>
						</div>

						<p class="sep-card-desc">
							<?php echo esc_html( get_post_meta( $post_id, '_sep_short_desc', true ) ?: get_the_excerpt() ); ?>
						</p>

						<?php if ( $technologies ) : ?>
							<div class="sep-tags">
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

						<div class="sep-card-actions" style="margin-top:16px;">
							<?php if ( $project_url ) : ?>
								<a class="sep-card-link" href="<?php echo esc_url( $project_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Live Demo', 'se-portfolio' ); ?>
								</a>
							<?php endif; ?>
							<?php if ( $github_url ) : ?>
								<a class="sep-card-link" href="<?php echo esc_url( $github_url ); ?>" target="_blank" rel="noopener noreferrer">
									GitHub
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No projects found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
