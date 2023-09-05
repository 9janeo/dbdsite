// Add your custom JS here.

(function ($) {
  'use strict';
  jQuery(function ($) {
    $('[data-bs-toggle="popover"]').popover();
  });

  // API Actions
  $(document).on("click", '.yt_sync', function (e) {
    e.preventDefault();
    var channel = $(this).attr("channel_id");
    var data = { action: 'api_actions', channel: channel };
    jQuery.post(ajaxurl, data, function (response) {
      console.log("Syncing: " + channel);
    });
  });

})(jQuery);

