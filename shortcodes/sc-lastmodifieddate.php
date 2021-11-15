<?php
add_shortcode('post-modified-date', 'shortcode_post_modified_date');
function shortcode_post_modified_date($a) {
    if (empty($a['format'])) {
        $a['format'] = get_option('date_format');
    }
    return $a['pretext'] . get_the_modified_date($a['format']);
}