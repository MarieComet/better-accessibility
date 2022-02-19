<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Menus related functions in admin
 */

 /**
 * Customize nav menus columns 
 * 
 * Remove title attribute field and add aria-label field in nav menu screen settings
 * 
 * @param array Array of column titles keyed by their column name.
 */
add_filter( 'manage_nav-menus_columns', 'better_a11y_manage_nav_menu_screen_settings', 15, 1 );
function better_a11y_manage_nav_menu_screen_settings( $wp_nav_menu_manage_columns ) {
    
    // Remove title attribute from nav menu screen settings
    unset( $wp_nav_menu_manage_columns[ 'title-attribute' ] );

    // Add aria-label checkbox to nav menu screen settings
    $wp_nav_menu_manage_columns[ 'aria-label' ] = __( 'Aria label', 'better-accessibility' );

    return $wp_nav_menu_manage_columns;
}

/**
 * Hide title field attribute in nav menu edit
 * Removing setting in better_a11y_manage_nav_menu_screen_settings is not sufficient as WP hide fields in JS
 */
add_action( 'admin_head', 'better_a11y_nav_menu_edit_css' );
function better_a11y_nav_menu_edit_css() {
    $current_screen = get_current_screen();
    if ( isset( $current_screen->base ) && 'nav-menus' === $current_screen->base ) {
        echo '<style>.field-title-attribute { display: none; }</style>';
    }
}

/**
 * Customize user nav menu columns options
 * 
 * Set user title attributes hidden in nav menu columns
 * 
 * @param array Array of column titles keyed by their column name.
 */
add_filter( 'manage_nav-menus_columns', 'better_a11y_manage_user_nav_menu_settings_screen_options' );
function better_a11y_manage_user_nav_menu_settings_screen_options( $wp_nav_menu_manage_columns ) {

    // Set title attribute to hidden in user options
    $user_id  = wp_get_current_user()->ID;
    $user_options = get_user_option( 'managenav-menuscolumnshidden', $user_id );
    if ( $user_options ) {
        if ( ! in_array( 'title-attribute', $user_options ) ) {
            $user_options[] = 'title-attribute';
            update_user_meta( $user_id, 'managenav-menuscolumnshidden', $user_options );
        }
    }
    return $wp_nav_menu_manage_columns;
}


/**
 * Add aria label field to menu items
 * 
 * @param string        $item_id           Menu item ID as a numeric string.
 * @param WP_Post       $menu_item         Menu item data object.
 * @param int           $depth             Depth of menu item. Used for padding.
 * @param stdClass|null $args              An object of menu item arguments.
 * @param int           $current_object_id Nav menu ID.
 */
add_action( 'wp_nav_menu_item_custom_fields', 'better_a11y_nav_menu_item_custom_fields', 10, 5 );
function better_a11y_nav_menu_item_custom_fields( $item_id, $menu_item, $depth, $args, $current_object_id ) {
    wp_nonce_field( 'menu_item_arialabel_nonce', '_menu_item_arialabel_nonce_name' );
    $menu_item_arialabel = get_post_meta( $item_id, '_menu-item-arialabel', true );
    ?>
    <p class="field-aria-label description description-wide">
        <label for="edit-menu-item-arialabel-<?php echo $item_id; ?>">
            <?php _e( 'Aria label (optional)', 'better-accessibility' ); ?><br />
            <input type="text" id="edit-menu-item-arialabel-<?php echo $item_id; ?>" class="widefat edit-menu-item-arialabel" name="menu-item-arialabel[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $menu_item_arialabel ); ?>" />
            <span class="description"><?php _e( 'The aria label attribute is used to provide a more explicit link label than the Navigation Label, to people using a screen reader. It is not visible on the screen.', 'better-accessibility' ); ?></span>
        </label>
    </p>
    <?php
}

/**
* Save the menu item aria label meta
* 
* @param int   $menu_id         ID of the updated menu.
* @param int   $menu_item_db_id ID of the updated menu item.
* @param array $args            An array of arguments used to update a menu item.
*/
function better_a11y_save_menu_item_meta( $menu_id, $menu_item_db_id, $args ) {
    if ( ! isset( $_POST['_menu_item_arialabel_nonce_name'] ) || ! wp_verify_nonce( $_POST['_menu_item_arialabel_nonce_name'], 'menu_item_arialabel_nonce' ) ) {
        return $menu_id;
    }
    if ( isset( $_POST['menu-item-arialabel'][$menu_item_db_id]  ) ) {
        update_post_meta( $menu_item_db_id, '_menu-item-arialabel', $_POST['menu-item-arialabel'][$menu_item_db_id] );
    }
}
add_action( 'wp_update_nav_menu_item', 'better_a11y_save_menu_item_meta', 10, 3 );