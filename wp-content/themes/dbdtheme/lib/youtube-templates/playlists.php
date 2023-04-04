<?php

/**
 * Single playlist partial template
 *
 */
$playlist = $args['playlist'];
$id = $playlist->id;
$etag = $playlist->etag;
$title = $playlist->snippet->title;
$description = $playlist->snippet->description;
$thumbnails = $playlist->snippet->thumbnails;
$itemCount =  $playlist->contentDetails->itemCount;
$videos = $args['videos'];
if (isset($videos[0])) {
  $indexVid = $videos[0]->snippet->resourceId->videoId;
  $playlistUrl = 'https://www.youtube.com/watch?v=' . $indexVid . '&list=' . $id;
}
// https://www.youtube.com/watch?v=DyOm5BX2GZk&list=PLgHIZMXekZto-3q1_Gb6HwE9fzfy3zF9g&ab_channel=DISBYDEM

?>
<div class="card">
  <div class="card-header">
    <h5 class="card-title"><?= $title ?></h5>
    <p class="card-text"><?php echo $itemCount; ?> Videos</p>
  </div>
  <?php if (isset($playlistUrl)) : ?>
    <a href="<?= $playlistUrl ?>" target="_blank"><img class="card-img" src="<?= $thumbnails->medium->url ?>" /></a>
  <?php else : ?>
    <img class="card-img" src="<?= $thumbnails->medium->url ?>" />
  <?php endif; ?>
  <div class="card-footer">
    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#list_<?= $id ?>" aria-expanded="false" aria-controls="multiCollapseVideoList">Toggle videos
    </button>
    <div id="list_<?php echo $id ?>" class="collapse">
      <?php get_template_part('lib/youtube-templates/video_list', 'video_list', $videos); ?>
    </div>
  </div>
</div>