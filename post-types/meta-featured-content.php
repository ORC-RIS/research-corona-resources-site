<?php

add_action('admin_init', function () {
    // elementID, Element Title, Render Function, post-type, location
    add_meta_box('cgs_featured_content_details', 'Details', 'render_cgs_featured_content', 'featured-content', 'side');
});

function render_cgs_featured_content($post) {
    $featured_url =  esc_html(get_post_meta($post->ID, 'featured_url', true));
    ?>
    URL <input type="text" class="wide" name="featured_url" value="<?= $featured_url ?>" placeholder="Featured Url"/>
    <?php
}


add_filter( 'manage_featured-content_posts_columns', function($columns) {

    unset( $columns['date'] );

    return array_merge( $columns, array(
		'menu_order'=> __('Order'),
        'locations' => __('Locations'),
        'url'       => __('URL'),
        'date'      => __('Date'),
    ));
});


add_action( 'manage_featured-content_posts_custom_column' , function ( $column, $post_id ) {
	global $post;

    switch ( $column ) {
        case 'locations':
            $terms = get_the_terms( $post_id, 'featured-content_tax');
            $arr_edit_links = array();
            if( !$terms ) {
                echo '-';
                break;
            }
            for( $i = 0, $count = count($terms); $i < $count; $i++ ) {
                $term = $terms[$i];
                $name = $term->name;
                $link = get_edit_term_link( $term->term_id, $term->taxonomy, 'featured-content');
                $arr_edit_links[] = "<a href='$link'>$name</a>";
            }

            if ( is_array( $arr_edit_links ) && count( $arr_edit_links ) )
                echo implode(', ', $arr_edit_links);
            else
                echo '-';

            break;
        case 'url':
            $value = get_post_meta( $post_id, 'featured_url', true );

            echo $value? "<a href='$value'>$value</a>" :'';

            break;
		case 'menu_order':
			$order = $post->menu_order;
			echo $order;
			break;
    }
}, 10, 2 );


add_filter('manage_edit-featured-content_sortable_columns',function ( $columns ) {
	$columns['menu_order'] = 'menu_order';
	$columns['locations'] = 'locations';
	return $columns;
});


add_action('save_post_featured-content', function ( $id ){
        $input = array('featured_url');

        foreach($input as $name){
            if( isset( $_POST[ $name ] ) ) {
                update_post_meta( $id, $name, $_POST[ $name ] );
            }
        }
    }
);
?>