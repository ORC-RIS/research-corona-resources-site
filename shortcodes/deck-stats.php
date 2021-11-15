<?php

function theme_deck_stats( $atts = array() ) {
    $a = shortcode_atts( array(
    ), $atts );

?>
<section class="stats-section jumbotron jumbotron-fluid bg-inverse d-none d-sm-block"  style="background-image: url('//www.ucf.edu/wp-content/uploads/2016/12/MainPage-StatsBackgroundnoyellow-1.jpg');margin:0 !important;">
    <div class="stats-cover"></div>
    <div class="container section-facts">
        <div class="fact-blocks d-flex flex-column flex-sm-row flex-wrap">
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon text-color-white"><span class="small">#</span>3</div>
                <div class="fact-details">
                    <em><strong>The Princeton Review</strong></em> ranks the Florida Interactive Entertainment Academy video game design graduate program #3 in the U.S.
                </div>
            </aside>
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon"><img src="//www.ucf.edu/wp-content/uploads/2017/05/College-Pages-icons-Grad-techtransfer.png" alt="Technology Transfer"></div>
                <div class="fact-details">
                    UCF is ranked 22nd in the country for Technology Transfer.
                </div>
            </aside>
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon fact-text-stacked text-color-white"><span class="small">Top</span>50</div>
                <div class="fact-details">
                    UCF engineering programs ranked NO. 46 among public universities according to U.S. News &amp; World Report.
                </div>
            </aside>
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon"><img src="//www.ucf.edu/wp-content/uploads/2017/03/College-Pages-icons-Grad.png" alt="NSF Graduate Research Fellowship Students"></div>
                <div class="fact-details">
                    The college enrolls 12 National Science Foundation Graduate Research Fellowship Program (GRFP) students.
                </div>
            </aside>
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon fact-text-stacked text-color-white"><span class="small">Top</span>25</div>
                <div class="fact-details">
                    22 UCF graduate programs are ranked among the top 25 by <em><strong>U.S. News &amp; World Report</strong></em>.
                </div>
            </aside>
            <aside class="fact-block" data-mh="fact-blocks">
                <div class="fact-icon"><img src="//www.ucf.edu/wp-content/uploads/2017/03/College-Pages-icons-Globe.png" alt="Fulbright Scholars"></div>
                <div class="fact-details">
                    <strong>14 Fulbright Scholars</strong> from around the world choose UCF graduate programs to do their research.
                </div>
            </aside>
            <p style="clear: both"></p>
        </div>
    </div>
</section>
<?php
}


add_shortcode('deck_stats', function ( $atts = array(), $content = null ) {
    ob_start();

    theme_deck_stats( $atts, $content );

    return ob_get_clean();
});

?>
