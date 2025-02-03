<?php
include 'inc/header.php';

// // Database connection
// $conn = mysqli_connect("localhost", "root", "", "your_database_name");
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// Set vars to empty values
$name = $email = $body = $img = '';
$nameErr = $emailErr = $bodyErr = $imgErr = '';

// Form submit
if (isset($_POST['submit'])) {
  // Validate name
  if (empty($_POST['name'])) {
    $nameErr = 'Name is required';
  } else {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  // Validate email
  if (empty($_POST['email'])) {
    $emailErr = 'Email is required';
  } else {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  }

  // Validate body
  if (empty($_POST['body'])) {
    $bodyErr = 'Body is required';
  } else {
    $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  }

  // Handle Image Upload
  if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $imgTmpName = $_FILES['img']['tmp_name'];
    $imgName = $_FILES['img']['name'];
    $imgSize = $_FILES['img']['size'];
    $imgType = $_FILES['img']['type'];

    // Validate if the file is an image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($imgType, $allowedTypes)) {
      // Sanitize the image name to prevent overwriting files
      $imgName = basename($imgName);
      $imgPath = 'uploads/' . $imgName;
      move_uploaded_file($imgTmpName, $imgPath); // Move the file to your server's folder
      $img = $imgPath; // Store the path in the database
    } else {
      $imgErr = 'Invalid image type. Only JPG, PNG, GIF are allowed.';
    }
  } else {
    $imgErr = 'Image is required';
  }

  // Check if there are no errors and insert into the database
  if (empty($nameErr) && empty($emailErr) && empty($bodyErr) && empty($imgErr)) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, img, body) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $img, $body);

    if ($stmt->execute()) {
      // Success - Redirect to feedback page
      header('Location: feedback.php');
      exit(); // Ensure no further code is executed
    } else {
      // Error inserting into database
      echo 'Error: ' . mysqli_error($conn);
    }

    $stmt->close();
  }
}

mysqli_close($conn);
?>

<img src="/php-crash/feedback/img/logo.png" class="w-25 mb-3" alt="">
<h2>Feedback</h2>
<?php echo isset($name) ? $name : ''; ?>
<p class="lead text-center">Leave feedback for Traversy Media</p>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="mt-4 w-75" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" id="name" name="name" placeholder="Enter your name" value="<?php echo $name; ?>">
    <div id="validationServerFeedback" class="invalid-feedback">
      <?php echo $nameErr; ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Enter your email" value="<?php echo $email; ?>">
    <div class="invalid-feedback">
      <?php echo $emailErr; ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="img" class="form-label">Image</label>
    <input type="file" class="form-control <?php echo $imgErr ? 'is-invalid' : ''; ?>" id="img" name="img">
    <div class="invalid-feedback">
      <?php echo $imgErr; ?>
    </div>
  </div>
  <div class="mb-3">
    <label for="body" class="form-label">Feedback</label>
    <textarea class="form-control <?php echo $bodyErr ? 'is-invalid' : ''; ?>" id="body" name="body" placeholder="Enter your feedback"><?php echo $body; ?></textarea>
    <div class="invalid-feedback">
      <?php echo $bodyErr; ?>
    </div>
  </div>
  <div class="mb-3">
    <input type="submit" name="submit" value="Send" class="btn btn-dark w-100">
  </div>
</form>

<?php include 'inc/footer.php'; ?>
