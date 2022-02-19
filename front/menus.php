<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Menus related functions
 */

/**
 * Filters a menu item's starting output.
 * 
 * Replace empty link (href="#") with span
 * 
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $menu_item   Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 */
add_filter( 'walker_nav_menu_start_el', 'better_a11y_replace_empty_menu_links', 10, 4 );
function better_a11y_replace_empty_menu_links( $item_output, $item, $depth, $args ) {
    if ( $item->url === '#' ) {
		$item_output = str_replace( [ '<a', 'href="#"', '</a>' ], [ '<span', '', '</span>' ], $item_output );
	}
    return $item_output;
}

/**
 * Filters nav menu item attributes
 * 
 * Add aria-label attribute
 * 
 * @param array $atts {
 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
 *
 *     @type string $title        Title attribute.
 *     @type string $target       Target attribute.
 *     @type string $rel          The rel attribute.
 *     @type string $href         The href attribute.
 *     @type string $aria-current The aria-current attribute.
 * }
 * @param WP_Post  $menu_item The current menu item object.
 * @param stdClass $args      An object of wp_nav_menu() arguments.
 * @param int      $depth     Depth of menu item. Used for padding.
 */
add_filter( 'nav_menu_link_attributes', 'better_a11y_nav_menu_link_attributes', 10, 4 );
function better_a11y_nav_menu_link_attributes( $atts, $menu_item, $args, $depth ) {
    // Add custom aria label attribute
    $menu_meta_arialabel = get_post_meta( $menu_item->ID, '_menu-item-arialabel', true );
    if ( ! empty( $menu_meta_arialabel ) ) {
        $atts[ 'aria-label' ] = esc_attr( $menu_meta_arialabel );
    }

    // Add "new tab" in aria label when target is blank
    if ( isset( $menu_item->target ) && '_blank' === $menu_item->target ) {
		if ( isset( $atts[ 'aria-label' ] ) && ! empty( $atts[ 'aria-label' ] ) ) {
			$atts[ 'aria-label' ] .= __( ' (new tab)', 'better-accessibility' );
		} else {
			$atts[ 'aria-label' ] = $menu_item->title . __( ' (new tab)', 'better-accessibility' );
		}
	}
    return $atts;
}