<?php
/**
 * SE Portfolio — 404 Error Page
 *
 * Standalone template served by the template_include filter when is_404() is true.
 * Calls wp_head() and wp_footer() so all enqueued assets load correctly, including
 * the inject_custom_styles() CSS-variable block from SE_Portfolio_Public.
 *
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_name = get_bloginfo( 'name' );
$hostname  = strtolower( preg_replace( '/[^a-zA-Z0-9]/', '', $site_name ) ) ?: 'portfolio';
$home_url  = home_url( '/' );
$req_uri   = isset( $_SERVER['REQUEST_URI'] )
	? esc_html( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) )
	: '/';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html__( '404 — Not Found', 'se-portfolio' ) . ' | ' . esc_html( $site_name ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="sep-404-page">

	<div class="sep-portfolio sep-404-portfolio">
		<div class="sep-404-wrap">
			<div class="sep-404-inner">

				<!-- Terminal chrome window -->
				<div class="sep-term-chrome">
					<span class="sep-term-dot sep-term-dot--close" aria-hidden="true"></span>
					<span class="sep-term-dot sep-term-dot--min" aria-hidden="true"></span>
					<span class="sep-term-dot sep-term-dot--max" aria-hidden="true"></span>
					<span class="sep-term-chrome-title">
						<?php echo esc_html( $site_name ); ?> &mdash; 404 error
					</span>
				</div>

				<div class="sep-term-body sep-404-body">

					<!-- Failed request command line -->
					<p class="sep-term-prompt-line">
						<span class="sep-term-ps1">visitor@<?php echo esc_html( $hostname ); ?>:~$</span>
						<span class="sep-term-cmd"> curl <?php echo $req_uri; // Already esc_html'd above. ?></span>
					</p>

					<!-- HTTP response line -->
					<p class="sep-404-http-line">
						HTTP/1.1 <span class="sep-404-status">404 Not Found</span>
					</p>

					<!-- Big 404 number -->
					<div class="sep-404-code" aria-hidden="true">
						<span>4</span><span>0</span><span>4</span>
					</div>

					<h1 class="sep-404-title"><?php esc_html_e( 'Page Not Found', 'se-portfolio' ); ?></h1>
					<p class="sep-404-desc">
						<?php esc_html_e( "The path you're looking for doesn't exist on this server. It may have been moved, deleted, or never existed.", 'se-portfolio' ); ?>
					</p>

					<!-- Suggestions prompt -->
					<p class="sep-term-prompt-line">
						<span class="sep-term-ps1">visitor@<?php echo esc_html( $hostname ); ?>:~$</span>
						<span class="sep-term-cmd"> ls ~/suggestions/</span>
					</p>
					<ul class="sep-404-suggestions">
						<li>
							<span class="sep-prompt-arrow" aria-hidden="true">→</span>
							<a href="<?php echo esc_url( $home_url ); ?>">home</a>
						</li>
						<li>
							<span class="sep-prompt-arrow" aria-hidden="true">→</span>
							<a href="javascript:history.back()">previous-page</a>
						</li>
					</ul>

					<!-- Action buttons -->
					<div class="sep-404-actions">
						<a href="<?php echo esc_url( $home_url ); ?>" class="sep-btn sep-btn-primary">
							&#8629; <?php esc_html_e( 'Go Home', 'se-portfolio' ); ?>
						</a>
						<button type="button" onclick="history.back()" class="sep-btn sep-btn-outline">
							&larr; <?php esc_html_e( 'Go Back', 'se-portfolio' ); ?>
						</button>
					</div>

					<!-- Blinking cursor -->
					<p class="sep-term-prompt-line sep-404-cursor-line">
						<span class="sep-term-ps1">visitor@<?php echo esc_html( $hostname ); ?>:~$</span>
						<span class="sep-blink" aria-hidden="true"></span>
					</p>

				</div><!-- /.sep-term-body -->
			</div><!-- /.sep-404-inner -->
		</div><!-- /.sep-404-wrap -->
	</div><!-- /.sep-portfolio -->

	<?php wp_footer(); ?>
</body>
</html>
