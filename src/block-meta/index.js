import { registerPlugin } from '@wordpress/plugins';
import MetaBox from './components/MetaBox';

registerPlugin( 'metadata-plugin', {
	render: MetaBox,
} );
