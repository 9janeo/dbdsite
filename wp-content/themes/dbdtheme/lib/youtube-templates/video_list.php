<?php
// video list template

$video_list = $args;
?>
<?php if (!(isset($video_list->error) && $video_list->error)) : ?>
  <ul class="list-group videos">
    <?php foreach ($video_list as $video) : ?>
      <li class="list-group-item d-flex justify-content-between align-items-center video">
        <div class="d-flex w-100 justify-content-between">
          <small class="video-title text-bold mb-1"><?php echo $video->snippet->title; ?></small>
        </div>
        <span class="icon play mx-1"><i class="fa fa-solid fa-play"></i><?php echo  $video->snippet->views; ?></span>
        <span class="icon like mx-1"><i class="fa fa-thin fa-thumbs-up"></i> <?php echo  $video->snippet->views; ?></span>
      </li>
    <?php endforeach;
  else : ?>
    <li>
      <td><?php echo $video_list->error->message ?></td>
    </li>
  <?php endif; ?>
  </ul>