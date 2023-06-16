<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>
<body class="hold-transition login-page bg-black">
  <h1><b>Digital Course File</b></h1>
  <div class="login-box">
    <div class="login-logo">
      <a href="#" class="text-white"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Enter your new password.</p>
        <form action="reset.php" method="POST">
        <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" required placeholder="email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
          <!-- <input type="email" class="form-control" name="email" required placeholder="email"> -->
            <input type="password" class="form-control" name="password" required placeholder="New Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
         
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <?php include 'footer.php'; ?>

</body>
</html>