<?php
// single video template

$video_list = $args;
$video = $args;
$etag = $video->etag;
// ToDo: add functionality to display as card or table row
?>
<?php
if (!(property_exists($video_list, 'error'))) :
?>
  <tr>
    <td><?php echo $video->snippet->title; ?></td>
    <td><?php echo $video->snippet->views; ?></td>
    <td><a href="<?php echo 'analytics_url'; ?>">View Analytics</a></td>
  </tr>
<?php else : ?>
  <tr>
    <td><?php echo $video_list->error->message ?></td>
  </tr>
<?php endif; ?>