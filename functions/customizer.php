<?php

function gs_customize_register( $wp_customize ) {
    
    // Remove the wordpress sections we don't need
    $wp_customize->remove_section( 'themes' );
    $wp_customize->remove_section( 'title_tagline');
    $wp_customize->remove_section( 'colors');
    $wp_customize->remove_section( 'header_image');
    $wp_customize->remove_section( 'background_image');
    $wp_customize->remove_section( 'static_front_page');
    $wp_customize->remove_section( 'static_front_page');
    $wp_customize->remove_section( 'custom_css');

    $wp_customize->add_section('cgs_options', array(
            'title' => 'CGS Custom Options',
            'description' => 'Options for the college main site.',
            'priority' => 2,
            'capability' => 'edit_theme_options',
        )
    );


	$wp_customize->add_setting('CollegeGraduateHours', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateHours',array(
		'label' => 'Office Hours',
		'section' => 'cgs_options',
		'type' => 'textarea'
	));
	$wp_customize->add_setting('CollegeGraduateHoursShort', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateHoursShort',array(
		'label' => 'Office Hours Simplified',
		'section' => 'cgs_options',
		'type' => 'textarea'
	));
	$wp_customize->add_setting('CollegeGraduateOfficeLocation', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateOfficeLocation',array(
		'label' => 'Office Location',
		'section' => 'cgs_options',
		'type' => 'textarea'
	));
	$wp_customize->add_setting('CollegeGraduateMailingAddress', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateMailingAddress',array(
		'label' => 'Mailing Address',
		'section' => 'cgs_options',
		'type' => 'textarea'
	));
	$wp_customize->add_setting('CollegeGraduatePhone', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduatePhone',array(
		'label' => 'Phone',
		'section' => 'cgs_options',
		'type' => 'input'
	));
	$wp_customize->add_setting('CollegeGraduateFax', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateFax',array(
		'label' => 'Fax',
		'section' => 'cgs_options',
		'type' => 'input'
	));
	$wp_customize->add_setting('CollegeGraduateApplicationEmail', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateApplicationEmail',array(
		'label' => 'Application Support Email',
		'section' => 'cgs_options',
		'type' => 'input'
	));
	$wp_customize->add_setting('CollegeGraduateWebmasterEmail', array(
		'default' => ''
	));
	$wp_customize->add_control('CollegeGraduateWebmasterEmail',array(
		'label' => 'Webmaster Email',
		'section' => 'cgs_options',
		'type' => 'input'
	));
}

function cgs_render_admin_option( $option_group_name, $options ) {
    static $form_input_image_id_count = 0;
    $option_keys = array_keys( $options ); // Stores the keys to options to be saved, is edited depending on option type

    ?>
    <div class="wrap">
        <h2><?= $option_group_name ?></h2>
        <form method="post" action="options.php">
            <?php foreach ( $options as $name => $option ) {
                if( $option['type'] == 'text' ):
                    $option_value = get_option( $name );
                    $option_size = ( isset( $option['size'] ) )? "size=\"${option['size']}\"": '';
                    echo "<p><input type=\"text\" name=\"$name\" $option_size value=\"". esc_attr( $option_value ) ."\"></p>";
                    if( isset( $option['description'] ) ):
                        echo "<p class=\"description\">${option['description']}</p>";
                    endif;
                endif;
                if( $option['type'] == 'textarea' ):
                    $option_value = get_option( $name );
                    echo "<div class=\"form-field term-description-wrap\">";
	                echo "<textarea name='$name' rows='10' cols='40'>" . esc_textarea( $option_value ) . "</textarea>";
                    if( isset( $option['description'] ) ):
                        echo "<p class=\"description\">${option['description']}</p>";
                    endif;
                    echo "</div>";
                endif;
                if( $option['type'] == 'image' ):
                    $form_input_image_id_count++;
                    $label          = ( isset( $option['label'] ) )? $option['label']: 'Upload an Image';
                    $label_button   = ( isset( $option['label_button'] ) )? $option['label_button']: 'Upload';

                    $image_name_src = $name . '_src';
                    $image_name_id  = $name . '_id';

                    // Add the hidden inputs to the new image options key
                    array_push( $option_keys, $image_name_src );
                    array_push( $option_keys, $image_name_id );

                    // Remove the base option name as it isn't actually an option only ID and SRC are stored
                    $option_keys = array_diff( $option_keys, array( $name ) );

                    $image_value_src = get_option( $image_name_src );
                    $image_value_id  = get_option( $image_name_id );

                    $image_holder_id = "image-holder-$form_input_image_id_count";
                    ?>
                    <div id="<?= $image_holder_id ?>">
                        <p><?= $label ?></p>
                        <input type="button" class="button picture-upload-button" value="<?= $label_button ?>">
                        <?php if( isset( $option['description'] ) ): ?>
                            <p class="description"><?= $option['description'] ?></p>
                        <?php endif; ?>
                        <div class="image-preview-wrapper">
                            <img id="attachment_preview" class="picture-preview" src="<?= $image_value_src ?>" style="max-width: 600px;">
                        </div>
                        <input type="hidden" class="picture-src" name="<?= $image_name_src ?>" value="<?= $image_value_src ?>">
                        <input type="hidden" class="picture-id" name="<?= $image_name_id ?>" value="<?= $image_value_id ?>">
                    </div>
                    <script> $(function(){ pictureFramework( $( '#<?= $image_holder_id ?>' ) ); }); </script>
                <?php
                endif;
                if( $option['type'] == 'checkbox' ):
                $option_value = get_option( $name );
                ?>
                    <label>
                        <?= $option['label'] ?>
                        <input type="checkbox" name="<?= $name ?>" <?= checked( $option_value, 'on', false ) ?>>
                    </label>
                    <?php if( isset( $option['description'] ) ): ?>
                    <p class="description"><?= $option['description'] ?></p>
                <?php endif; ?>
                <?php
                endif;
            } ?>
            <?php wp_nonce_field('update-options') ?>
            <p><input type="submit" name="Submit" class="button" value="Save"></p>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options" value="<?= implode( ",", $option_keys ) ?>" />
        </form>
    </div>
    <?php
}
// USED IN THE GRADUATE MAIN FOOTER
function render_social_menu( $location ) {
    if ( ($location) && ($locations = get_nav_menu_locations()) && isset($locations[$location]) ) {
        $menu = get_term( $locations[$location], 'nav_menu' );
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menus_list = '';

        foreach( $menu_items as $menu_item ) {
            $menus_list .= build_social_menu_item( $menu_item->title . '.svg', $menu_item->title . ' Social Icon', $menu_item->url );
        }

        echo $menus_list;
    }
}


function build_social_menu_item ( $file, $alt, $url ) {
    $root_path = get_template_directory_uri();
    $social_icons_path = $root_path . '/images/social-icons/';
    $desaturated_path = $social_icons_path . 'desaturated/' . $file;
    $saturated_path = $social_icons_path . 'saturated/' . $file;

    $o = '<li>';
    $o .= "<a class='swap-image-hover' href='$url'>";
    $o .= "<img src='$desaturated_path' alt='$alt'>";
    $o .= "<img src='$saturated_path' alt='$alt'>";
    $o .= '</a>';
    $o .= '</li>';

    return $o;
}
