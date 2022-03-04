import './style.scss';

const { __ } = wp.i18n;
const { registerFormatType, toggleFormat } = wp.richText;
const { RichTextToolbarButton } = wp.blockEditor;
 
const ScreenReaderTextFormat = ( { isActive, onChange, value } ) => {
    return (
        <RichTextToolbarButton
            icon="hidden"
            title={__( 'Hide Visually', 'better-accessibility' ) }
            onClick={ () => {
                onChange(
                    toggleFormat( value, {
                        type: 'screen-reader-format/span',
                        attributes: {
                            class: 'better-a11y-screen-reader-text'
                        }
                    } )
                );
            } }
            isActive={ isActive }
        />
    );
};
 
registerFormatType( 'screen-reader-format/span', {
    title: __( 'Screen Reader Text', 'better-accessibility' ),
    tagName: 'span',
    className: 'screen-reader-text',
    edit: ScreenReaderTextFormat,
} );