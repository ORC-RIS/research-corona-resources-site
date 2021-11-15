<?php
CONST POST_TYPE = 'featured-content';

add_shortcode('deck_featured_content', function ( $atts, $content ) {
    ob_start();

    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'slug' => '',
        'target' => '_blank',
        'mod' => '4',
        'button_text' => 'Read More',
    ), $atts );

    $a['class'] .= ' profile';
    if( $a['class'] )
        $a['class'] = 'class="'. $a['class'].'"';

    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    ?>
    <section class="card-deck mt-5 mb-5 featured-content">
        <div class="container d-flex flex-wrap justify-content-center">
            <?php
            $args = array(
                'post_type'         => 'featured-content',
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'tax_query'         => array(
                    array(
                        'taxonomy' => 'featured-content_tax',
                        'field'    => 'slug',
                        'terms'    => $a['slug']
                    ),
                ),
                'orderby' => 'menu_order date',
                'order' => 'DESC',
            );

            $loop = new WP_Query( $args );
            while ($loop->have_posts()) : $loop->the_post();
                $post_id = get_the_ID();
                $url = get_post_meta( $post_id, 'featured_url', true );
                $button_text = get_field( 'button_text', $post_id );
                if( ! $button_text ) {
                    $button_text = "Read Me";
                }
                ?>
                <div class="content-card card">
                    <a target="<?= $a['target'] ?>" href="<?= $url ?>">
                        <div class="thumbnail">
                            <div class="thumbnail-frame">
                                <div class="centered">
                                    <img src="<?= get_the_post_thumbnail_url( $post_id, 'gs-menu-size') ?>" alt="Featured Content image">
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="card-block card-block-anchorable">
                        <h3 class="card-title"><a target="<?= $a['target'] ?>" href="<?= $url ?>"><?= get_the_title() ?></a></h3>
                        <p class="card-text"><?= get_the_content() ?></p>
                        <p class="card-text card-bottom-anchor"><a target="<?= $a['target'] ?>" href="<?= $url ?>" class="btn btn-primary btn-sm"><?= $button_text ?></a>
                            <?= ((get_edit_post_link() != '') ? ' <a href="' . get_edit_post_link() . '" class="btn btn-primary btn-sm">Edit</a>' : '') ?>
                        </p>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
});

add_shortcode('deck_acf_repeater', function ( $atts, $content ) {
    ob_start();

    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'repeater' => ''
    ), $atts );


    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    ?>
    <style>
        .andy-lineheight {
            line-height: 2rem;
        }
    </style>
    <sectio[[n class="<?= $a['class'] ?>">
        <div class="container">
            <?php
            $rows = get_field($a['repeater']);
            if($rows) { ?>
                <div>

                <?php foreach($rows as $row) { ?>
                    <div class="d-flex flex-column flex-sm-row align-items-stretch mb-3">
                        <?php if( !empty( $row['content']['thumbnail']['url'] ) ): ?>
                        <div class="p-0 pt-1 pr-3">
                            <a href="<?= $row['link']['url'] ?>">
                                <img src="<?= $row['content']['thumbnail']['sizes']['medium'] ?>" style="width: 280px;">
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="flex-grow-1 pt-1 pb-1 pl-0">
                            <div class="badge badge-primary" style="line-height: 1.5"><?= $row['content']['badge'] ?></div>
                            <h2 class="andy-lineheight mt-1 mb-1"><?= $row['content']['title'] ?></h2>
                            <div class="mb-0">
                                <?= $row['subtext'] ?>
                            </div>
                            <?php if( $row['link']['url']): ?>
                            <strong>
                                <a class=""
                                   href="<?= $row['link']['url'] ?>"
                                   <?= ($row['link']['open_in_new_tab'])?'target="_blank"':'' ?>>
                                    <?= $row['link']['link_text'] ?>
                                </a>
                            </strong>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>

                </div>
            <?php } ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
});