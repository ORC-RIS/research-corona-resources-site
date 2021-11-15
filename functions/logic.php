<?php

function get_published_posts( $post_type, $count = 4, $ostArgs = array() ){

    $args = array_merge( array(
        'post_type'         => $post_type,
        'posts_per_page'    => $count,
        'post_status'       => 'publish',
    ), $ostArgs );

	return new WP_Query( $args );
}
function get_published_posts_by_category($post_type, $count=1, $tax, $paged = 1 ) {

    if(! is_numeric( $paged ) )
        return null;

    $args = array(
        'post_type'         => $post_type,
        'posts_per_page'    => $count,
        'paged'             => $paged,
        'post_status'       => 'publish',
        'tax_query'         => array(
            array(
                'taxonomy' => $post_type . '_tax',
                'field'    => 'slug',
                'terms'    => $tax
            ),
        ),
        'order' => 'DESC',
        'orderby' => 'date',
    );

    return new WP_Query($args);
}

function json_to_php($feed_url){

	$json_feed = file_get_contents($feed_url);
	$results_array = json_decode($json_feed);

	return $results_array;
}

function get_random_profile($type, $count){

	$args = array('post_type'=>'profiles',
		'orderby'=>'rand',
		'posts_per_page'=> $count,
		'meta_query'=>array(
			array('key' => 'profile_type',
			 	'value' => $type,
				'compare'=>'=')));

	$wp_query = new WP_Query($args);
	$profiles = $wp_query->posts;

	foreach($profiles as &$profile){

		$profile->profile_photo = get_profile_image($profile->ID);
	}

	return $profiles;
}

function get_profile_image($id){

	$profile_photo = get_the_post_thumbnail($id,array(250,250));
		
	if(!$profile_photo){

		/* Default profile image */
		$profile_photo = '<img width="250" height="250" src="'.get_template_directory_uri().'/images/knight-head.jpg"/>';
		
	}

	return $profile_photo;
}

function read_more($text){
	if(strlen($text)>50){
		$text = substr($text,0,50).'...';
	}
	return $text;
}

/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
 */
function sort_terms_hierarchically(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        sort_terms_hierarchically($cats, $topCat->children, $topCat->term_id);
    }
}

function term_hierarchically_flatten( Array &$cats ) {

    $flat_cats = array();

    foreach( $cats as $cat ) {
        array_push( $flat_cats, $cat );
        if( sizeof( $cat->children ) > 0 )
            $flat_cats = array_merge( $flat_cats, term_hierarchically_flatten( $cat->children ));
    }

    return $flat_cats;
}

function get_degree_program_list_from_terms( $termArray ) {
    $sortedDegreePrograms = array();
    sort_terms_hierarchically( $termArray,  $sortedDegreePrograms );
    return term_hierarchically_flatten( $sortedDegreePrograms );
}
