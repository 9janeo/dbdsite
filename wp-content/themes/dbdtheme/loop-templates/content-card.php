<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class('card'); ?> id="post-<?php the_ID(); ?>">
	<?php 
		// Check for YT thumbnail if vid added to post
		// To do: add this as featured image when video link added
		$vid_exists = metadata_exists('post', get_the_ID(), 'video_info');		
		if ($vid_exists) :
			$yt_meta = get_post_meta( get_the_ID() ,'video_info')[0];
			$yt_thumb = $yt_meta->thumbnails->high->url;
		else:
			$yt_thumb = false;
		endif;
		
		// Load default fallback post thumbnail
		$cover_id = get_field('post_card_cover', 'option');
		$cover_image = ($yt_thumb)? $yt_thumb : wp_get_attachment_url( $cover_id );

		$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
		$has_thumb = (has_post_thumbnail())? true : false;
		$thumb = $feat_image_url? $feat_image_url : $cover_image;
	?>
	<div class="card-img-top" style="background-image: url('<?php echo $thumb ?>');">
 		<?php
			if($vid_exists):
		?> <img width="800" height="800" src="<?php echo $yt_thumb ?>" /> <?php
			elseif($has_thumb):
				echo get_the_post_thumbnail( $post->ID, 'small' );
			else:
				echo wp_get_attachment_image($cover_id, 'small');
			endif;
		?>
	</div>
		
	<div class="card-body">
		<header class="entry-header">
			<?php
				the_title(
					sprintf( '<h2 class="entry-title card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
					'</a></h2>'
				);
			?>
		</header>
		<div class="entry-content card-text">
			<?php
				the_excerpt();
				understrap_link_pages();
			?>
		</div>		
	</div>
	<footer class="entry-footer card-footer">
		<?php if ( 'post' === get_post_type() ) : ?>
			<small class="entry-meta card-text text-muted"><?php understrap_posted_on(); ?></small>
		<?php endif; ?>
		<?php // understrap_entry_footer(); ?>
	</footer>
</article>
