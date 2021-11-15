<?php
    function theme_blockquote( $atts = array(), $content = null ) {
        $a = shortcode_atts( array(
            'class' => '',
            'style' => ''
        ), $atts );

        ?>
        <div class="blockquote-holder">
            <blockquote class="<?= $a['class'] ?>" style="<?= $a['style'] ?>">
                <?= $content ?>
            </blockquote>
        </div>
        <?php
    }
    function theme_shortcode_blockquote( $atts, $content ) {
        ob_start();

        theme_blockquote( $atts, $content );

        return ob_get_clean();
    }
    add_shortcode('blockquote', 'theme_shortcode_blockquote');
