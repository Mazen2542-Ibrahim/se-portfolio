<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Must be called inside a WP_Query loop with the_post() already called.
$post_id   = get_the_ID();
$issuer    = get_post_meta( $post_id, '_sep_issuer', true );
$issue_dt  = get_post_meta( $post_id, '_sep_issue_date', true );
$expiry_dt = get_post_meta( $post_id, '_sep_expiry_date', true );
$no_expiry = (bool) get_post_meta( $post_id, '_sep_no_expiry', true );
$cred_id   = get_post_meta( $post_id, '_sep_credential_id', true );
$cred_url  = get_post_meta( $post_id, '_sep_credential_url', true );
$ci_id     = (int) get_post_meta( $post_id, '_sep_cert_image', true );
$ci_url    = $ci_id ? wp_get_attachment_image_url( $ci_id, 'medium' ) : get_the_post_thumbnail_url( $post_id, 'medium' );
$c_skills  = get_post_meta( $post_id, '_sep_skills_covered', true );
?>
<article class="sep-card">
	<div class="sep-card-chrome" aria-hidden="true">
		<span class="sep-card-dot"></span>
		<span class="sep-card-dot"></span>
		<span class="sep-card-dot"></span>
		<span class="sep-card-filename"><?php echo esc_html( strtolower( str_replace( ' ', '_', get_the_title() ) ) . '.cert' ); ?></span>
	</div>
	<div class="sep-card-body">
		<?php if ( $ci_url ) : ?>
			<img class="sep-cert-image" src="<?php echo esc_url( $ci_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
		<?php endif; ?>
		<h3 class="sep-card-title"><?php the_title(); ?></h3>
		<p class="sep-card-meta">
			<?php echo esc_html( $issuer ); ?>
			<?php if ( $issue_dt ) : ?>
				&nbsp;&bull;&nbsp; <?php echo esc_html( date_i18n( 'M Y', strtotime( $issue_dt ) ) ); ?>
			<?php endif; ?>
		</p>
		<?php if ( $no_expiry ) : ?>
			<span class="sep-status sep-status-completed"><?php esc_html_e( 'No Expiry', 'se-portfolio' ); ?></span>
		<?php elseif ( $expiry_dt ) : ?>
			<p class="sep-card-meta" style="color:var(--sep-warning);">
				<?php printf( esc_html__( 'Expires: %s', 'se-portfolio' ), esc_html( date_i18n( 'M Y', strtotime( $expiry_dt ) ) ) ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $cred_id ) : ?>
			<p class="sep-card-meta"><?php printf( esc_html__( 'ID: %s', 'se-portfolio' ), esc_html( $cred_id ) ); ?></p>
		<?php endif; ?>
		<?php if ( $c_skills ) : ?>
			<div class="sep-tags">
				<?php foreach ( array_filter( array_map( 'trim', explode( ',', $c_skills ) ) ) as $t ) : ?>
					<span class="sep-tag"><?php echo esc_html( $t ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $cred_url ) : ?>
			<div class="sep-card-actions">
				<a class="sep-card-link" href="<?php echo esc_url( $cred_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Verify Credential', 'se-portfolio' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div><!-- /.sep-card-body -->
</article>
