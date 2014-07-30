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
                <a href="#">Photos</a>
            </li>
            <li>
                <a href="#">Albums</a>
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
                <a href="../php/login.php">Login/Register</a>
            </li>
        </ul>
    </nav>
    
    
    <!-- MAIN AREA OF PAGE-->
    <?php
        if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['fullname'])){
           
            include("mysqli_class.php");
            $db= new database();
            
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            
            $password = hash('sha256',$_POST['password']);
        
            if(!empty($_POST['user_profile'])){
                $profile = $_POST['user_profile'];
                $query="INSERT INTO users (name,password,email,user_profile) VALUES ('".$fullname."','".$password."','".$email."','".$profile."')";
            
            }
            else{
                $query="INSERT INTO users (name,password,email) VALUES ('".$fullname."','".$password."','".$email."')";
            }
            
            
            $db->send_sql($query);
            
            echo "<h1>Please check your email to confirm registration</h1>";
        }

    ?>
    
    <h2>New Registration</h2>
    <BR/>

    <form role="form" method="post" action="register.php" name="loginform" id="loginform">
        <div class="form-group">
        <label for="fullname" class="col-md-2">
            *Full Name:
        </label>
        <div class="col-md-10">
            <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Enter Full Name" required>
        </div>
        </div>

 
        <div class="form-group">
            <label for="email" class="col-md-2">
            *Email address:
            </label>
            <div class="col-md-10">
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" required>
                    <p class="help-block">
                    Example: someone@example.com
                    </p>
            </div>
        </div>
 
        <div class="form-group">
            <label for="pSassword" class="col-md-2">
                *Password:
            </label>
            <div class="col-md-10">
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                    <p class="help-block">
                    Min: 6 characters (Alphanumeric only)
                    </p>
            </div>
        </div>

        <div class="form-group">
            <label for="user_profile" class="col-md-2">
            User Profile (optional):
            </label>
            <div class="col-md-10">
                <textarea class="form-control" name="user_profile" id="user_profile" placeholder="About you"></textarea>
            </div>
        </div>

      
 
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
                <button type="submit" class="btn btn-info">
                   Register
                </button>
            </div>
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

