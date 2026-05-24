<?php
/**
 * Plugin Name:  SE Portfolio
 * Plugin URI:   https://github.com/Mazen2542-Ibrahim/se-portfolio
 * Description:  A production-ready portfolio plugin for Support Engineers and Programmers with a dark, terminal-inspired design.
 * Version:      1.0.4
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author:       Mazen Alghadouni
 * License:      GPL v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  se-portfolio
 * Domain Path:  /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEP_VERSION',     '1.0.4' );
define( 'SEP_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'SEP_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'SEP_PLUGIN_FILE', __FILE__ );

require_once SEP_PLUGIN_DIR . 'includes/class-se-portfolio.php';

register_activation_hook( __FILE__,   [ 'SE_Portfolio', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'SE_Portfolio', 'deactivate' ] );

SE_Portfolio::get_instance();
