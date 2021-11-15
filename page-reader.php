<?php
/* Template Name: 770 Width, for better reading */
$image_path = get_stylesheet_directory_uri() . '/images';
$show_page_title       = get_field('show_page_title');

get_header(); ?>
        <?php
        while(have_posts()){
            the_post();
            ?>
            <style>
                .u-container > *, .u-container > * .container {
                    max-width: 770px;
                }
            </style>
            <div class="u-container mb-5">
            <?php if( has_post_thumbnail() ): ?>
                <div class="col-sm-12 mb-3">
                    <div style="height: 642px;background: url(<?php echo get_the_post_thumbnail_url(); ?>) center center no-repeat; background-size: cover"></div>
                </div>
            <?php else: ?>
                <div class="mb-3"></div>
            <?php endif; ?>
            <?php if( "hide" !== $show_page_title ): ?>
            <h1 class="heading-underline"><?php the_title(); ?></h1>
            <?php endif; ?>
            <?php the_content(); ?>
            </div>
            <?php
            if( get_edit_post_link() != '' ) {
                echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
            }
        }
        ?>
<?php get_footer();
