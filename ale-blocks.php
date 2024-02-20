<?php
/**
 * Plugin Name:       Ale Blocks
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ale-blocks
 *
 * @package           create-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//Classic Metaboxes
function ale_create_classic_meta(){

  add_meta_box(
    'ale_metafields_box',
    __('Custom Meta','ale-blocks'),
    'ale_custom_render_meta',
    'post',
    'side',
    'default'
  );
}
add_action('add_meta_boxes','ale_create_classic_meta');

//Render Classic Meta
function ale_custom_render_meta($post){
  wp_nonce_field('ale_save_custom_meta','ale_metafields_box_nonce' );
  $title_two = get_post_meta($post->ID, '_meta_field_title_two', true);
  ?>
  <div>
    <h4>Title:</h4>
    <p><input type="text" id="_meta_field_title_two" name="_meta_field_title_two" value="<?php echo $title_two; ?>" /></p>
  </div>
  <?php
}

//Save Classic Meta
function ale_save_custom_meta($post_id){

  if(!isset($_POST['ale_metafields_box_nonce'])) return;

  if(!isset($_POST['_meta_field_title_two'])) return;

  if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  if(!current_user_can('edit_post', $post_id)) return;

  $title_two = $_POST['_meta_field_title_two'];

  update_post_meta($post_id, '_meta_field_title_two', $title_two);
}
add_action('save_post','ale_save_custom_meta');


//Register Meta
function ale_register_metabox(){
  register_post_meta('','_meta_field_title_one', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function(){
      return current_user_can('edit_posts');
    }
  ));

  register_post_meta('','_meta_field_title_two', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function(){
      return current_user_can('edit_posts');
    }
  ));

  register_post_meta('','_meta_field_color', array(
    'show_in_rest' => true,
    'type' => 'string',
    'single' => true,
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function(){
      return current_user_can('edit_posts');
    }
  ));
}
add_action('init','ale_register_metabox');

//Front for Meta from Fields
function ale_custom_metaboxes_render($attributes, $content, $block){
  $title_one = get_post_meta(get_the_ID(),'_meta_field_title_one', true);

  return $title_one;
}

//Register Custom Blocks
function ale_blocks_ale_blocks_block_init() {
	register_block_type( __DIR__ . '/build/block-fields', array(
    'render_callback' => 'ale_custom_metaboxes_render'
  ) );
  register_block_type( __DIR__ . '/build/block-meta' );
}
add_action( 'init', 'ale_blocks_ale_blocks_block_init' );
