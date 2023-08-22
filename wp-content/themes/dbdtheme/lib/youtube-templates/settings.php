<div class="wrap">
  <form role="presentation" action="options.php" method="post">
    <?php
      // output security fields
      settings_fields('dbd_options'); 
      // output setting sections
      do_settings_sections('dbd_admin_menu');
      // submit button
      submit_button();
    ?>
  </form>
  <hr>
</div>