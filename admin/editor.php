<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Editor related functions
 */

/**
 * Enqueue editor script
 */
function better_a11y_editor_scripts() {
    wp_register_script(
        'better-accessibility-script',
        BETTER_A11Y_URL . 'build/index.js',
        [ 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ],
        BETTER_A11Y_VERSION
    );
    wp_enqueue_script( 'better-accessibility-script' );
    wp_set_script_translations( 'better-accessibility-script', 'better-accessibility', BETTER_A11Y_PATH . '/languages/' );
}
add_action( 'enqueue_block_editor_assets', 'better_a11y_editor_scripts' );