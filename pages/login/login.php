<?php
session_start();
$error = isset($_SESSION['login-error']) ? $_SESSION['login-error'] : '';
unset($_SESSION['login-error']); // Clear error after displaying
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NGL | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

  <style>
    body,
    html {
      height: 100%;
      margin: 0;
    }

    .bg-video {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
      z-index: -1;
    }

    .login-box {
      position: relative;
      z-index: 2;
    }

    .login-card-body {
      background: rgba(255, 255, 255, 0.8);
    }
  </style>

  <script>
    window.onload = function () {
      const error = "<?php echo $error; ?>";
      if (error) {
        alert(error);
      }
    }
  </script>
</head>

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