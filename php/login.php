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
   
    
    <h2></h2>
        <BR/>
        
        
        <div class="row">
       
            <div class="col-sm-8">
                <?php
                session_start();
               
                if(!empty($_SESSION['loggedin']))
                {
                    $error_message='';
                ?>
 
                <h2>Account Information</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <p>Thanks for logging in <?php echo $_SESSION['name'] ?>! your email address is <?php echo $_SESSION['email'] ?>.</p>
                    </div> 
                </div>
                <h3>Password change</h3>
                <form role="form" method="post" action="login.php" name="loginform" id="loginform">
                    <div class="form-group">
                        <label for="oldpassword">Old password</label>
                        <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Enter password">
                    </div>
                    <div class="form-group">
                        <label for="newpassword">New password</label>
                        <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New password">
                    </div>
                    <div class="form-group">
                        <label for="newpassword2">Confirm new password</label>
                        <input type="password" class="form-control" name="newpassword2" id="newpassword2" placeholder="Confirm new password">
                    </div>
                    <button type="submit" name="login" value="login" class="btn btn-default">Submit</button>
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-10">
                            <p>
                            <?php 
                                echo $error_message; 
                            ?>
                            </p>
                        </div>
                    </div>   
                </form>
                
                <?php
                }
                elseif(!empty($_POST['email']) && !empty($_POST['password']))
                {
                    include("mysqli_class.php");
                    $db=new database(); 
                    
                    $email = $_POST['email'];
                    $password = trim(hash('sha256',$_POST['password']));
     
                    $query= "SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'";
                    
                    
                    $db->send_sql($query);
                    
               
                    if($row=$db->next_row())
                    {
                        $uid = $row['uid'];
                        $email = $row['email'];
                        $name = $row['name'];
                        
         
                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $email;
                        $_SESSION['loggedin'] = 1;
                        $_SESSION['uid'] = $uid;
         
                        echo "<h1>Success</h1>";
                        echo "<p>Welcome Back ".$_SESSION['name']."!</p>";
                     
                    }
                    else
                    {
                    echo "<h1>Error</h1>";
                    echo "<p>Sorry, your account could not be found. Please <a href=\"login.php\">click here to try again</a>.</p>";
                    }
                }
                elseif(!empty($_POST['oldpassword']) && !empty($_POST['newpassword']) && !empty($_POST['newpassword2']))
                {
                    include("mysqli_class.php");
                    $db=new database(); 
                    $password = trim(hash('sha256',$_POST['password']));
     
                    $query= "SELECT * FROM users WHERE email = '".$email."' AND password = '".$password."'";
                    
                    
                    $db->send_sql($query);
                    
                
                
                
                
                }
            
                else
                {
                ?>
     
                <h1>Member Login</h1>
     
                <p>Thanks for visiting! Please either login below, or <a href="register.php">click here to register</a>.</p>
     
               
    
                <form role="form" method="post" action="login.php" name="loginform" id="loginform">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                    <div class="checkbox">
                        <label>
                        <input type="checkbox"> Remember Me
                        </label>
                    </div>
                    <button type="submit" name="login" value="login" class="btn btn-default">Submit</button>
                </form>
    
                
                <?php
                }
                ?>
                
            
        
        </div>
    <div class="col-sm-4">
    </div>
    </div>
    
    
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

