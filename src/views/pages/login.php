<?php


if( $_SESSION['msg'] ) {
  $class = "btn btn-danger btn-sm w-100 mb-3";
  $msg = "Usuário ou senha inválidos!";
  $_SESSION['msg'] = false;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="Keywords" content="">
  <meta name="author" content="Junior Neto Dev WEB">
  <meta name="url" content="">
  <title>Login</title>
  <!-- loader-->
  <link href="<?=$base;?>/assets/css/pace.min.css" rel="stylesheet"/>
  <script src="<?=$base;?>/assets/js/pace.min.js"></script>
  <!--favicon-->
  <link rel="icon" href="<?=$base;?>/assets/images/favicon.ico" type="image/x-icon">
  <!-- Bootstrap core CSS-->
  <link href="<?=$base;?>/assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="<?=$base;?>/assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="<?=$base;?>/assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?=$base;?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->
  <link href="<?=$base;?>/assets/css/app-style.css" rel="stylesheet"/>
  
</head>

<body class="bg-theme bg-theme1">

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">

 <div class="loader-wrapper"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
	<div class="card card-authentication1 mx-auto my-5">
		<div class="card-body">
		 <div class="card-content p-2">
		 	<div class="text-center">
		 		<img src="<?=$base?>/assets/images/logos/light-blue.png" alt="logo icon" width="300">
		 	</div>
		  <div class="card-title text-uppercase text-center py-3">login</div>
            <span class="<?=$class?>" style="text-align: center;"><?=$msg?></span>
		    <form method="POST" action="<?=$base;?>/loginaction">
			  <div class="form-group">
			  <label for="exampleInputUsername" class="">Login</label>
			   <div class="position-relative has-icon-right">
				  <input type="text" id="exampleInputUsername" value="Júnior Neto" name="login" class="form-control input-shadow" required>
				  <div class="form-control-position">
					  <i class="fas fa-user"></i>
				  </div>
			   </div>
			  </div>
			  <div class="form-group">
			    <label for="password" class="">Senha</label>
			    <div class="position-relative has-icon-right">
				    <input type="password" id="password" value="240604" name="pass" class="form-control input-shadow" required>
            <div class="form-control-position">
              <span type="button" id="showPassword"><i class='fas fa-lock'></i></span>
            </div>
			    </div>
			  </div>
			  <div class="col text-right">
			    <p class="text-sm">* Clique no cadeado para ver a senha.</p>
			  </div>
			  <button type="submit" class="btn btn-light btn-block">Logar</button>
			 </form>
		   </div>
		  </div>
	     </div>
    
     <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	
	
	</div><!--wrapper-->
	
  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
	
  <!-- sidebar-menu js -->
  <script src="assets/js/sidebar-menu.js"></script>
  
  <!-- Custom scripts -->
  <script src="assets/js/app-script.js"></script>
  <script>
    $(function(){
      
      // Show or Hide the password
      $('#showPassword').on('click', function(){
        
        // Get the password field
        var passwordField = $('#password');
    
        // Get the current type of the password field will be password or text
        var passwordFieldType = passwordField.attr('type');
    
        // Check to see if the type is a password field
        if(passwordFieldType == 'password')
        {
            // Change the password field to text
            passwordField.attr('type', 'text');
            $(this).html("<i class='fas fa-lock-open'></i>");
    
        } else {
            // If the password field type is not a password field then set it to password
            passwordField.attr('type', 'password');
            $(this).html("<i class='fas fa-lock'></i>");
    
        }
      });
    });
  </script>
  
</body>
</html>
