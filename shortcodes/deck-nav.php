<?php

namespace deck;
function nav_theme_render__menu( $menu_name ) {
	return wp_nav_menu( array( 'menu' => $menu_name, 'menu_class' => 'nav_deck', 'echo' => false ) );
}

add_shortcode('deck_nav', function ( $atts = array(), $content = null ) {
    ob_start();

    $a = shortcode_atts( array(
        'class' 	=> '',
		'style' 	=> '',
        'menu' 		=> ''
    ), $atts );

    ?>
	<style>
		ul.nav_deck {
			padding-left: 0 !important;
			list-style: none;
		}
		ul.nav_deck ul {
			padding-left: 2rem !important;
			list-style: none;
		}
	</style>
    <section class="section-nav jumbotron jumbotron-fluid <?= $a['class'] ?>" style="<?= $a['style'] ?>">
		<div class="u-container">
			<h2>Contents</h2>
        <nav class="<?= $a['class'] ?>">
        <?= nav_theme_render__menu( $a['menu'] ) ?>
        </nav>
		</div>
    </section>
    <?php

    return ob_get_clean();
});

?>
