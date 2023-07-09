<div class="wrap">
	<div class="vortex-box">
		<form role="presentation" action="options.php" method="post">
			<?php
			// output security fields
			settings_fields('vortex_options');

			// output setting sections
			do_settings_sections('vortex_admin_menu');

			// submit button
			submit_button();

			?>

		</form>
	</div>
</div>