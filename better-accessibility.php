<?php
/**
 * Plugin Name:       Better Accessibility
 * Description:       Adds settings to improve accessibility and automatically corrects accessibility issues.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Marie Comet
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       better-accessibility
 * Domain Path: /languages/
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! defined( 'BETTER_A11Y_VERSION' ) ) {
    define( 'BETTER_A11Y_VERSION', '0.0.1' );
}
if ( ! defined( 'BETTER_A11Y_PATH' ) ) {
    define( 'BETTER_A11Y_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BETTER_A11Y_URL' ) ) {
    define( 'BETTER_A11Y_URL', plugin_dir_url( __FILE__ ) );
}

add_action( 'plugins_loaded', 'better_a11y_include_files' );
function better_a11y_include_files() {
    load_plugin_textdomain( 'better-accessibility', false, basename( dirname( __FILE__ ) ) . '/languages/' );

    if ( is_admin() ) {
        require_once( BETTER_A11Y_PATH . 'admin/menus.php' );
        require_once( BETTER_A11Y_PATH . 'admin/editor.php' );
    } else {
        require_once( BETTER_A11Y_PATH . 'front/menus.php' );
        require_once( BETTER_A11Y_PATH . 'front/editor.php' );
    }
}

function better_a11y_gutenberg_editor_scripts() {
    wp_enqueue_style(
        'better_a11y-gutenberg',
        BETTER_A11Y_URL . 'build/style-index.css',
        BETTER_A11Y_VERSION
    );
}
add_action( 'enqueue_block_editor_assets', 'better_a11y_gutenberg_editor_scripts' );