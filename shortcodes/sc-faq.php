<?php

function short_code_frequently_asked_question( $faq_post ) {
?>
<div class="d-flex mb-4 flex-column">
    <a href="<?= get_permalink( $faq_post->ID ) ?>" class="ucf-faq-question-link d-flex collapsed" data-toggle="collapse" data-target="#post<?= $faq_post->ID ?>" aria-expanded="false">
        <div class="ucf-faq-collapse-icon-container mr-2 mr-md-3">
            <span class="ucf-faq-collapse-icon" aria-hidden="true"></span>
        </div>
        <h3 class="ucf-faq-question align-self-center mb-0 mt-0"><?= $faq_post->post_title ?></h3>
    </a>
    <div class="ucf-faq-topic-answer ml-2 ml-md-3 mt-2 collapse" id="post<?= $faq_post->ID ?>" aria-expanded="false">
        <div class="card">
            <div class="card-block"><?= $faq_post->post_content ?></div>
        </div>
    </div>
</div>
<?php
}
$faqNum = 0;
function short_code_inline_faq( $atts, $content ) {
    global $faqNum;
    $faqNum++;
    $a = shortcode_atts( array(
        'slug'      => '',
        'notslug'   => '',
        'question'  => '',
        'answer'    => '',
    ), $atts );

    $a['slug'] = sanitize_title( $a['question'] );

    ?>
    <div class="d-flex mb-4 flex-column">
        <a name="<?= $a['slug'] ?>" href="<?= $faqNum ?>" onclick="history.replaceState(undefined, undefined, '#<?= $a['slug'] ?>' )" class="ucf-faq-question-link d-flex collapsed" data-toggle="collapse" data-target="#post<?= $faqNum ?>" aria-expanded="false">
            <div class="ucf-faq-collapse-icon-container mr-2 mr-md-3">
                <span class="ucf-faq-collapse-icon" aria-hidden="true"></span>
            </div>
            <h3 class="ucf-faq-question align-self-center mb-0 mt-0">
                <?= $a[ 'question' ] ?>
            </h3>
        </a>
        <div class="ucf-faq-topic-answer ml-2 ml-md-3 mt-2 collapse" id="post<?= $faqNum ?>" aria-expanded="false">
            <div class="card">
                <div class="card-block">
                    <?= $a[ 'answer' ] ?><?= do_shortcode( $content ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}


add_shortcode('faq', function( $atts = array(), $content = null ) {
    ob_start();

    $a = shortcode_atts(array(
        'slug' => '',
        'notslug' => '',
        'question' => '',
        'answer' => '',
    ), $atts);

    if( !empty( $a[ 'slug' ] ) ) {
        $loop = new WP_Query(array(
            'post_type' => 'faq',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'faq_tax',
                    'field' => 'slug',
                    'terms' => explode(',', $a['slug'])
                ),
                array(
                    'taxonomy' => 'faq_tax',
                    'field' => 'slug',
                    'terms' => explode(',', $a['notslug']),
                    'operator' => 'NOT IN',
                ),
            ),
            'orderby' => 'title',
            'order' => 'ASC',
        ));


        while ($loop->have_posts()) : $loop->the_post();
            $post = $loop->post;

            short_code_frequently_asked_question($post);
        endwhile;
    } else {
        short_code_inline_faq( $a, $content );
    }

    return ob_get_clean();
});