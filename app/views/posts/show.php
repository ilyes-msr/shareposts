<?php require APPROOT . '/views/inc/header.php'; ?>
  <a href="<?php echo URLROOT;?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
  <br><br>
  <div class="card card-body mb-3">
    <h4><?php echo $data->title; ?></h4>
    <div class="bg-light p-2 mb-3">
      written by <?php echo $data->name; ?> on <?php echo $data->postDate; ?>
    </div>
    <p class="card-text"><?php echo $data->body; ?></p>
  </div>

  <?php if($data->userId == $_SESSION['user_id']) : ?>
  <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data->postId; ?>" class="btn btn-warning">Edit</a>
  <form class="float-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data->postId; ?>" method="post">
    <input type="submit" value="Delete" class="btn btn-danger">
  </form>
    <?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>