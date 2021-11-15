<?php
/* Template Name: Side menu 1-2 template */
$menu_name          = get_field('menu_name');
$social_media       = get_field('social_media');
$show_page_title       = get_field('show_page_title');
$show_next_and_prev_links = get_field('show_next_and_prev_links');

$guide_top_image = get_field('guide_top_image');


$paging = gs_custom_menu_pagination(array(
    'menu'              => $menu_name,
    'container_id'      => $menu_name,
    'container_class'   => 'wp-plain-menu',
    'menu_class'        => 'nav_deck'
));

get_header(); ?>
        <?php
        while(have_posts()){
            the_post();

            ?>
            <?= do_shortcode( get_post_meta( get_the_ID(), 'hero_splash', true ), true ) ?>
            <?php if( $guide_top_image ): ?>
            <div style="background: url('<?= $guide_top_image ?>') no-repeat center center; background-size: cover; overflow: hidden;">
                <div class="container">
                    <div style="padding: 75px 0;">
                        <div class="h1 header-title frame-yellow mb-0 d-inline-block"><?= get_field('guide_top_title') ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-9 offset-sm-3 col-md-5 col-lg-6">
                        <?php if( $show_page_title === 'show' ): ?>
                        <h1 class="heading-underline mt-5"><?php the_title(); ?></h1>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 text-right">
                        <?php if( $social_media ): ?>
                            <?= do_shortcode('[social_media]'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="container <?= ( $show_page_title === 'hide' && !$social_media )? 'mt-5': '' ?> mb-5">
                <div class="row">
                    <div class="col-12 col-sm-3 mt-5">
                        <?php wp_nav_menu(array(
                            'menu'              => $menu_name,
                            'container_id'      => $menu_name,
                            'container_class'   => 'wp-plain-menu',
                            'menu_class'        => 'nav_deck'
                        )); ?>
                    </div>
                    <div class="col-12 col-sm-9 ">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <?php if( $show_next_and_prev_links ): ?>
            <section class="mb-5">
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col col-sm-9">
                            <hr class="m-5">
                            <div class="d-flex flex-row justify-content-between">
                                <a class="<?= $paging['previous']['visibility'] ?>" href="<?= $paging['previous']['url'] ?>">Previous Guide</a>
                                <a class="<?= $paging['next']['visibility'] ?>" href="<?= $paging['next']['url'] ?>">Next Guide</a>
                            </div>
                            <div class="d-flex flex-row justify-content-between">
                                <span class="<?= $paging['previous']['visibility'] ?>" style="max-width: 50%"><?= $paging['previous']['title'] ?></span>
                                <span class="<?= $paging['next']['visibility'] ?>" style="max-width: 50%"><?= $paging['next']['title'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            <?php
            if( get_edit_post_link() != '' ) {
                echo '<section><a style="width: 100%;" href="' . get_edit_post_link() . '" class="breakout btn btn-primary btn-sm">Edit</a></section>';
            }
        }
        ?>
<?php get_footer();
