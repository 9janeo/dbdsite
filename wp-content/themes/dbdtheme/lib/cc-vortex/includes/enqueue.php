<?php

function ccv_enqueue()
{
	wp_register_style(
		'ccv_admin_styles',
		get_theme_file_uri('/assets/css/admin_styles.css'),
	);
}
