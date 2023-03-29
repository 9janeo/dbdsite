<div class="wrap">
	<div class="vortex-box">
		<form role="presentation" action="options.php" method="post">
			<?php
			echo (VORTEX_PLUGIN_NAME);
			echo ('<br>');
			echo (plugin_basename(__FILE__));
			echo ('<br>');
			echo (plugin_basename(plugin_dir_path(__FILE__)));
			// output security fields
			// settings_fields('vortex_options');

			// output setting sections
			// do_settings_sections('vortex');

			// submit button
			// submit_button();

			?>

		</form>
	</div>
</div>