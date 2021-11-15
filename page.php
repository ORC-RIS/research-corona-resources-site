<?php
$image_path = get_stylesheet_directory_uri() . '/images';

get_header(); ?>
        <?php
        while(have_posts()){
            the_post();
            ?>
            <div class="u-container mb-5">
            <?php if( has_post_thumbnail() ): ?>
                <div class="col-sm-12 mb-3">
                    <div style="height: 642px;background: url(<?php echo get_the_post_thumbnail_url(); ?>) center center no-repeat; background-size: cover"></div>
                </div>
            <?php else: ?>
                <div class="mb-3"></div>
            <?php endif; ?>
            <h1 class="heading-underline"><?php the_title(); ?></h1>
            <?php the_content(); ?>
            </div>
            <?php
            if( get_edit_post_link() != '' ) {
                echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
            }
        }
        ?>
<?php get_footer();
