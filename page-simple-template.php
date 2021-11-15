<?php
/* Template Name: Simple template */

add_filter('the_content', function($content) {
    $content = force_balance_tags($content);
    return preg_replace('/<p>(?:\s|&nbsp;)*?<\/p>/i', '', $content);
}, 10, 1);
get_header('simple'); ?>
<?php
while( have_posts() ){
    the_post();
    the_content();
    if( get_edit_post_link() != '' ) {
        echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
    }
}

get_footer('admissions');
