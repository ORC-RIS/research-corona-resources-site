<?php
function theme_breakout_container( $atts = array(), $content = null ) {

    $a = shortcode_atts( array(
        'class' => '',
		'background_image' => '',
		'style' => '',
    ), $atts );

    ?>
    <div class="breakout-container"><?= $content ?></div>
<?php
}
function theme_breakout( $atts = array(), $content = null ) {

    $a = shortcode_atts( array(
        'class' => '',
		'background_image' => '',
        'style' => '',
    ), $atts );

	$has_background_image = !empty( $a['background_image'] );

	$style = '';
	if( $has_background_image )
		$style .= " background: url('{$a['background_image']}') no-repeat center center; background-size: cover;";

	$style .= ' ' . $a['style'];

	?>
    <section class="breakout <?= $a['class'] ?>" style="<?= $style ?>"><?= do_shortcode( $content ) ?></section>
<?php
}

function theme_shortcode_breakout_container( $atts, $content ) {
    ob_start();

    theme_breakout_container( $atts, $content );

    return ob_get_clean();
}

function theme_shortcode_breakout( $atts, $content ) {
    ob_start();

    theme_breakout( $atts, $content );

    return ob_get_clean();
}

add_shortcode('breakout_container', 'theme_shortcode_breakout_container');
add_shortcode('breakout', 'theme_shortcode_breakout');