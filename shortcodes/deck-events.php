<?php
// http://events.ucf.edu/upcoming/feed.json
// https://events.ucf.edu/calendar/2529/college-of-graduate-studies-events/upcoming/feed.json


function theme_deck_events( $atts = array() ) {

    $a = shortcode_atts( array(
        'class' => '',
        'style' => '',
		'title' => 'UPCOMING EVENTS',
        'background-image' => '//www.ucf.edu/wp-content/uploads/2017/08/ucf-planerarium-space-events_12717203-1.jpg',
        'feed' => 'https://events.ucf.edu/calendar/2529/college-of-graduate-studies-events/upcoming/',
        'show_number' => get_option('main-site-number-of-events-to-display') ?: 10,
    ), $atts );

    $style = '';
    //if( $a['background-image'] )
        //$style .= "background-image: url(${a['background-image'] });";

    $class = '';
    $class .= $a['class'];


    $feed = $a['feed'] . 'feed.json';

    $json_feed = file_get_contents( $feed );
    if( $json_feed ) {
        $events_array = json_decode($json_feed);
        $number_of_events = intval( $a['show_number'] );
        $num_events = count( $events_array );
        $number_of_events = ( $number_of_events > $num_events )? $num_events: $number_of_events;
    } else { // The fetch failed
        $events_array = array();
        $number_of_events = 0;
    }

?>
<section class="bg-inverse section-events <?= $class ?>" style="<?= $style ?>">
	<div class="media-background-container">
		<img src="<?= $a['background-image'] ?>" alt="UCF Events" class=" media-background object-fit-cover hidden-md-down" data-object-fit="cover">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="event-header-wrapper">
						<h2 class="mt-5 mb-4 title"><?= $a['title']; ?></h2>
						<div class="mb-5"><a href="<?= $a['feed']; ?>" class="btn btn-primary btn-sm">Up next</a></div>
					</div>
					<div class="events-list">
						<?php
						if( count($events_array) > 0 ) {
							$dt = new DateTime( $events_array[0]->starts );
							?>
							<div class="h-event events-list-item event mb-5">
								<?= $dt->format('M d Y g:i A') ?>
								<a href="<?php echo $events_array[0]->url; ?>" class="event-link url external" target="_blank">
									<time class="dt-start event-start-datetime dtstart" >
										<span class="event-start-date"><?= $dt->format('M d') ?>,</span>
										<span class="event-start-year"><?= $dt->format('Y') ?></span>
										<span class="event-start-time"><?= $dt->format('g:i A') ?></span>
									</time>
									<span class="event-title summary"><?php echo $events_array[0]->title ?></span>
									<span class="event-location location"><?php echo $events_array[0]->location ?></span>
								</a>
								<div class="event-description description"><?php echo wp_trim_words( $events_array[0]->description, 40, '…'); ?></div>
							</div>
							<div class="mb-3"><a href="<?= $a['feed']; ?>" class="btn btn-primary btn-sm">Looking ahead</a></div>
							<?php
							for( $i = 1; $i < $number_of_events; $i++ ) {
								$dt = new DateTime( $events_array[$i]->starts );
								?>
								<div class="h-event events-list-item event ">
									<a href="<?= $events_array[$i]->url ?>" class="event-link url external" target="_blank">
										<time class="dt-start event-start-datetime dtstart">
											<span class="event-start-date"><?= $dt->format('M d') ?>,</span>
											<span class="event-start-year"><?= $dt->format('Y') ?></span>
											<span class="event-start-time"><?= $dt->format('g:i A') ?></span>
										</time>
										<span class="event-title summary"><?= $events_array[$i]->title ?></span>
										<span class="event-location location"><?= $events_array[$i]->location ?></span>
									</a>
								</div>
							<?php
							}
						} else {
							?>
							<div>No events found.</div>
							<?php
						}

						?>
						<div class="mb-5 mt-5">
							<a class="events-view-all" href="<?= $a['feed']; ?>">View All Events</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
}


add_shortcode('deck_events', function ( $atts = array(), $content = null ) {
    ob_start();

    theme_deck_events( $atts, $content );

    return ob_get_clean();

});

?>
