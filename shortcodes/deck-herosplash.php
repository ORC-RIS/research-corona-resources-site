<?php
function theme_deck_hero_splash( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'title' => '',
		'subtitle' => '',
        'title-container-class' => '',
        'title-style' => '',
		'title-image' => '',
		'title-image-alt' => '',
        'video' => '',
        'image' => '',
    ), $atts );
    ?>
    <section class="hero-splash mb-0 <?= $a['class'] ?>" style="<?= $a['style'] ?>">
		<div class="hero-splash-container media-background-container">
			<div style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; display:flex; align-items: center; justify-content: flex-start">
				<?php if( !empty( $a['title-image'] ) ) { ?>
				<div class="hero-title-image-container">
					<img src="<?= $a['title-image'] ?>" alt="<?= $a['title-image-alt'] ?>" style="max-width: 100%; max-height: 100%;">
				</div>
				<?php } ?>
				<?= !empty( $a["title"] )? "<h1 class='d-none'>${a["title"]}</h1>": '' ?>
				<div class="hero-title-container <?= $a['title-container-class'] ?>" <?php if( !empty( $a['title-image'] ) ) { ?>style="display: none;"<?php } ?>>
					<?= !empty( $a["title"] )? "<div class=\"hero-title\" style=\"${a['title-style']}\">${a["title"]}</div>": '' ?>
					<?= !empty( $a["subtitle"] )? "<div class=\"hero-subtitle\">${a["subtitle"]}</div>": '' ?>
				</div>
			</div>
			<?php if( !empty( $a['video'] ) && !wp_is_mobile() ) { ?>
			<video class="media-background object-fit-cover" autoplay="autoplay" loop="loop" muted="" width="300" height="150">
				<source src="<?= $a['video'] ?>" type="video/mp4" />
				<?php if( !empty( $a['image'] ) ) { ?>
				<img src="<?= $a['image'] ?>">
				<?php } ?>
			</video>
			<?php } else if( !empty( $a['image'] ) ) { ?>
				<img class="media-background object-fit-cover" src="<?= $a['image'] ?>" alt="<?= $a['title'] ?>">
			<?php } else { ?>
				<img class="media-background object-fit-cover" src="<?php echo get_template_directory_uri(); ?>/images/hero.png">
			<?php } ?>
		</div>
        <?= do_shortcode( $content ) ?>
    </section>
<?php
}
function theme_deck_hero_splash_actions( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
    ), $atts );

    if( $a['class'] )
        $a['class'] = 'class="'. $a['class'].'"';

    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    ?>
    <div class="jumbotron-fluid heroic-actions">
        <div class="container">
            <div class="row text-center align-items-stretch">
                <?= do_shortcode( $content, true ) ?>
            </div>
        </div>
    </div>
<?php
}
function theme_deck_hero_splash_action_item( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
		'target' => '_blank',
        'url'   => '#',
    ), $atts );

    if( $a['class'] )
        $a['class'] = 'class="'. $a['class'].'"';

    if( $a['style'] )
        $a['style'] = 'style="' . $a['style'] . '"';

    ?>
    <div class="col-12 col-sm p-2">
        <a target="<?= $a['target'] ?>" href="<?= $a['url'] ?>" class="btn btn-primary w-100 d-flex justify-content-center align-items-center" style="height: 100%;"><?= $content ?></a>
    </div>
<?php
}
function theme_deck_hero_tickets( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'url'   => '#',
    ), $atts );

    if( trim( $content ) ) {
    ?>
    <div class="d-flex" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; align-items: center;">
        <div class="container">
            <div class="row justify-content-end">
                <div class="hero-tickets d-none d-md-block">
                    <?= do_shortcode($content, true ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
}
function theme_deck_hero_ticket( $atts = array(), $content = null ) {
    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
        'type'  => '',
        'url'   => '#',
    ), $atts );
    ?>
    <a href="<?= $a['url'] ?>">
        <div class="nav-item nav-pull-right">
            <?php if( $a['type'] ) { ?>
            <div class="title"><?= $a['type'] ?></div>
            <?php } ?>
            <div class="desc"><?= $content ?></div>
        </div>
    </a>
    <?php
}



add_shortcode('deck_hero_splash', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_splash( $atts, $content );

    return ob_get_clean();
});
add_shortcode('deck_hero', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_splash( $atts, $content );

    return ob_get_clean();
});
add_shortcode('dhs_tickets', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_tickets( $atts, $content );

    return ob_get_clean();
});
add_shortcode('dhs_ticket', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_ticket( $atts, $content );

    return ob_get_clean();
});
add_shortcode('dhs_actions', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_splash_actions( $atts, $content );

    return ob_get_clean();
});
add_shortcode('dhs_action_item', function ( $atts, $content ) {
    ob_start();

    theme_deck_hero_splash_action_item( $atts, $content );

    return ob_get_clean();
});