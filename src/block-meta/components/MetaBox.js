import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginDocumentSettingPanel, PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { PanelRow, TextControl, ColorPicker } from '@wordpress/components';
import { dispatch } from '@wordpress/data';
import { PanelBody } from '@wordpress/components';

const MetaBox = ( { postType, metaFields, setMetaFields } ) => {
	if ( postType !== 'post' ) return null;

	return (
    <>
      <PluginSidebarMoreMenuItem
        target="metafields-sidebar"
      >
        { __( 'Custom Metadata' ) }
      </PluginSidebarMoreMenuItem>
      <PluginSidebar
        name="metafields-sidebar"
        title={ __( 'Custom Metadata' ) }
      >
        <PanelBody>
          <PanelRow>
            <TextControl
              label={ __( 'Title Two' ) }
              value={ metaFields._meta_field_title_two }
              onChange={ ( val ) =>
                setMetaFields( { _meta_field_title_two: val } )
              }
            ></TextControl>
          </PanelRow>
          <PanelRow>
            <ColorPicker
              color={ metaFields._meta_field_color }
              onChange={ ( val ) =>
                setMetaFields( { _meta_field_color: val } )
              }
            ></ColorPicker>
          </PanelRow>
        </PanelBody>
      </PluginSidebar>
			
    </>
	);
};

const applyWithSelect = withSelect( ( select ) => {
	return {
		metaFields: select( 'core/editor' ).getEditedPostAttribute( 'meta' ),
		postType: select( 'core/editor' ).getCurrentPostType(),
	};
} );

const applyWithDispatch = withDispatch( ( dispatch ) => {
	return {
		setMetaFields( val ) {
			dispatch( 'core/editor' ).editPost( { meta: val } );
		},
	};
} );

export default compose( [ applyWithSelect, applyWithDispatch ] )( MetaBox );
