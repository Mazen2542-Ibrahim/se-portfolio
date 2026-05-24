<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles plugin deactivation.
 *
 * @since 1.0.0
 */
class SE_Portfolio_Deactivator {

	public static function deactivate(): void {
		flush_rewrite_rules();
	}
}
