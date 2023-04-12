<?php
// video list template

$video_list = $args;

?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Title</th>
      <th scope="col">Views</th>
      <th scope="col">Analytics</th>
    </tr>
  </thead>
  <tbody class="table">
    <?php if (!(isset($video_list->error) && $video_list->error)) :
      foreach ($video_list as $video) :
        if (($video->status->privacyStatus == 'private')) {
          continue;
        } ?>
        <tr>
          <td><?php echo $video->snippet->title;
              ?></td>
          <td><?php echo $video->snippet->views;
              ?></td>
          <td><a href="<?php echo 'analytics_url'; ?>">View Analytics</a></td>
        </tr>
      <?php endforeach;
    else : ?>
      <tr>
        <td><?php echo $video_list->error->message ?></td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>