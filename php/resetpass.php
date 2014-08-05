<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pix Gallery</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

   <!-- Navigation -->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="fa fa-bars"></i></a>
      <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand">
                <a href="../index.html">Pix Gallery</a>
            </li>
            <li>
                <a href="upalbum.php">Upload</a>
            </li>
            <li>
                <a href="#">Tags</a>
            </li>
            <li>
                <a href="#">Gallery</a>
            </li>
             <li>
                <a href="#">Search</a>
            </li>
            <li>
                <a href="login.php">Login/Register</a>
            </li>
        </ul>
    </nav>
    
    
    <!-- MAIN AREA OF PAGE-->
    <?php
        $error_message="";
        if(!empty($_POST['email']))
        {
            include("email.php");
            include("../../../dbprop.php");
            
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $goodemail=filter_var($email, FILTER_VALIDATE_EMAIL);
            
            //if valid email check if email exists in database
            if($goodemail){
                
                
                
            }
                   
        }//end if- post

    ?>
    <br/>
    <h2 style="padding: 0 0 0 40px;">Reset Password</h2>
    <BR/>

    <form role="form" method="post" action="resetpass.php" name="loginform" id="loginform">
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="fullname">
            *Email:
            </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required style="width: 400px;">
            <p class="help-block">
                Please enter the email you registered with
            </p>
        </div>
 
        <div class="form-group" style="padding: 0 0 0 40px;">
                <button type="submit" class="btn btn-info">
                   Reset
                </button>
        </div>
        
        <div class="form-group" style="padding: 0 0 0 40px;">
                <p class="help-block">
                    <?php 
                        echo $error_message; 
                    ?>
                </p>
        </div>
    </form>
            
        
    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    
        $("#menu-close").click(function(e) {
            e.preventDefault();
        
            $("#sidebar-wrapper").toggleClass("active");
        });

        // Opens the sidebar menu
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#sidebar-wrapper").toggleClass("active");
        }); 
    </script>
</html>

