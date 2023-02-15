<?php
/**
 * Right sidebar check
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

</div><!-- close the primary container -->

<?php
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );

if ( 'right' === $sidebar_pos || 'both' === $sidebar_pos ) {
	get_template_part( 'sidebar-templates/sidebar', 'right' );
}
