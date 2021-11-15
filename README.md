# Corona Site Theme
[![forthebadge](https://forthebadge.com/images/badges/built-with-wordpress.svg)](https://forthebadge.com)
[![forthebadge](https://forthebadge.com/images/badges/uses-git.svg)](https://forthebadge.com)
[![forthebadge](https://forthebadge.com/images/badges/powered-by-electricity.svg)](https://forthebadge.com)

**Website:** https://corona.research.ucf.edu/

## 🛠️ Building and Development
This will install the latest npm packages
```
npm i
```

To install gulp globally
```
npm i gulp -g 
```

To run gulp build process use run gulp

```cmd
gulp
```

The Graduate site uses [lesscss](http://lesscss.org/) for the styling and templating. This requires the preprocessing build our less files into webstandard css.

Create a gulpconfig.js file based on the provided gulpconfig.sample.js

Run the following command:
```
gulp compile-less
```
Alternatively, running the following command will watch all less files, and build the style.css file any time it detects a change.
```
gulp watch-less
```

## 🚀 Installation 
Grab the latest WordPress install, drop this theme into the wp-themes folder.

### Plugins used
* **Gravity Forms** by by rocketgenius
* **Permalinks Customizer** by YAS Global Team
* **Redirection** by John Godley
* **UberMenu 3 - The Ultimate WordPress Mega Menu** by Chris Mavricos, SevenSpark
* **WP Cerber Security, Antispam & Malware Scan** by Gregory

## Decks
Events
Reads events from the UCF events calender.
[Upcoming Graduate Studies Events](https://events.ucf.edu/calendar/2529/college-of-graduate-studies-events/upcoming/)
- [Events Documentation](https://events.ucf.edu/help/)
```
[deck_events
    class=""
    style=""
    title=""
    background-image=""
    feed=""
    show_number=""
]
```
Defaults
```
class = ''
style = ''
title = 'UPCOMING EVENTS'
background-image = '//www.ucf.edu/wp-content/uploads/2017/08/ucf-planerarium-space-events_12717203-1.jpg'
feed = 'https://events.ucf.edu/calendar/2529/college-of-graduate-studies-events/upcoming/'
show_number = 10
```
Default Number of events can be set in the 

Featured Content

Herosplash

News

Room Reservation

## Shortcodes

### Breakout
Breakout allows content to fill the whole width of the page, this is great for wells, inverted sections, full width images with inner content.

```
[breakout class="" style=""]
This will appear to the left side of a page screen
[/breakout]
```

### Blockquote

```
[blockquote class="" style=""]
The quote here
[/blockquote]
```

### Custom Admin Functions
#### cgs_render_admin_option
Accepts:
* Option_Group_Name
* Options Array of option
    * option_name => array of attributes

Option types and their supporting attributes
* checkbox
    * label
    * description
* image
    * label
    * label_button 
    * description
* text
    * size
    * description

Example usage:
```
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
```

### Custom Admin Javascript Code
#### pictureFramework
Accepts a Jquery element, and initializes all of the controls for the upload process.
* Hooks the wordpress image upload to the upload button.
* Applies the id, and url to hidden elements, when an image is selected.
* Clicking the preview image removes the image.
* Data can be passed via the second parameter to initialize the upload to existing data.
``` Javascript
let data = {
    id: '',
    src: ''
};
pictureFramework( $template, data );
```

HTML template format:
```$html
<div id="pictureUpload">
    <p>Upload a Picture</p>
    <input type="button" class="button picture-upload-button">
    <p class="description">Uploads a picture for template use</p>
    <div class="image-preview-wrapper">
        <img id="attachment_preview" class="picture-preview" src="[some url]" style="max-width: 600px;">
    </div>
    <input type="hidden" class="picture-src" name="upload_image_src" value="">
    <input type="hidden" class="picture-id" name="upload_image_id" value="">
</div>
```
