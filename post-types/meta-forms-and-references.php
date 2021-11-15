<?php

/**
 * 'forms-and-references' meta.
 */


add_filter( 'manage_forms-and-references_posts_columns', function($columns) {

    unset( $columns['date'] );

    return array_merge( $columns, array(
        'type'      => __('Form or Reference'),
        'url'       => __('URL'),
        'date'      => __('Date'),
    ));
});

add_action( 'manage_forms-and-references_posts_custom_column' , function ( $column, $post_id ) {
    switch ( $column ) {
        case 'type':

            echo get_post_meta(get_the_ID(), 'cgs_file-type', true);

            break;
        case 'url':

            $value = get_permalink($post_id);

            preg_match('/(?:https{0,1}:\/\/)?(?:www\.)?(?:[a-z0-9\-]+)?(?:\.[a-z\.]+?)?(\/.*)/i', $value, $matches );

            if( is_array( $matches ) && in_array( 0, $matches )  ) {
                $value = $matches[1];
            } else {
                $value = '';
            }

            echo !empty($value)? "<a href='$value'>$value</a>" :' - ';

            break;
    }
}, 10, 2 );


?>