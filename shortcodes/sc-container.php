<?php

function theme_sc_container( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
    ), $atts );


    ?>
    <div class="container <?= $a['class'] ?>" style="<?= $a['style'] ?>">
        <?= do_shortcode( $content ) ?>
    </div>
<?php
}


add_shortcode('container', function ( $atts, $content ) {
    ob_start();

    theme_sc_container( $atts, $content );

    return ob_get_clean();
});