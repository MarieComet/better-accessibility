/* Add aria label to blocks */

const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

// Enable icon control on the following blocks
const enableAriaLabelOnBlocks = [
    'core/button'
];

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { TextControl, ToolbarGroup, ToolbarButton, Popover } = wp.components;
const { BlockControls } = wp.blockEditor;

import { useState } from '@wordpress/element'

/* Declare custom attributes */
const setAriaLabel = ( settings, name ) => {
    // Do nothing if it's another block than our defined ones.
    if ( ! enableAriaLabelOnBlocks.includes( name ) ) {
        return settings;
    }

    return Object.assign( {}, settings, {
        attributes: Object.assign( {}, settings.attributes, {
            arialabel: { type: 'string' }
        } ),
    } );
};
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'better-accessibility/arialabel',
    setAriaLabel
);

/* Add custom control to blocks */
const withAriaLabel = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {

    	if ( ! enableAriaLabelOnBlocks.includes( props.name ) ) {
            return (
                <BlockEdit { ...props } />
            );
        }

        const [ isVisible, setIsVisible ] = useState( false );
        const toggleVisible = () => {
            setIsVisible( ( state ) => ! state );
        };

        const { arialabel } = props.attributes;
        const isAriaLabelSet = !! arialabel;

        return (
            <Fragment>
                <BlockControls group="block">
                    <ToolbarGroup>
                        <ToolbarButton
                            icon="universal-access"
                            label={ __( 'Aria label', 'better-accessibility' ) }
                            onClick={ toggleVisible }
                            isActive={ isAriaLabelSet }
                        />
                        { isVisible &&
                        <Popover position="bottom center" noArrow focusOnMount={ false }>
                            <div className="block-editor-link-control block-editor-link-control__field block-editor-link-control__arialabel">
                                <TextControl
                                    label={ __( 'Aria label', 'better-accessibility' ) }
                                    help={ __( 'The aria label attribute is used to provide a more explicit link label than the button text, to people using a screen reader. It is not visible on the screen.', 'better-accessibility' )}
                                    value={ arialabel }
                                    onChange={ ( value ) =>
                                        props.setAttributes( { arialabel: value ? value : '' } )
                                    }
                                />
                            </div>
                        </Popover>
                        }
                    </ToolbarGroup>
                </BlockControls>
                <BlockEdit { ...props } />
            </Fragment>
        );
    };
}, 'withAriaLabel' );
wp.hooks.addFilter(
    'editor.BlockEdit',
    'better-accessibility/with-inspector-controls',
    withAriaLabel
);