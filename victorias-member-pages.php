<?php
/*
Plugin Name: Victoria's Member Pages
Plugin URI: http://wp.tutsplus.com/
Description: Create custom biography pages for members containing multiple media types.
Version: 1.0
Author: Victoria Sawyer
Author URI: http://victoriasawyer.com
License: GPLv2
*/


//Create custom post type for Member pages

add_action('init', 'vmp_create_member_page');
add_action('admin_head', 'vmp_embed_media_uploader');
add_action( 'add_meta_boxes', 'add_vmp_metaboxes' );
add_action('save_post', 'save_vmp_metabox');

function vmp_create_member_page() {
	register_post_type('member_pages',
		array(
			'labels' => array(
				'name' => 'Member Pages',
				'singular_name' => 'Member Page',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Member Page',
				'edit' => 'Edit',
				'edit_item' => 'Edit Member Page',
				'new_item' => 'New Member Page',
				'view' => 'View',
				'view_item' => 'View Member Page',
				'search_items' => 'Search Member Pages',
				'not_found' => 'No Member Pages found',
				'not_found_in_trash' => 'No Member Pages found in Trash',
				'parent' => 'Parent Member Page'
			),
			'public' => true,
			'menu_position' => 15,
			'supports' => array( 'title', 'editor', 'thumbnail',  ),
			'taxonomies' => array( '' ),
			'menu_icon' => dashicons-admin-users,
			'has_archive' => true
		)
	);
}


//Add meta boxes

//Define the metabox attributes.
$metaBox = array(
  'id'     => 'vmp_media_uploader',
  'title'    => 'Media Uploader',
  'page'     => 'member_pages',
  'context'  => 'normal',
  'priority'   => 'default',
  'fields' => array(
    array(
      'name'   => 'My Custom Image',
      'desc'   => 'A Custom Image Displayed On Your Site Somewhere.',
      'id'  => 'vmpMedaUploader',  //value is stored with this as key.
      'class' => 'image_upload_field',
      'type'   => 'media'
    )
  )
);


function add_vmp_metaboxes() {
	global $metaBox;
	add_meta_box($metaBox['id'], $metaBox['title'], 'vmp_media_uploader', $metaBox['page'], $metaBox['context'], $metaBox['priority']);
	// add_meta_box('vmp_media_uploader', 'Photo and Video Uploader', 'vmp_media_uploader', 'member_pages', 'normal', 'default');
}


//Create media uploader meta box
/*
function vmp_media_uploader() {  
	?>
	<form method="post">
		<input id="media-url" type="text" name="media-url" />
		<input id="media-uploader-button" type="button" class="button" value="Upload Media" />

		<input type="submit" value="Submit" />
	</form>
	<?php

}
*/

function vmp_media_uploader($post) {
	$values = get_post_custom( $post->ID );
	$text = isset( $values['media-url'] ) ? esc_attr( $values['media-url'][0] ) : "";

	wp_nonce_field( 'vmp_media_uploader_nonce', 'vmp_metabox_nonce' );
	?>

	<p>
    	<input type="text" name="media-url" id="media-url" value="<?php echo $text; ?>" />
		<input id="media-uploader-button" type="button" class="button" value="Upload Media" />
    
	</p>

	<?php


}




function save_vmp_metabox($post_id) {   
	// Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['vmp_metabox_nonce'] ) || !wp_verify_nonce( $_POST['vmp_metabox_nonce'], 'vmp_media_uploader_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

}





//Enqueue scripts 
function vmp_embed_media_uploader() {
?>
	<script type="text/javascript">
	
		jQuery(document).ready(function($){

			var mediaUploader;

			$('#media-uploader-button').click(function(e) {
				e.preventDefault();
				// If the uploader object has already been created, reopen the dialog
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}
				// Extend the wp.media object
				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Media',
					button: {
						text: 'Choose Media'
					}, 
					multiple: false 
				});

				// Open the uploader dialog
				mediaUploader.open();

				//When a file is selected, grab the URL and set it as the text field's value
				mediaUploader.on('select', function() {
					attachment = mediaUploader.state().get('selection').first().toJSON();
					$('#media-url').val(attachment.url);
				});
			});

		});
	

	</script>
<?php
}


?>