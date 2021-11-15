if(!$)$=jQuery;
/**
 *
 * @param $template JQuery parent element
 * @param data
 * @returns {*}
 */
function pictureFramework( $template, data ) {
    var frame,
        $attachment_src         = $template.find(".picture-src"),
        $attachment_id          = $template.find(".picture-id"),
        $attachment_preview     = $template.find(".picture-preview"),
        $attachment_image_btn   = $template.find(".picture-upload-button");

    if( data && data.src ) {
        $attachment_src.val( data.src );
        $attachment_preview.attr( 'src', data.src );
    }
    if( data && data.id ) $attachment_id.val( data.id );

    $attachment_image_btn.on( 'click', function( e ){
        e.preventDefault();

        if( frame ) {
            frame.open();
            return;
        }

        frame = wp.media({
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();

            $attachment_id.val(             attachment.id  );
            $attachment_src.val(            attachment.url );
            $attachment_preview.attr('src', attachment.url );
        });

        frame.open();
    });
    $attachment_preview.on('click', function( e ) {
        e.preventDefault();

        $attachment_id.val( '' );
        $attachment_src.val( '' );
        $attachment_preview.attr('src', '' );
    });
    return $template;
}