<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="vortex-plugin-container" class="wrap">
	<div class="vortex-masthead card">
		<div class="vortex-masthead__inside-container">
			<div class="vortex-masthead__logo-container">
				<img class="vortex-masthead__logo" src="<?php echo esc_url(plugins_url('../../assets/images/vortex_logo.png', __FILE__)); ?>" alt="Vortex" />
			</div>
		</div>
		<div class="vortex-box">
			<?php Vortex::view('title'); ?>
		</div>
	</div>
	<div class="vortex-lower">
		<?php Vortex_Admin::display_status(); ?>

		<?php if (!empty($notices)) { ?>
			<?php foreach ($notices as $notice) { ?>
				<?php Vortex::view('notice', $notice); ?>
			<?php } ?>
		<?php } ?>

		<?php
		// place social configuration forms here
		?>
		<form action="">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="channel_id">Channel ID</label></th>
						<td><input name="channelid" type="text" aria-describedby="youtube-channel-id" name="channel_id" id="channel_id" placeholder="YouTube channel id" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="channel_name">Channel Name</label></th>
						<td><input type="text" name="channel_name" id="channel_name" class="regular-text"></td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" class="button button-primary" value="save">
							<input type="reset" name="reset" class="button button-danger" value="clear">
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div> <!-- close vortex-card -->
</div>