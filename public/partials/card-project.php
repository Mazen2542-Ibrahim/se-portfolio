<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Must be called inside a WP_Query loop with the_post() already called.
$post_id  = get_the_ID();
$p_status = get_post_meta( $post_id, '_sep_status', true ) ?: 'in-progress';
$p_url    = get_post_meta( $post_id, '_sep_project_url', true );
$p_github = get_post_meta( $post_id, '_sep_github_url', true );
$p_tech   = get_post_meta( $post_id, '_sep_technologies', true );
$p_thumb  = get_the_post_thumbnail_url( $post_id, 'medium' );
?>
<article class="sep-card sep-project-card" data-status="<?php echo esc_attr( $p_status ); ?>">
	<div class="sep-card-chrome" aria-hidden="true">
		<span class="sep-card-dot"></span>
		<span class="sep-card-dot"></span>
		<span class="sep-card-dot"></span>
		<span class="sep-card-filename"><?php echo esc_html( strtolower( str_replace( ' ', '_', get_the_title() ) ) . '.sh' ); ?></span>
	</div>
	<?php if ( $p_thumb ) : ?>
		<img class="sep-card-thumbnail" src="<?php echo esc_url( $p_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
	<?php endif; ?>
	<div class="sep-card-body">
		<div class="sep-card-header">
			<h3 class="sep-card-title"><?php the_title(); ?></h3>
			<span class="sep-status sep-status-<?php echo esc_attr( $p_status ); ?>">
				<?php echo esc_html( ucfirst( str_replace( '-', ' ', $p_status ) ) ); ?>
			</span>
		</div>
		<p class="sep-card-desc">
			<?php echo esc_html( get_post_meta( $post_id, '_sep_short_desc', true ) ?: get_the_excerpt() ); ?>
		</p>
		<?php if ( $p_tech ) : ?>
			<div class="sep-tags">
				<?php foreach ( array_filter( array_map( 'trim', explode( ',', $p_tech ) ) ) as $t ) : ?>
					<span class="sep-tag"><?php echo esc_html( $t ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="sep-card-actions">
			<?php if ( $p_url ) : ?>
				<a class="sep-card-link" href="<?php echo esc_url( $p_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Live Demo', 'se-portfolio' ); ?></a>
			<?php endif; ?>
			<?php if ( $p_github ) : ?>
				<a class="sep-card-link" href="<?php echo esc_url( $p_github ); ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
			<?php endif; ?>
		</div>
	</div>
</article>
