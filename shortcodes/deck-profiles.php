<?php
function theme_profiles( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'slugs' => '',
        'title' => 'Profiles',
    ), $atts );

    $a['class'] .= ' profile';
    if( $a['class'] )
        $a['class'] = 'class="'. $a['class'].'"';

    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    $loop = new WP_Query( array(
        'post_type'         => 'profiles',
        'post_status'       => 'publish',
        'posts_per_page'    => -1,
        'post_name__in'     => [ ...explode(',',$a['slugs']) ],
        'orderby'           => 'title',
        'order'             => 'ASC',
    ) );

    ?>
    <div <?= $a['class']?:'' ?> <?= $a['style'] ?>>
        <div class="d-flex justify-content-between flex-column flex-sm-row align-items-end news-heading-wrap">
            <div class="">
                <h2 class="mb-0"><?= $a['title']; ?></h2>
            </div>
            <div class="">
                <div class="more-news">
                    <a href="/news/">
                        Check out more stories
                    </a>
                </div>
            </div>
        </div>
        <div class="card-deck mb-3">
            <?php while ($loop->have_posts()) : $loop->the_post();
                $post = $loop->post;

                $meta = get_post_meta( $post->ID );
                $profile_type = $meta['profile_type'][0];
                if( array_key_exists( 'alumni', $meta ) && ( $meta['alumni'][0] == 1 || $meta['alumni'][0] == 'on' ) ) {
                    $profile_type = 'Alumni';
                }

                $degree_programs_terms = get_terms( array(
                    'taxonomy' => 'profiles_program_tax',
                    'object_ids' => $post->ID
                ));

                ?>
                <div class="card">
                    <div class="thumbnail-outer">
                        <a href="<?= get_permalink() ?>">
                            <div class="thumbnail">
                                <div class="thumbnail-frame">
                                    <div class="centered">
                                        <?php if( get_the_post_thumbnail_url() != "" ): ?>
                                            <img src="<?= get_the_post_thumbnail_url( null, 'gs-menu-size' ) ?>" alt="Default image">
                                        <?php else: ?>
                                            <img src="<?= wp_get_attachment_image_url( $option_profiles_default_image_id, 'gs-menu-size' ) ?>" alt="<?= get_the_title() ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="ribbon ribbon-<?= strtolower( $profile_type ) ?>"><?= $profile_type ?></div>
                    </div>
                    <div class="card-block">
                        <h3 class="card-title mb-0"><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
                        <?php if( ! empty( $degree_programs_terms ) ): ?>
                            <p>
                                <?php foreach( $degree_programs_terms as $i => $degree_program ):
                                    if( $i > 0 ): echo ", "; endif;
                                    echo $degree_program->name;
                                endforeach; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
    <?php
}
function theme_shortcode_profiles( $atts, $content ) {
    ob_start();

    theme_profiles( $atts, $content );

    return ob_get_clean();
}
add_shortcode('profiles', 'theme_shortcode_profiles');