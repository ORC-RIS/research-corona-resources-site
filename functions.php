<?php
$root_template_directory = get_template_directory();

define( 'REMOVE_FROM_ADMIN_MENU', array('Posts','Comments') );

require_once( $root_template_directory . '/post-types/helpers.php' );
require_once( $root_template_directory . '/post-types/cpt-registration.php' );

require_once( $root_template_directory . '/post-types/meta-featured-content.php' );
require_once( $root_template_directory . '/post-types/meta-forms-and-references.php' );


require_once( $root_template_directory . '/functions/customizer.php' );

add_theme_support('post-thumbnails');
add_theme_support('custom-header');
add_theme_support('yoast-seo-breadcrumbs');

// Fix paragraphs wrapping our shortcodes
function wpex_clean_shortcodes($content){
    $array = array (
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']'
    );
    $content = strtr($content, $array);
    return $content;
}
add_filter('the_content', 'wpex_clean_shortcodes');

add_action('init', function() {
    add_image_size( 'gs-menu-size', 250, 250, true );
	$role = get_role( 'editor' );
	$role->add_cap( 'gravityforms_edit_forms' );
	$role->add_cap( 'gravityforms_view_entries' );
	$role->add_cap( 'gravityforms_edit_entries' );
	$role->add_cap( 'gravityforms_delete_entries' );
	$role->add_cap( 'gravityforms_export_entries' );
	$role->add_cap( 'gravityforms_view_entry_notes' );
	$role->add_cap( 'gravityforms_edit_entry_notes' );
	$role->add_cap( 'gravityforms_preview_forms' );

    $root_template_directory = get_template_directory();
    require_once( $root_template_directory . '/shortcodes/shortcodes.php' ); // Shortscodes need to be called later in initialization
});

add_action( 'admin_head', function() {
    wp_enqueue_style('cgs-admin-css', get_theme_file_uri('css/admin/style.css'), array(), false, false);
});

add_action( 'admin_enqueue_scripts', function() {
	// Wordpress already loads a version of JQuery, I'm leaving this here as a reminder that Two versions of Jquery can't coexist in WP
    wp_enqueue_script( 'admin-index', get_template_directory_uri() . '/js/admin/index.js', array(), '', false );
    wp_enqueue_script( 'image-upload', get_template_directory_uri() . '/js/admin/image-upload.js', array(), '', true );
});

add_action( 'wp_enqueue_scripts', function () {
    wp_register_script('tether', get_template_directory_uri() . '/js/tether.min.js', array(), '', true );
    wp_enqueue_script('tether');
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.2.1.min.js', array(), '', true );
    wp_enqueue_style('cgs', get_stylesheet_directory_uri().'/style.min.css', array(), false, false);
    wp_register_script('index', get_template_directory_uri() . '/js/index.js', array(), '', true );
    wp_enqueue_script('index');
});


add_action( 'admin_menu', function () {
    global $menu;
    end ($menu);
    // Removes items defined in REMOVE_FROM_ADMIN_MENU from the admin menu
    while( prev( $menu ) ) {
        $item = explode(' ',$menu[key($menu)][0]);
        if( in_array( $item[0] != NULL? $item[0]:"", REMOVE_FROM_ADMIN_MENU ) ) {
            unset($menu[key($menu)]);
        }
    }
    // UCF Events options.
    add_options_page('UCF Student Handbook Options','UCF Student Handbook Options','manage_options','student_handbook_options',function(){
        $options = array(
            'student_handbook_default_banner_image' => array(
                'type'          => 'image',
                'label'         => 'Default Image for Student Handbooks',
                'label_button'  => 'Upload Default Image',
                'description'   => 'This is the background image for the thin banner of the Student Handbook',
            ),
        );
        cgs_render_admin_option('UCF Student Handbook Options', $options );
    });
    add_options_page('Site Wide Theme Settings','Site Wide Theme Settings','manage_options','theme_options',function(){
        $options = array(
            'SiteWideAlert' => array(
                'type'          => 'textarea',
                'description'   => 'Notification for UCF emergency alert. (Hurricanes, Pandemics, etc) Displays at the top of every page under the UCF main bar.',
            ),
        );
        cgs_render_admin_option('UCF Student Handbook Options', $options );
    });
    add_options_page('UCF Events Options','UCF Events Options','manage_options','event_options',function(){
        $options = array(
            'main-site-number-of-events-to-display' => array(
                'type'          => 'text',
                'description'   => 'Number of events to display on front page',
                'size'          => 10,
            ),
        );
        cgs_render_admin_option('UCF Events Options', $options );
    });
    add_options_page('UCF Profile Options','UCF Profile Options','manage_options','profile_options',function(){
        $options = array(
            'profiles_use_default_image' => array(
                'type' => 'checkbox',
                'label' => 'Use a default Profile Picture:',
            ),
            'profile_default_image' => array(
                'type' => 'image',
                'label' => 'Default Profile Image:',
                'label_button' => 'Upload Default Image',
            ),
        );
        cgs_render_admin_option('UCF Profile Options', $options );
    });
    add_options_page('UCF Staff Profile Options','UCF Staff Profile Options','manage_options','staff_profile_options',function(){
        $options = array(
            'staff_profile_default_image' => array(
                'type' => 'image',
                'label' => 'Default Staff Profile Image:',
                'label_button' => 'Upload Default Image',
            ),
        );
        cgs_render_admin_option('UCF Staff Profile Options', $options );
    });
});

add_action( 'after_setup_theme', function() {
    register_nav_menu( 'primary',           'Primary Menu' );
    register_nav_menu( 'primary-research',  'Primary Research Menu' );
	register_nav_menu( 'primary-flat',      'Primary Menu Flat ' );
    register_nav_menu( 'footer-modules',    'Footer Modules' );
    register_nav_menu( 'research-footer-resources',  'Footer Research Resources' );
    register_nav_menu( 'footer-resources',  'Footer Resources' );
    register_nav_menu( 'footer-social',     'Footer Social' );
});


function find_all_terms($tax){
	
	$term_query = new WP_Term_Query(
		array(
			'taxonomy' => $tax,
			'hide_empty' => false
	));

	$data = array();

	if(count($term_query->terms)!=0){
		$data = transverse_node($term_query->terms, 0);
	}

  	return $data;
}

function transverse_node(array &$tax_array, $parent = 0) {
    $results = array();

    foreach($tax_array as &$current_tax){
        if($current_tax->parent == $parent){
            
            $children = transverse_node($tax_array, $current_tax->term_id);
            
            if($children){
                $current_tax->children = $children;
            }

            array_push($results,$current_tax);
            unset($current_tax);
        }
    }
    return $results;
}

add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="btn btn-primary btn-sm"';
}

add_action('customize_register','gs_customize_register');


/***
 * This is a soft copy of the wp_nav_menu function to return sorted, and properly filtered navigation items for paging based on menus.
 * @param array $args
 * @return mixed
 */
function gs_custom_nav_menu_items_sorted( $args = array() ) {
	static $menu_id_slugs = array();

	$defaults = array( 'menu' => '', 'container' => 'div', 'container_class' => '', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
		'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', 'item_spacing' => 'preserve',
		'depth' => 0, 'walker' => '', 'theme_location' => '' );

	$args = wp_parse_args( $args, $defaults );

	if ( ! in_array( $args['item_spacing'], array( 'preserve', 'discard' ), true ) ) {
		// invalid value, fall back to default.
		$args['item_spacing'] = $defaults['item_spacing'];
	}

	/**
	 * Filters the arguments used to display a navigation menu.
	 *
	 * @since 3.0.0
	 *
	 * @see wp_nav_menu()
	 *
	 * @param array $args Array of wp_nav_menu() arguments.
	 */
	$args = apply_filters( 'wp_nav_menu_args', $args );
	$args = (object) $args;

	/**
	 * Filters whether to short-circuit the wp_nav_menu() output.
	 *
	 * Returning a non-null value to the filter will short-circuit
	 * wp_nav_menu(), echoing that value if $args->echo is true,
	 * returning that value otherwise.
	 *
	 * @since 3.9.0
	 *
	 * @see wp_nav_menu()
	 *
	 * @param string|null $output Nav menu output to short-circuit with. Default null.
	 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
	 */
	$nav_menu = apply_filters( 'pre_wp_nav_menu', null, $args );

	if ( null !== $nav_menu ) {
		if ( $args->echo ) {
			echo $nav_menu;
			return;
		}

		return $nav_menu;
	}

	// Get the nav menu based on the requested menu
	$menu = wp_get_nav_menu_object( $args->menu );

	// Get the nav menu based on the theme_location
	if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
		$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );

	// get the first menu that has items if we still can't find a menu
	if ( ! $menu && !$args->theme_location ) {
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu_maybe ) {
			if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) {
				$menu = $menu_maybe;
				break;
			}
		}
	}

	if ( empty( $args->menu ) ) {
		$args->menu = $menu;
	}

	// If the menu exists, get its items.
	if ( $menu && ! is_wp_error($menu) && !isset($menu_items) )
		$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

	/*
     * If no menu was found:
     *  - Fall back (if one was specified), or bail.
     *
     * If no menu items were found:
     *  - Fall back, but only if no theme location was specified.
     *  - Otherwise, bail.
     */
	if ( ( !$menu || is_wp_error($menu) || ( isset($menu_items) && empty($menu_items) && !$args->theme_location ) )
		&& isset( $args->fallback_cb ) && $args->fallback_cb && is_callable( $args->fallback_cb ) )
		return call_user_func( $args->fallback_cb, (array) $args );

	if ( ! $menu || is_wp_error( $menu ) )
		return false;

	$nav_menu = $items = '';

	$show_container = false;
	if ( $args->container ) {
		/**
		 * Filters the list of HTML tags that are valid for use as menu containers.
		 *
		 * @since 3.0.0
		 *
		 * @param array $tags The acceptable HTML tags for use as menu containers.
		 *                    Default is array containing 'div' and 'nav'.
		 */
		$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
		if ( is_string( $args->container ) && in_array( $args->container, $allowed_tags ) ) {
			$show_container = true;
			$class = $args->container_class ? ' class="' . esc_attr( $args->container_class ) . '"' : ' class="menu-'. $menu->slug .'-container"';
			$id = $args->container_id ? ' id="' . esc_attr( $args->container_id ) . '"' : '';
			$nav_menu .= '<'. $args->container . $id . $class . '>';
		}
	}

	// Set up the $menu_item variables
	_wp_menu_item_classes_by_context( $menu_items );

	$sorted_menu_items = $menu_items_with_children = array();
	foreach ( (array) $menu_items as $menu_item ) {
		$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
		if ( $menu_item->menu_item_parent )
			$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
	}

	// Add the menu-item-has-children class where applicable
	if ( $menu_items_with_children ) {
		foreach ( $sorted_menu_items as &$menu_item ) {
			if ( isset( $menu_items_with_children[ $menu_item->ID ] ) )
				$menu_item->classes[] = 'menu-item-has-children';
		}
	}

	unset( $menu_items, $menu_item );

	/**
	 * Filters the sorted list of menu item objects before generating the menu's HTML.
	 *
	 * @since 3.1.0
	 *
	 * @param array    $sorted_menu_items The menu items, sorted by each menu item's menu order.
	 * @param stdClass $args              An object containing wp_nav_menu() arguments.
	 */
	return $sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, $args );
}

function gs_custom_menu_pagination( $args = array() ) {
	$text_length = 30;

	$menu_items = gs_custom_nav_menu_items_sorted( $args );

	$_previous_item = null;
	$_current_item = null;
	$_next_item = null;

	if ( $menu_items ) {
		foreach ( $menu_items as &$menu_item ) {
			if( isset( $_current_item ) && !isset( $_next_item ) ) {
				$_next_item = $menu_item;
				break;
			}

			if( $menu_item->current )
				$_current_item = $menu_item;

			if( !isset( $_current_item ) )
				$_previous_item = $menu_item;
		}
	}

	$p = array(
		'title' => '',
		'title_short' => '',
		'url' => '#',
		'disabled' => 'disabled',
		'visibility' => 'invisible'
	);
	$n = array(
		'title' => '',
		'title_short' => '',
		'url' => '#',
		'disabled' => 'disabled',
		'visibility' => 'invisible'
	);
	if( isset( $_previous_item ) ) {
		$p['title'] = $_previous_item->title;
		$p['title_short'] = mb_strimwidth( $_previous_item->title, 0, $text_length, '...' );
		$p['url'] = $_previous_item->url;
		$p['disabled'] = '';
		$p['visibility'] = 'visible';
	}
	if( isset( $_next_item ) ) {
		$n['title'] = $_next_item->title;
		$n['title_short'] = mb_strimwidth( $_next_item->title, 0, $text_length, '...' );
		$n['url'] = $_next_item->url;
		$n['disabled'] = '';
		$n['visibility'] = 'visible';
	}
	return array(
		'previous' => $p,
		'next' => $n
	);
}

// Add the filter
add_filter('upload_mimes', function ( $mimes ) {
	// Images
	$mimes['bmp'] 	= 'image/bmp';
	$mimes['svg']  	= 'image/svg+xml';
	$mimes['svgz'] 	= 'image/svg+xml';

	// Power point
	$mimes['pptx'] 	= 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	$mimes['ppt'] 	= 'application/vnd.ms-powerpoint';

	// Word docs
	$mimes['doc'] 	= 'application/msword';
	$mimes['docx'] 	= 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

	// Excel
	$mimes['xls'] 	= 'application/vnd.ms-excel';
	$mimes['xlsx'] 	= 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	$mimes['csv'] 	= 'text/csv';
	$mimes['tsv'] 	= 'text/tsv';

	// Zip Files
	$mimes['zip'] 	= 'application/zip';
	$mimes['rar'] 	= 'application/x-rar-compressed';
	$mimes['tar'] 	= 'application/x-tar';
	$mimes['gz'] 	= 'application/x-gzip';
	$mimes['gzip'] 	= 'application/x-gzip';

	$mimes['tiff'] 	= 'image/tiff';
	$mimes['tif'] 	= 'image/tiff';
	$mimes['psd'] 	= 'image/vnd.adobe.photoshop';
	$mimes['ai'] 	= 'application/postscript';
	$mimes['indd'] 	= 'application/x-indesign'; // not official, but might still work
	$mimes['eps'] 	= 'application/postscript';
	$mimes['rtf'] 	= 'application/rtf';
	$mimes['txt'] 	= 'text/plain';
	$mimes['wav'] 	= 'audio/x-wav';
	$mimes['xml'] 	= 'application/xml';
	$mimes['flv'] 	= 'video/x-flv';
	$mimes['swf'] 	= 'application/x-shockwave-flash';
	$mimes['vcf'] 	= 'text/x-vcard';
	$mimes['html'] 	= 'text/html';
	$mimes['htm'] 	= 'text/html';
	$mimes['css'] 	= 'text/css';
	$mimes['js'] 	= 'application/javascript';
	$mimes['ico'] 	= 'image/x-icon';
	$mimes['otf'] 	= 'application/x-font-otf';
	$mimes['ttf'] 	= 'application/x-font-ttf';
	$mimes['woff'] 	= 'application/x-font-woff';
	$mimes['ics'] 	= 'text/calendar';

	return $mimes;
}, 1, 1 );

// If your updating custom post types refresh this really quick
flush_rewrite_rules(); // Test