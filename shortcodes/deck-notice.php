<?php

add_shortcode('notice', function( $atts = array(), $content = null ) {
    ob_start();

    $a = shortcode_atts( array(
        'class' => '',
        'style' => ''
    ), $atts );

    ?>
    <section class="section-notice alert alert-info" role="alert">
        <div class="container">
            <?= do_shortcode( $content ) ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
});