<?php
/**
 * CPT: Custom Post Type
 */
function cgs_register_ctp($post_name, $label, $singular, $also_supports = null, $attrs = array() ){

    $supports = array('title','editor');

    if(is_array($also_supports)){
        $supports = array_merge($supports, $also_supports);
    }

	$args = array_merge( array(
		'labels' => cgs_generate_labels($label,$singular),
		'public' => true,
		'has_archive' => true,
		'show_in_rest' => true,
		'supports' => $supports
	), $attrs );

    register_post_type( $post_name, $args );
}

function cgs_register_tax($post_name, $singular_name, $allowChildren = true, $rewite = false){
    register_taxonomy(
        $post_name.'_tax',
        $post_name,
        array(
            'label'				=> "$singular_name Types",
            'hierarchical'		=> $allowChildren,
            'show_in_rest'		=> true,
            'rewrite'			=> $rewite,
        )
    );
}

add_action('init', function(){

    /* Register base CTPs */
    cgs_register_ctp( 'faq',"FAQs","FAQ", array( 'revisions' ),array('exclude_from_search'=>true));
    cgs_register_ctp( 'featured-content',"Featured Content","Featured Content",array('thumbnail','page-attributes'),array('exclude_from_search'=>true));
    cgs_register_ctp( 'forms-and-references',"Forms and References","Forms and References", array('revisions'),array('exclude_from_search'=>true));

    /* Taxonomies registration for the CTPs that need it. */
    register_taxonomy(
        'featured-content_tax',
        'featured-content',
        array(
            'label'			=> "Display Locations",
            'hierarchical'	=> true,
            'show_in_rest'	=> true,
            'rewrite' 		=> false
        )
    );
	cgs_register_tax("faq","FAQ");
    cgs_register_tax("forms-and-references", "Forms and References");
    register_taxonomy(
        'forms_type_tax',
        'forms-and-references',
        array(
            'label'			=> "Form Types",
            'hierarchical'	=> true,
            'show_in_rest'	=> true,
        )
    );
});

?>