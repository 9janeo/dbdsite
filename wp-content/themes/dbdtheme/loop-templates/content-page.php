<?php
/**
 * Partial template for content in page.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php if(!(is_front_page())) : ?>
		<header class="entry-header page">
			<div class="banner" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'large' ); ?>)">
				<?php //echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
			</div>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<div class="entry-content">

		<?php
		the_content();
		understrap_link_pages();
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_edit_post_link(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

<?php 
	get_template_part('acf-templates/page', 'block');
?>