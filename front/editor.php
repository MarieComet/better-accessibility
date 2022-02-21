<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Editor related functions
 */


 /**
  * Filter block render
  */
add_filter( 'render_block', 'better_a11y_filter_blocks_render', 10, 2 );
function better_a11y_filter_blocks_render( $block_content, $block ) {
    // Hide hr blocks for screen readers
    if ( 'core/separator' === $block['blockName'] ) {
        $block_content = str_replace( '<hr', '<hr aria-hidden="true"', $block_content );
    }

    if ( 'core/button' === $block['blockName'] || 'core/image' === $block['blockName'] ) {
        // Add custom aria label to button links
        $blank = str_contains( $block_content, 'target="_blank"' );
        if ( isset( $block[ 'attrs' ][ 'arialabel' ] ) && ! empty( $block[ 'attrs' ][ 'arialabel' ] ) ) {
            $replace = sprintf(
                '<a aria-label="%s%s"',
                $block[ 'attrs' ][ 'arialabel' ],
                $blank ? __( ' (new tab)', 'better-accessibility' ) : ''
            );
            $block_content = str_replace( '<a', $replace, $block_content );
        } else {
            // Add aria label to image with link
            if ( 'core/image' === $block['blockName'] && isset( $block['attrs']['linkDestination'] ) && 'none' != $block['attrs']['linkDestination'] ) {
                preg_match( '/alt="([^"]+)"/', $block_content, $image_alt );
                if ( empty( $image_alt ) ) {
                    $replace = sprintf(
                        '<a aria-label="%s%s"',
                        __( 'Open image', 'better-accessibility' ),
                        $blank ? __( ' (new tab)', 'better-accessibility' ) : ''
                    );
                    $block_content = str_replace( '<a', $replace, $block_content );
                } else {
                    if ( $blank ) {
                        $replace = sprintf(
                            'alt="%s%s"',
                            $image_alt[1],
                            $blank ? __( ' (new tab)', 'better-accessibility' ) : ''
                        );
                        $block_content = str_replace( 'alt="' . $image_alt[1] . '"', $replace, $block_content );
                    }                    
                }
            }
            if ( 'core/button' === $block['blockName'] ) {
                if ( $blank ) {
                    preg_match( '/<a[^>]*>(.*)<\/a>/', $block_content, $button_text );
                    $replace = sprintf(
                        '<a aria-label="%s%s"',
                        $button_text[1],
                        __( ' (new tab)', 'better-accessibility' )
                    );
                    $block_content = str_replace( '<a', $replace, $block_content );
                }
            }            
        }
    }

    return $block_content;
}