// Add your custom JS here.

(function($) {
  'use strict';
  jQuery(function($) {
    $('[data-bs-toggle="popover"]').popover();
  });

  // API Actions
  // ajaxurl = '<?php echo admin_url('admin.php?action=wp_ajax_api_actions')';
  $('#yt_sync').on("click", function(e){
    e.preventDefault();
    var channel = $(this).attr("channel_id");
    var data = { action: 'api_actions', channel: channel };
    jQuery.post(ajaxurl, data, function(response){
      console.log("YT_sync clicked! For: " + channel);
      // alert(response);
      // alert('Got this from the server: ' + response);
    });
  });

})(jQuery);

