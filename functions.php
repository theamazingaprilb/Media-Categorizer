<?php

function getPostsToMove($baseurl){  
	$baseurl = $baseurl.'/%/%/%.%';	
	global $wpdb  ;
  	return $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE `guid` not 
  	like '$baseurl' and `post_type` = 'attachment'");
}

function updatePostTable($ID,$newPostLocation){
	global $wpdb ;
	return $wpdb->update($wpdb->posts,
		array('guid' => $newPostLocation),
		array('ID' => intval($ID)),
		array('%s'),
		array('%d'));
}

function updatePostmetaTable($ID,$newPostMetaLocation){
	global $wpdb ;
	return $wpdb->update($wpdb->postmeta,
		array('meta_value' => $newPostMetaLocation),
		array('post_id' => intval($ID), 'meta_key' => '_wp_attached_file'),
		array('%s'),
		array('%d','%s'));
}

function updateParent($post_parentID,$original,$replace){
	global $wpdb ;
	$post_parentID = intval($post_parentID);
	$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID = $post_parentID");
	$str = $results[0]->post_content ;
	$str = str_replace($original, $replace, $str);

	return $wpdb->update($wpdb->posts,
		array('post_content' => $str),
		array('ID' => $post_parentID),
		array('%s'),
		array('%d'));
}


function restore_image_meta($ID,$prefix){
	$results = wp_get_attachment_metadata($ID);
	$results['file'] = $prefix.$results['file'] ;
	wp_update_attachment_metadata($ID,$results);
}