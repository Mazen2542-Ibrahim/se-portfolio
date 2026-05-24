<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to access this page.', 'se-portfolio' ) );
}
?>
<div class="wrap sep-admin-wrap">
	<h1><?php esc_html_e( 'SE Portfolio — About Me', 'se-portfolio' ); ?></h1>

	<?php settings_errors( 'sep_about' ); ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'sep_about_group' );
		do_settings_sections( 'se-portfolio' );
		submit_button( __( 'Save Changes', 'se-portfolio' ) );
		?>
	</form>
</div>
