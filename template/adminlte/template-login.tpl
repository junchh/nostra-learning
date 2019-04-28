<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{site_title}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{site_root}css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{site_root}css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{site_root}css/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.login-page, .register-page {
    background-image: url(/Nostra/template/adminlte/images/bg3.jpg);
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center;
}
</style>
</head>
<body class="login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Nostra</b>LC</a>
  </div>
  <div id="warning-alert">

	</div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="user_email" id="user_email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="user_password" id="user_password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input id="remember" type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="btn-login" id="btn-login" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <a href="#">I forgot my password</a><br>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 2.2.0 -->
<script src="{site_root}js/plugins/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{site_root}js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="{site_root}js/plugins/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  
  $('#btn-login').click(function(event){
	  event.preventDefault();
	  
	  $('#warning-alert').hide();
	  
	  var email = $('#user_email').val();
	  var pass = $('#user_password').val();
	  var checked;
	  
	  if($('#remember').prop('checked')){
		  checked = 1;
	  } else {
		  checked = 0;
	  }
	  
	  
	  $.ajax({
		  type: "POST", 
		  url: "ajax.php?type=login",
		  data: "Email="+email+"&Password="+pass+"&Checked="+checked,
		  success: function (html){
			  if(html == 'true'){
				  window.location='index.php';
			  } else if(html == 'false') {
				  $('#warning-alert').html('<div id="wrong-alert" class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Wrong email or password!</h4>please type the correct email and password.</div>');
				  $('#warning-alert').show("normal");
			  }
		  }
	  });
  });
</script>
</body>
</html>
