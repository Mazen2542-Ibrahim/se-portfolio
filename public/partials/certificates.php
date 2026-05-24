<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $certs_query is a WP_Query passed from the shortcode handler.
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="certificates">
		<h2 class="sep-section-heading"><?php esc_html_e( 'Certificates', 'se-portfolio' ); ?></h2>

		<?php if ( $certs_query->have_posts() ) : ?>
			<div class="sep-cards-grid">
				<?php while ( $certs_query->have_posts() ) : $certs_query->the_post(); ?>
					<?php
					$post_id        = get_the_ID();
					$issuer         = get_post_meta( $post_id, '_sep_issuer', true );
					$issue_date     = get_post_meta( $post_id, '_sep_issue_date', true );
					$expiry_date    = get_post_meta( $post_id, '_sep_expiry_date', true );
					$no_expiry      = (bool) get_post_meta( $post_id, '_sep_no_expiry', true );
					$credential_id  = get_post_meta( $post_id, '_sep_credential_id', true );
					$credential_url = get_post_meta( $post_id, '_sep_credential_url', true );
					$cert_image_id  = (int) get_post_meta( $post_id, '_sep_cert_image', true );
					$cert_image_url = $cert_image_id ? wp_get_attachment_image_url( $cert_image_id, 'medium' ) : get_the_post_thumbnail_url( $post_id, 'medium' );
					$skills_covered = get_post_meta( $post_id, '_sep_skills_covered', true );
					?>
					<article class="sep-card">
						<?php if ( $cert_image_url ) : ?>
							<img
								class="sep-cert-image"
								src="<?php echo esc_url( $cert_image_url ); ?>"
								alt="<?php the_title_attribute(); ?>"
								loading="lazy"
							>
						<?php endif; ?>

						<h3 class="sep-card-title"><?php the_title(); ?></h3>

						<p class="sep-card-meta">
							<?php echo esc_html( $issuer ); ?>
							<?php if ( $issue_date ) : ?>
								&nbsp;&bull;&nbsp;
								<?php echo esc_html( date_i18n( 'M Y', strtotime( $issue_date ) ) ); ?>
							<?php endif; ?>
						</p>

						<?php if ( ! $no_expiry && $expiry_date ) : ?>
							<p class="sep-card-meta" style="color:var(--sep-warning);">
								<?php
								printf(
									/* translators: %s: expiry date */
									esc_html__( 'Expires: %s', 'se-portfolio' ),
									esc_html( date_i18n( 'M Y', strtotime( $expiry_date ) ) )
								);
								?>
							</p>
						<?php elseif ( $no_expiry ) : ?>
							<span class="sep-status sep-status-completed"><?php esc_html_e( 'No Expiry', 'se-portfolio' ); ?></span>
						<?php endif; ?>

						<?php if ( $credential_id ) : ?>
							<p class="sep-card-meta">
								<?php
								printf(
									/* translators: %s: credential ID */
									esc_html__( 'ID: %s', 'se-portfolio' ),
									esc_html( $credential_id )
								);
								?>
							</p>
						<?php endif; ?>

						<?php if ( $skills_covered ) : ?>
							<div class="sep-tags">
								<?php
								$tags = array_map( 'trim', explode( ',', $skills_covered ) );
								foreach ( $tags as $tag ) :
									if ( '' === $tag ) {
										continue;
									}
									?>
									<span class="sep-tag"><?php echo esc_html( $tag ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( $credential_url ) : ?>
							<div class="sep-card-actions" style="margin-top:16px;">
								<a class="sep-card-link" href="<?php echo esc_url( $credential_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Verify Credential', 'se-portfolio' ); ?>
								</a>
							</div>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="sep-empty"><?php esc_html_e( 'No certificates found.', 'se-portfolio' ); ?></p>
		<?php endif; ?>
	</section>
</div>
