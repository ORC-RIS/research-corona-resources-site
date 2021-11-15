<?php
/* Template Name: No title */
$image_path = get_stylesheet_directory_uri() . '/images';

get_header(); ?>
        <?php
        while(have_posts()){
            the_post();
            ?>
            <h1 class="d-none"><?php the_title(); ?></h1>
            <div class="u-container">
            <?php the_content(); ?>
            </div>
            <?php
            if( get_edit_post_link() != '' ) {
                echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
            }
        }
        ?>
<?php get_footer();
