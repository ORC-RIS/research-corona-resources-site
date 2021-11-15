<?php

define('PROFILE_TYPES', array('Student','Faculty','PostDoc') );

function cgs_get_profile_types(){
	return PROFILE_TYPES;
}

function cgs_generate_labels( $name, $singular ) {
	return array(
		'name'               => $name,
		'singular_name'      => $singular,
		'menu_name'          => $name,
		'name_admin_bar'     => $singular,
		'add_new'            => "Add New $singular",
		'add_new_item'       => "Add New $singular",
		'new_item'           => "New $singular",
		'edit_item'          => "Edit $singular",
		'view_item'          => "View $singular",
		'all_items'          => "All $name",
		'search_items'       => "Search $name",
		'parent_item_colon'  => "Parent $name:",
		'not_found'          => "No $name found.",
		'not_found_in_trash' => "No $name found in Trash.",
	);
}

function cgs_save_file($id, $file_object, $file_meta, $file_name_meta){
    
	$cgs_attachment_name = $file_object['name'];

    if(!empty($cgs_attachment_name)) {
         
        //$supported_types = array('application/pdf');
        //$wp_check_filetype = wp_check_filetype(basename($cgs_attachment_name));
        //$uploaded_type = $wp_check_filetype['type'];
         
        //if(in_array($uploaded_type, $supported_types)) {
            
		$upload = wp_upload_bits($cgs_attachment_name,
			null,
			file_get_contents($file_object['tmp_name']));

		if(isset($upload['error']) && $upload['error']!=0){
			wp_die('There was an error uploading your file: '.$upload['error']);
		} else {
			update_post_meta($id, $file_meta, $upload['url']);
			update_post_meta($id, $file_name_meta, $cgs_attachment_name);
		}
 
        //}else{
        //    wp_die("Unsupported filetype.");
        //}
    }
}
function save_array($id, $post_key, $meta_key) {
    if (isset($_POST[$post_key]) && is_array($_POST[$post_key]) && !empty($_POST[$post_key]))
        update_post_meta($id, $meta_key, $_POST[$post_key]);
}
function cgs_render_file_upload($file_name, $file_url, $input_name){

	if($file_url){
		print "Current File: <a href='$file_url'>$file_name</a><br><br>
		<input type='checkbox' name='delete_$input_name'/>Delete attachment on save.<br><br>";
	}
	
	print "Choose file to add or replace existing one.
		  <input type='file' id='$input_name' name='$input_name' value=''/>";
}
function cgs_input_text($label, $input_name, $post_ID ){
    $value = esc_html( get_post_meta( $post_ID, $input_name, true ) );

	print "$label <input type='text' class='wide' id='$input_name' name='$input_name' value='$value'/>";
}