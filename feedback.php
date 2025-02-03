<!-- @import('style.css'); -->
<?php include 'inc/header.php'; ?>
<?php
$SQL = 'SELECT * FROM feedback';
$result = mysqli_query($conn, $SQL);
if (!$result) {
  die('Query failed: ' . mysqli_error($conn));
}
$feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<h2>Past Feedback</h2>
<?php if (empty($feedbacks)): ?>
  <p class="lead mt-3">There is no feedback</p>
<?php else: ?>
  <?php foreach ($feedbacks as $feedback): ?>
    <div class="my-3 card w-75">
      <div class="row flex justify-content-center items-center" >
        <!-- Left side content -->
        <div class="col-md-6">
          <div class="card-body text-center">
            <div class="card-text mt-3 fw-bold"><?php echo $feedback['body']; ?></div>
            <div class="card-title mt-3"><?php echo $feedback['name']; ?></div>
            <div class="card-data mt-3"><?php echo $feedback['date']; ?></div>
          </div>
        </div>

        <!-- Right side image -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
          <img src="<?php echo $feedback['img']; ?>" class="img-fluid passport-photo " alt="img">
        </div>
      </div>
    </div>

  <?php endforeach; ?>
<?php endif; ?>

<?php include 'inc/footer.php'; ?>