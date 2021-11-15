<?php



function theme_deck_search( $atts = array() ) {
    $a = shortcode_atts( array(
        'categories' => '',
    ), $atts );

?>
<section class="jumbotron jumbotron-fluid section-search mb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form role="search" method="get" id="searchform" class="searchform" action="/">
                    <div class="w-100 d-flex flex-column flex-sm-row align-items-center justify-content-between">
                        <label class="m-2" style="white-space: nowrap" for="s">Looking for something?</label>
                        <input class="m-2 form-control" type="text" value="<?php echo get_search_query() ?>" name="s" id="s" placeholder="Search our site..."/>
                        <input class="m-2 btn btn-primary" type="submit" id="searchsubmit" value="Search" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
}

add_shortcode('deck_search', function ( $atts = array(), $content = null ) {
    ob_start();

    theme_deck_search( $atts, $content );

    return ob_get_clean();

});

?>
