<?php
get_header();


global $query_string;
$search_query = array();

$query_args = explode("&", $query_string);

if( !empty( $query_string ) )
	foreach($query_args as $key => $string) {
		$query_split = explode("=", $string);
		$search_query[$query_split[0]] = urldecode($query_split[1]);
	}

$the_query = new WP_Query($search_query);

?>
<div class="page-content-wrapper">
	<div class="u-container">
		<h1>Search Results</h1>
	<?php if ( $the_query->have_posts() ) : ?>
		<ul class="normal-list">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
		</ul>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>
	</div>
</div>
<?= do_shortcode("[deck_search]") ?>

<?php get_footer(); ?>