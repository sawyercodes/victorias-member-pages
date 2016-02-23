<?php
	global $wpdb;

	if ( !empty( $_POST['image'] ) ) {
	$image_url = $_POST['image'];
	$wpdb->insert( 'images', array( 'image_url' => $image_url ), array( '%s' ) ); 
	}
?>