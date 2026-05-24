<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $about is passed from the shortcode handler via class-public.php.

$photo_id  = isset( $about['photo_id'] ) ? (int) $about['photo_id'] : 0;
$photo_url = $photo_id ? wp_get_attachment_image_url( $photo_id, 'medium' ) : '';
?>
<div class="sep-portfolio">
	<section class="sep-section" data-sep-section="about">
		<h2 class="sep-section-heading"><?php esc_html_e( 'About Me', 'se-portfolio' ); ?></h2>

		<div class="sep-about-inner">
			<?php if ( $photo_url ) : ?>
				<div>
					<img
						class="sep-about-photo"
						src="<?php echo esc_url( $photo_url ); ?>"
						alt="<?php echo esc_attr( $about['name'] ?? '' ); ?>"
					>
				</div>
			<?php endif; ?>

			<div>
				<?php if ( ! empty( $about['long_bio'] ) ) : ?>
					<div class="sep-about-bio">
						<?php echo wp_kses_post( $about['long_bio'] ); ?>
					</div>
				<?php elseif ( ! empty( $about['short_bio'] ) ) : ?>
					<div class="sep-about-bio">
						<?php echo wp_kses_post( $about['short_bio'] ); ?>
					</div>
				<?php endif; ?>

				<?php
				$years_exp    = isset( $about['years_exp'] ) ? (int) $about['years_exp'] : 0;
				$project_count = wp_count_posts( 'sep_project' )->publish ?? 0;
				$skill_count   = wp_count_posts( 'sep_skill' )->publish ?? 0;
				?>
				<div class="sep-stats">
					<?php if ( $years_exp ) : ?>
						<div class="sep-stat">
							<span class="sep-stat-number"><?php echo esc_html( $years_exp ); ?>+</span>
							<span class="sep-stat-label"><?php esc_html_e( 'Years Experience', 'se-portfolio' ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $project_count ) : ?>
						<div class="sep-stat">
							<span class="sep-stat-number"><?php echo esc_html( $project_count ); ?></span>
							<span class="sep-stat-label"><?php esc_html_e( 'Projects', 'se-portfolio' ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $skill_count ) : ?>
						<div class="sep-stat">
							<span class="sep-stat-number"><?php echo esc_html( $skill_count ); ?></span>
							<span class="sep-stat-label"><?php esc_html_e( 'Skills', 'se-portfolio' ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="sep-about-links">
					<?php if ( ! empty( $about['github_url'] ) ) : ?>
						<a class="sep-btn sep-btn-outline" href="<?php echo esc_url( $about['github_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							GitHub
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $about['linkedin_url'] ) ) : ?>
						<a class="sep-btn sep-btn-outline" href="<?php echo esc_url( $about['linkedin_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							LinkedIn
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $about['cv_url'] ) ) : ?>
						<a class="sep-btn sep-btn-primary" href="<?php echo esc_url( $about['cv_url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Download CV', 'se-portfolio' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $about['email'] ) ) : ?>
						<a class="sep-btn sep-btn-outline" href="mailto:<?php echo esc_attr( $about['email'] ); ?>">
							<?php esc_html_e( 'Email Me', 'se-portfolio' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>
