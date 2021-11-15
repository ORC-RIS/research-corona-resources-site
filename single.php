<?php get_header(); ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="heading-underline"><?php the_title(); ?></h1>
                <?php
                while(have_posts()){
                    the_post();
                    the_content();
                    if( get_edit_post_link() != '' ) {
                        echo ' <a href="' . get_edit_post_link() . '" class="btn btn-primary btn-sm">Edit</a>';
                    }
                }
                ?>
            </div>
        </div>

    </div>

<?php

get_footer();
