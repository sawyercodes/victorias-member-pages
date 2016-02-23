jQuery(document).ready(function($){
  alert("hello world");

  var mediaUploader;

  $('#meda-uploader-button').click(function(e) {
    e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    // Extend the wp.media object
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Media',
      button: {
      text: 'Choose Media'
    }, multiple: false });

    // When a file is selected, grab the URL and set it as the text field's value
    mediaUploader.on('select', function() {
      attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#image-url').val(attachment.url);
    });
    // Open the uploader dialog
    mediaUploader.open();
  });

});


// jQuery(document).ready(function($){

//     // Instantiates the variable that holds the media library frame.
//     var meta_image_frame;

//     // Runs when the image button is clicked.
//     $('media-uploader-button').click(function(e){

//         // Prevents the default action from occuring.
//         e.preventDefault();

//         // If the frame already exists, re-open it.
//         if ( meta_image_frame ) {
//             meta_image_frame.open();
//             return;
//         }

//         // Sets up the media library frame
//         meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
//             title: meta_image.title,
//             button: { text:  meta_image.button },
//             library: { type: 'image' }
//         });

//         // Runs when an image is selected.
//         meta_image_frame.on('select', function(){

//             return false;

//             // Grabs the attachment selection and creates a JSON representation of the model.
//             var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

//             // Sends the attachment URL to our custom image input field.
//             $('#meta-image').val(media_attachment.url);
//         });

//         // Opens the media library frame.
//         wp.media.editor.open();
//     });
// });