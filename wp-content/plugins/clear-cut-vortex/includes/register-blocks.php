<?php

function ccv_register_blocks()
{
	$blocks = [
		['name' => 'fancy-header'],
		['name' => 'search-form', 'options' => [
			'render_callback' => 'vortex_search_form_render_cb'
		]]
	];

	foreach ($blocks as $block) {
		register_block_type(
			VORTEX__PLUGIN_DIR . 'build/blocks/' . $block['name'],
			isset($block['options']) ? $block['options'] : []
		);
	}
}
