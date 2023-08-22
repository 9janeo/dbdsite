<?php

function ccv_social_post_type()
{
	$labels = array(
		'name'                  => _x('Social Posts', 'Post type general name', 'cc-vortex'),
		'singular_name'         => _x('Social Post', 'Post type singular name', 'cc-vortex'),
		'menu_name'             => _x('Social Posts', 'Admin Menu text', 'cc-vortex'),
		'name_admin_bar'        => _x('Social Post', 'Add New on Toolbar', 'cc-vortex'),
		'add_new'               => __('Add New', 'cc-vortex'),
		'add_new_item'          => __('Add New Social Post', 'cc-vortex'),
		'new_item'              => __('New Social Post', 'cc-vortex'),
		'edit_item'             => __('Edit Social Post', 'cc-vortex'),
		'view_item'             => __('View Social Post', 'cc-vortex'),
		'all_items'             => __('All Social Posts', 'cc-vortex'),
		'search_items'          => __('Search Social Posts', 'cc-vortex'),
		'parent_item_colon'     => __('Parent Social Posts:', 'cc-vortex'),
		'not_found'             => __('No books found.', 'cc-vortex'),
		'not_found_in_trash'    => __('No books found in Trash.', 'cc-vortex'),
		'featured_image'        => _x('Social Post Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'cc-vortex'),
		'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'cc-vortex'),
		'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'cc-vortex'),
		'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'cc-vortex'),
		'archives'              => _x('Social Post archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'cc-vortex'),
		'insert_into_item'      => _x('Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'cc-vortex'),
		'uploaded_to_this_item' => _x('Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'cc-vortex'),
		'filter_items_list'     => _x('Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'cc-vortex'),
		'items_list_navigation' => _x('Social Posts list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'cc-vortex'),
		'items_list'            => _x('Social Posts list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'cc-vortex'),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'book'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		'show_in_rest'		 => true,
		'description'		 => __('A custome post type for social posts', 'cc-vortex')
	);

	register_post_type('social-post', $args);
}
