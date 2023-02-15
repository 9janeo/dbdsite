<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta bg-secondary"><?php understrap_posted_on(); ?></div>
	</header>

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">
		<?php
			the_content();
			understrap_link_pages();
		?>
	</div>

	<footer class="entry-footer">
		<?php
			$meta = $post->video_info;
			$url = get_field('video_link');
			$vid_exists = metadata_exists('post', $post->ID, 'video_info');
			if ($vid_exists) :
				$yt_meta = get_post_meta( $post->ID ,'video_info')[0];
				$thumb = $yt_meta->thumbnails->high->url;
				$title = $yt_meta->title;
				$desc = $yt_meta->description;
				$url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';   
				$desc= preg_replace($url_pattern, '<a href="$0" target="_blank">$0</a>', $desc);
			?>
			<div class="yt-meta card">
				<a href="<?php echo get_field('video_link')?>" target="_blank"><img class="card-img-top" src="<?php echo $thumb ?>" /></a>
				<div class="card-body">
					<h3 class="card-title"><a href="<?php echo get_field('video_link')?>" target="_blank"><?php echo $title ?></a></h3>
					<p class="card-text"><?php echo apply_filters('the_content', $desc) ?></p>
					<p class="small card-text pull-left">Published: <?php echo date("Y-m-d", strtotime($yt_meta->publishedAt)); ?></p>
				</div>
			</div>
		<?php endif; ?>

		<?php // understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
