<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div class="wrap" id="vortex-plugin-container">
	<div class="vortex-masthead card">
		<div class="vortex-masthead__inside-container">
			<div class="vortex-masthead__logo-container">
				<img class="vortex-masthead__logo" src="<?php echo esc_url(plugins_url('../../assets/images/vortex_logo.png', __FILE__)); ?>" alt="Clear Cut Vortex" />
			</div>
			<div class="vortex-box">
				<?php Vortex::view('title'); ?>
			</div>
		</div>
	</div>
	<div class="vortex-lower">
		<?php Vortex_Admin::display_status(); ?>
		<div class="vortex-boxes">
			<?php

			if (Vortex::predefined_api_key()) {
				Vortex::view('predefined');
				echo "<h3>Congratulations you are using a predefined user key</h3>";
			}

			?>
		</div>
		<?php Vortex::view('dashboard') ?>
	</div>
</div>