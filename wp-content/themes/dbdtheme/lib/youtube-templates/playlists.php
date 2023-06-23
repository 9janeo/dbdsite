<?php

/**
 * Single playlist partial template
 */
$playlist = $args;
$id = $playlist->id ? $playlist->id : $playlist->PlaylistId;
$playlistUrl = $playlist->snippet ? $playlist->url : $playlist->PlaylistUrl;
$title = $playlist->snippet ? $playlist->snippet->title : $playlist->Title;
$description = $playlist->snippet ? $playlist->snippet->description : $playlist->Details;
$thumbnail = $playlist->snippet ? $playlist->snippet->thumbnails->high->url : $playlist->Thumbnail;
$itemCount =  $playlist->contentDetails->itemCount;
?>

<div class="card-header <?= $id ?>">
  <h5 class="card-title"><?= $title ?></h5>
  <p class="card-text"><?php echo $itemCount; ?> Videos</p>
</div>
<?php if (isset($playlistUrl)) : ?>
  <a href="<?= $playlistUrl ?>" target="_blank"><img class="card-img" src="<?= $thumbnail ?>" /></a>
<?php else :
  // allow active video in list to display here
?>
  <img class="card-img" src="<?= $thumbnail ?>" />
<?php endif; ?>