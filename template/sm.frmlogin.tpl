  <body class="login-body">

    <div class="container">

      <form method="post" name="frmLogin" id="frmLogin" class="form-signin" action="?m=admin.auth">
        <h2 class="form-signin-heading">Koperasi Simpan Pinjam</h2>
        <div class="login-wrap">
            <div class="user-login-info">
                <input type="text" name="edtUserName" class="form-control" placeholder="User ID" autofocus>
                <input type="password" name="edtPassword" class="form-control" placeholder="Password">
            </div>
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>

        </div>

          <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <div class="modal-body">
                          <p>Enter your e-mail address below to reset your password.</p>
                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                          <button class="btn btn-success" type="button">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- modal -->

      </form>

    </div>



    <!-- Placed js at the end of the document so the pages load faster -->

    <!--Core js-->
<script src="js/lib/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
