if(!$)$=jQuery;
var meta = {
    quickLinks: {
        names: {
            text: "meta-quicklink-text[]",
            url: "meta-quicklink-url[]"
        },
        placeholders: {
            text: "Enter the Quicklink\'s display text here",
            url: "Enter the Quicklink\'s url text here"
        }
    },
    spotlight:{
        names: {
            text: "meta-spotlight-text[]",
            url: "meta-spotlight-url[]",
            image: "meta-spotlight-image[]"
        },
        placeholders: {
            text: "Enter the Spotlight\'s display text here",
            url: "Enter the Spotlight\'s url text here",
            image: "Enter the Spotlight\'s image here"
        }
    }

};

/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($){
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;

    function imageManager( elem ) {

        meta_image_frame = wp.media.frames.file_frame = wp.media({ // Sets up the media library frame
            title: 'Choose or Upload a File',
            button: { text:  'Use this File' },
            multiple: false
        });

        // Runs when an image is selected.
        meta_image_frame.on('select', function(){
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON(); // Grabs the attachment selection and creates a JSON representation of the model.

            $( elem ).val( media_attachment.url ); // Sends the attachment URL to our custom image input field.
        });

        meta_image_frame.open(); // Opens the media library frame.
    }


    function JQ_click_add_file_button(e) {
        e.preventDefault();

        var parent = $(this).parent();

        var inputField = parent.find( $("input.wp-input-file-url") );

        imageManager( inputField );
    }
    function JQ_click_remove_file_button(e) {
        e.preventDefault();

        var parent = $(this).parent().remove();
    }

    // Runs when the image button is clicked.
    $('.wp-file-button').click( JQ_click_add_file_button );
    $('.wp-file-remove').click( JQ_click_remove_file_button );
    $('.wp-add-another-file-field').click(function(e){
        e.preventDefault();

        var parent = $(this).parent();

        var holder = parent.children()[0];

        var button = $('<button>').text('Upload file').addClass('wp-file-button').click(JQ_click_add_file_button);
        var removeButton = $("<button class='wp-file-remove'>Remove</button>").click(JQ_click_remove_file_button);


        var new_field = $('<div>')
            .append("<input class='wp-input-file-name' name='file_name[]' type='text' placeholder='Name'>")
            .append("<input class='wp-input-file-url' name='file_url[]' type='text' placeholder='URL'>")
            .append(button)
            .append(removeButton)
            .appendTo(holder);

        console.log( new_field );
    });
});