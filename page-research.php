<?php
/* Template Name: Research Generic Template */

add_filter('the_content', function($content) {
    $content = force_balance_tags($content);
    return preg_replace('/<p>(?:\s|&nbsp;)*?<\/p>/i', '', $content);
}, 10, 1);
get_header('research');

while(have_posts()){
    the_post();
    ?>
    <style>
        p {
            line-height: 1.5;
        }
        .has-medium-font-size {
            line-height: 2;
        }
    </style>
    <div class="u-container">
        <?php the_content(); ?>
    </div>
    <?php
    if( get_edit_post_link() != '' ) {
        echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
    }
}

get_footer('research');
