<?php
function theme_social_media( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'text' => '',
        'url' => '',
        'pull' => '',
        'size' => '',
        'showshare' => 'yes'
    ), $atts );

    $a['class'] .= ' share-nav';
    if( $a['class'] )
        $a['class'] = 'class="'. $a['class'].'"';

    if( $a['pull'] )
        $a['pull'] = 'class="pull"';

    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    $url = $a['url'];
    $text = $a['text'];


    $facebook_URI   = "https://www.facebook.com/sharer/sharer.php?u=$url&title=$text";
    $twitter_URI    = "https://twitter.com/intent/tweet?original_referer=$url&text=$text&url=$url";
    $linkedIN_URI   = "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$text";
    $email_URI      = "mailto:?subject=Check out $text&body=$text - $url";
    ?>
    <div <?= $a['class']?:'' ?> <?= $a['style'] ?>>
        <?php if( 'yes' == $a['showshare'] ): ?><span class="h-3">Share:</span><?php endif; ?>
        <a class="facebook" href="<?= esc_url( $facebook_URI )  ?>"><i class="fab <?= $a['size'] ?> fa-facebook-square"></i></a>
        <a class="twitter"  href="<?= esc_url( $twitter_URI  )  ?>"><i class="fab <?= $a['size'] ?> fa-twitter-square"></i></a>
        <a class="linkedin" href="<?= esc_url( $linkedIN_URI )  ?>"><i class="fab <?= $a['size'] ?> fa-linkedin"></i></a>
        <a class="email"    href="<?= esc_url( $email_URI    )  ?>"><i class="fas <?= $a['size'] ?> fa-envelope"></i></a>
    </div>
    <?php
}
add_shortcode('social_media', function( $atts, $content ) {
    ob_start();

    theme_social_media( $atts, $content );

    return ob_get_clean();
});