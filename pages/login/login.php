<?php
session_start();
$error = isset($_SESSION['login-error']) ? $_SESSION['login-error'] : '';
unset($_SESSION['login-error']); // Clear error after displaying
?>

<?php include '../../includes/header.php'; ?>

<body class="hold-transition login-page">
  <video autoplay muted loop class="bg-video">
    <source src="../../dist/video/NGLbackground.mp4" type="video/mp4">
  </video>
  <div class="login-box">
    <div class="login-logo d-flex justify-content-center">
      <b style="font-size: 2.5em; color: white;">NGL</b>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign In</p>

        <form action="userData/userLogin.php" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php include '../../includes/script.php'; ?>
</body>

</html>