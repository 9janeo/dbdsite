<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<?php // Vortex_Admin::display_status();
?>
<?php if ($type == 'plugin') : ?>
	<div class="updated" id="vortex_setup_prompt">
		<form name="vortex_activate" action="<?php echo esc_url(Vortex_Admin::get_page_url()); ?>" method="POST">
			<div class="vortex_activate">
				<div class="aa_a">A</div>
				<div class="aa_button_container">
					<div class="aa_button_border">
						<input type="submit" class="aa_button" value="<?php esc_attr_e('Set up your Vortex account', 'cc-vortex'); ?>" />
					</div>
				</div>
				<div class="aa_description"><?php _e('<strong>Almost done</strong> - configure Vortex and say goodbye to a disconnected web experience', 'cc-vortex'); ?></div>
			</div>
		</form>
	</div>
<?php elseif ($type == 'notice') : ?>
	<div class="vortex-alert vortex-critical">
		<h3 class="vortex-key-status failed"><?php echo $notice_header; ?></h3>
		<p class="vortex-description">
			<?php echo $notice_text; ?>
		</p>
	</div>
<?php elseif ($type == 'intro_message') : ?>
	<div class="vortex-alert vortex-critical">
		<h3 class="vortex-key-status failed"><?php echo $notice_header; ?></h3>
		<p class="vortex-description">
			<?php echo $notice_text; ?>
		</p>
	</div>
<?php elseif ($type == 'missing-functions') : ?>
	<div class="vortex-alert vortex-critical">
		<h3 class="vortex-key-status failed"><?php esc_html_e('Network functions are disabled.', 'cc-vortex'); ?></h3>
		<p class="vortex-description"><?php printf(__('Your web host or server administrator has disabled PHP&#8217;s <code>gethostbynamel</code> function.  <strong>Vortex cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about Vortex&#8217;s system requirements</a>.', 'cc-vortex'), 'https://blog.vortex.com/vortex-hosting-faq/'); ?></p>
	</div>
<?php elseif ($type == 'no-sub') : ?>
	<div class="vortex-alert vortex-critical">
		<h3 class="vortex-key-status failed"><?php esc_html_e('You don&#8217;t have an Vortex plan.', 'cc-vortex'); ?></h3>
		<p class="vortex-description">
			<?php printf(__('In 2012, Vortex began using subscription plans for all accounts (even free ones). A plan has not been assigned to your account, and we&#8217;d appreciate it if you&#8217;d <a href="%s" target="_blank">sign into your account</a> and choose one.', 'cc-vortex'), 'https://vortex.com/account/upgrade/'); ?>
			<br /><br />
			<?php printf(__('Please <a href="%s" target="_blank">contact our support team</a> with any questions.', 'cc-vortex'), 'https://vortex.com/contact/'); ?>
		</p>
	</div>
<?php endif; ?>