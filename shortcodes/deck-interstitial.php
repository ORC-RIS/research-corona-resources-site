<?php
    function deck_interstitial( $atts = array(), $content = null ) {
        $class_default = 'jumbotron jumbotron-fluid m-0';
        $style_default = 'padding: 8rem 0 8rem 0;';

        $isAppleDevice = // Apple mobile devices have trouble with parallax scrolling, we need to turn it off for them
                stripos($_SERVER['HTTP_USER_AGENT'],"iPod")     !== false
            ||  stripos($_SERVER['HTTP_USER_AGENT'],"iPhone")   !== false
            ||  stripos($_SERVER['HTTP_USER_AGENT'],"iPad")     !== false;

        $a = shortcode_atts( array(
            'is_parallax' => false,
            'background_image' => '',
            'url' => '#',
            'header' => '',
            'button' => '',
            'class' => '',
            'style' => ''
        ), $atts );

        $has_background_image = !empty( $a['background_image'] );

        $style = $style_default;

        if( $has_background_image && !$isAppleDevice ) $style .= " background-size: 100vmax 100vmax; background: url('{$a['background_image']}') no-repeat center center fixed; background-size: cover; ";
        if( $has_background_image && $isAppleDevice ) $style .= " background-size: 100vmax 100vmax; background: url('{$a['background_image']}') no-repeat center center; background-size: cover; ";
        $style .= ' ' . $a['style'];

        $class = $class_default;
        if( $a['is_parallax'] ) $class .= ' parallax';
        $class .= ' ' . $a['class'];

        ?>
        <section class="<?= $class ?>" style="<?= $style ?>">
            <div class="container">
                <div>
                    <div class="h1 header-title frame-yellow mb-0 d-inline-block"><?= $a['header'] ?></div>
                </div>
                <a class="lead frame-black d-inline-block" href="<?= $a['url'] ?>">
                    <?= $a['button'] ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
            </div>
        </section>
        <?php
    }


    function theme_shortcode_deck_interstitial( $atts, $content ) {
        ob_start();

        deck_interstitial( $atts, $content );

        return ob_get_clean();
    }



add_shortcode('deck_interstitial', 'theme_shortcode_deck_interstitial');