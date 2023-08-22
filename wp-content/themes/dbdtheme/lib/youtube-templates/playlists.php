<?php

/**
 * Single playlist partial template
 */
$playlist = $args;
$id = isset($playlist->id) ? $playlist->id : $playlist->PlaylistId;
$fromAPI = isset($playlist->snippet);
$playlistUrl = $fromAPI ? $playlist->url : $playlist->PlaylistUrl;
$title = $fromAPI ? $playlist->snippet->title : $playlist->Title;
$description = $fromAPI ? $playlist->snippet->description : $playlist->Details;
$thumbnail = $fromAPI ? $playlist->snippet->thumbnails->high->url : $playlist->Thumbnail;
$itemCount =  $fromAPI ? $playlist->contentDetails->itemCount : $playlist->ItemCount;
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