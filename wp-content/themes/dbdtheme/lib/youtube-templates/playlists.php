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
// https://www.youtube.com/watch?v=DyOm5BX2GZk&list=PLgHIZMXekZto-3q1_Gb6HwE9fzfy3zF9g&ab_channel=DISBYDEM
// Parameter for first video in playlist v=DyOm5BX2GZk
// extract from thumbnail urls
//  https://www.youtube.com/playlist?list=UUK8sQmJBp8GCxrOtXWBpyEA
// print_r($playlist);
// Embedd links
// https://www.youtube.com/embed?list=PLMlNiWEoh5QrLU6C7_Ad71UtRpACh3FdQ
// or
// https://www.youtube.com/embed/?listType=playlist&list=PLMlNiWEoh5QrLU6C7_Ad71UtRpACh3FdQ
?>
<div class="card">
  <div class="card-title">
    <a href="https://www.youtube.com/playlist?list=<?php echo $id ?>" target="_blank"><img class="card-img-top" src="<?php echo $thumbnails->medium->url ?>" /></a>
    <h3><?= $title ?></h3>
    <p class="small card-text pull-left"><?php echo $itemCount; ?> Videos</p>
  </div>
  <?php get_template_part('lib/youtube-templates/video_list', 'video_list', $videos); ?>
</div>