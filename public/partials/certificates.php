<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $certs_query — WP_Query passed from shortcode handler.
// $per_page    — int, cards per page.
// $max_pages   — int, total pages from WP_Query.
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="certificates">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Certificates', 'se-portfolio' ); ?></h2>

		<?php if ( $certs_query->have_posts() ) : ?>
			<div class="sep-cards-grid"
				data-section="certificates"
				data-page="1"
				data-per-page="<?php echo esc_attr( $per_page ); ?>"
				data-max-pages="<?php echo esc_attr( $max_pages ); ?>">
				<?php while ( $certs_query->have_posts() ) : $certs_query->the_post(); ?>
					<?php include __DIR__ . '/card-certificate.php'; ?>
				<?php endwhile; ?>
			</div>

			<?php if ( $max_pages > 1 ) : ?>
				<div class="sep-load-more-wrap">
					<button class="sep-btn sep-btn-outline sep-load-more" data-section="certificates">
						<?php esc_html_e( 'Load More', 'se-portfolio' ); ?>
					</button>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No certificates found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
