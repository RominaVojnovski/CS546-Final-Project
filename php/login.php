<?php
ob_start();
session_start();
?>
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
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.html">PixGallery</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="../php/home.php">Home</a>
                    </li>
                    <li>
                        <a href="../php/upalbum.php">Upload</a>
                    </li>
                    <li>
                        <a href="../php/tags.php">Tags</a>
                    </li>     
                    <li>
                        <a href="../php/logout.php">Logout</a>
                    </li>

                </ul>
                <ul class="nav navbar-nav  navbar-right">
                    <li> 
                        <a class="text-info" href="login.php"><strong>Welcome <?php if(isset($_SESSION['name'])) echo $_SESSION['name'] ?></strong></a>
                    </li>                
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    
    <!-- MAIN AREA OF PAGE-->
   
    
    <h2></h2>
        <BR/>
        <div class="row">
       
            <div class="col-sm-8">
                <?php
                if(!empty($_SESSION['loggedin']))
                {
                    $error_message='';
                    if(!empty($_POST['oldpassword']) && !empty($_POST['newpassword']) && !empty($_POST['newpassword2']))
                
                    {
                        //include("mysqli_class.php");
                        include("../../../dbprop.php");   
                    
                        $db2 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                        $oldpassword = trim(hash('sha256',$_POST['oldpassword']));
                        $uid=$_SESSION['uid'];
                        $sql = "SELECT uid,password FROM users WHERE uid = ? AND password =?";
                  
                        if (!$stmt = $db2->prepare($sql)) {
                            echo 'Database prepare error';
                            exit;
                        }
                        $stmt->bind_param('is',$uid,$oldpassword);
                    
                        if (!$stmt->execute()) {
                            echo 'Database execute error';
                            exit;
                        }
                
                        $stmt->store_result();
                        $stmt->bind_result($userid,$oldpass);
                        $stmt->fetch();
                        $stmt->close();
                
                        //if user put in correct old password
                        if(((strcmp($oldpassword,$oldpass)) == 0) && ($uid == $userid)){
                            //check new password and new password2 if they are good to go 
                            //then update users passsword
                            $newpassword = trim(hash('sha256',$_POST['newpassword']));
                            $newpassword2 = trim(hash('sha256',$_POST['newpassword2']));
                            
                            //if both new passwords match proceed
                            if((strcmp($newpassword,$newpassword2)) == 0){
                                 
                                $re= '/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/';
                                $p1=trim($_POST['newpassword']);    
                                if((preg_match($re,$p1)) && (strlen($p1)<16) && (strlen($p1)>5)) 
                                {
                                
                                    $db2 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                                    $sql = "UPDATE users SET password = ? WHERE uid = ?";
                                
                                    if (!$stmt = $db2->prepare($sql)) {
                                        echo 'Database prepare error';
                                        exit;
                                    }
                                    $stmt->bind_param('si',$newpassword,$uid);
                                    if (!$stmt->execute()) {
                                        echo 'Database execute error';
                                        exit;
                                    }
                                    $stmt->close();
                                    echo "<br/><br/><p style='font-size:20px;padding: 0 0 0 100px;'>Password has been changed!</p>";
                                }
                                else{
                                    echo "<br/><br/><p style='font-size:20px;padding: 0 0 0 100px;'>Invalid password, please use alphanumeric chars min 6 max 15 chars long! Must contain at least one number and one alphabetical character!</p>";
                                }
                            }
                            else{
                                
                            
                                echo "<br/><br/><p style='font-size:20px;padding: 0 0 0 100px;'>Sorry we could not process your request please try again!</p>";
                            
                        
                            }
                        }//end if user put in correct old password
                        //user didnt enter his old password     
                        else{
                        
                            echo "<br/><br/><p style='font-size:20px;padding: 0 0 0 100px;'>Sorry we could not process your request please try again!</p>";    
                        }
                    }//end if user is requesting a password change
                    
                
                    else{
                        
                ?>
                <br/>
                <h2 style="padding: 0 0 0 100px;">Account Information</h2>
                <div class="row" style="padding: 0 0 0 100px;">
                    <div class="col-sm-12">
                        <p>Thanks for logging in <?php echo $_SESSION['name'] ?>! your email address is <?php echo $_SESSION['email'] ?>.</p>
                    </div> 
                </div>
                <?php
                //user has not cofirmed registration if when_confirmed Session var is NULL    
                if(empty(($_SESSION['confirmed'])))
                {
                    ?>
                    <div class="row" style="padding: 0 0 0 100px;">
                        <div class="col-sm-12">
                            <p>You have not confirmed registration yet please check your email.</p>
                        </div> 
                    </div>
                    <?php
                }
                        ?>
                    
                
                <h3 style="padding: 0 0 0 100px;">Password change</h3>
                <form role="form" method="post" action="login.php" name="loginform" id="loginform">
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <label for="oldpassword">Old password</label>
                        <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Enter password" required style="width: 400px;">
                    </div>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <label for="newpassword">New password</label>
                        <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New password" required style="width: 400px;" onkeyup="checkPass(); return false;"><span id="confirmMessage2" class="confirmMessage"></span><p class="help-block" style="width: 400px;">
                    Min: 6 characters Max: 15 characters (Alphanumeric only, at least one numeric and at least one alphabetic character)
                    </p>
                    </div>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <label for="newpassword2">Confirm new password</label>
                        <input type="password" class="form-control" name="newpassword2" id="newpassword2" placeholder="Confirm new password" required style="width: 400px;" onkeyup="checkPass(); return false;"><span id="confirmMessage" class="confirmMessage"></span><p class="help-block">
                        Password reset? <a href= 'resetpass.php'>Click Here</a>
                        </p>
                    </div>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <button type="submit" name="login" value="login" class="btn btn-info">Submit</button>
                    </div>
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
                }

                elseif(!empty($_POST['email']) && !empty($_POST['password']))
                {
                    
                    include("../../../dbprop.php");   
                    $db2 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                    
                    $email = $_POST['email'];
                    $password = trim(hash('sha256',$_POST['password']));
                    //$remember_me=$_POST['remember_me'];
     
                    $sql = "SELECT uid,name,email,when_confirmed FROM users WHERE email = ? AND password =?";
                  
                    if (!$stmt = $db2->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                    $stmt->bind_param('ss',$email,$password);
                    
                    if (!$stmt->execute()) {
                        echo 'Database execute error';
                        exit;
                    }
                
                    $stmt->store_result();
                    $stmt->bind_result($uid,$name,$email,$confdate);
                    $stmt->fetch();
                    $stmt->close();
                 
               
                    if((!empty($uid)) && (!empty($name)) && (!empty($email)))
                    {
                        
                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $email;
                        $_SESSION['loggedin'] = 1;
                        $_SESSION['uid'] = $uid;
                        $_SESSION['confirmed']= $confdate;
                        

          
                        
                        if(isset($_POST['remember_me']))
                        {
                        
                            /* Set cookie to last 1 year */
                            setcookie('email', $_POST['email'], time()+60*60*24*365);
                        } 
                        else
                        {
                            if(isset($_COOKIE['email'])) {
		                      setcookie(email,"", time() - 100);
	                       
                            }
                        }
                        //Redirect user to home/gallery page
                        header("location:home.php");
                    } 
                    else
                    {
                        echo "<br/><br/>";
                        echo "<p style='font-size:20px;padding: 0 0 0 100px;'>Sorry, you entered the wrong combination. Please <a href=\"login.php\">click here to try again</a>.</p>";
                    }
                }
                else
                {
                ?>
                <br/>
                <h2 style="padding: 0 0 0 100px;">Member Login</h2>
     
                <p style="padding: 0 0 0 100px;">Thanks for visiting! Please either login below or <a href="register.php">click here to register</a>.</p>
                
              
                <form role="form" method="post" action="login.php" name="loginform" id="loginform">
                    <br/>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" style="width: 300px;" value = "<?php if (isset($_COOKIE['email']))echo $_COOKIE['email']; ?>">
                    </div>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" style="width: 300px;">
                        <p class="help-block">
                        Forgot password? <a href= 'resetpass.php'>Click Here</a>
                        </p>
                    </div>
                    <div class="checkbox" style="padding: 0 0 0 100px;">
                        <label>
                        <input type="checkbox" name="remember_me"> Remember Me
                        </label>
                    </div>
                    <div class="form-group" style="padding: 0 0 0 100px;">
                        <button type="submit" name="login" value="login" class="btn btn-info">Submit</button>
                    </div>
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
        

        function checkPass()
        {
        
            //Store the password field objects into variables ...
        
            var pass1 = document.getElementById('newpassword');
        
            var pass2 = document.getElementById('newpassword2');
            //Store the Confimation Message Object ...
            var message = document.getElementById('confirmMessage');
            var message2 = document.getElementById('confirmMessage2');
            //Set the colors we will be using ...
            var goodColor = "#66cc66";
            var badColor = "#ff6666";
            
            //check that password requirement is met
            //re = /(?=.*[a-zA-Z])(?=.*[0-9]).{6,15}/;
            re= /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
            
           
            if((pass1.value.length < 6) || (pass1.value.length > 15) || !re.test(pass1.value)){
                pass1.style.backgroundColor = badColor;
                message2.style.color = badColor;
                message2.innerHTML = "Password requirement not met!"
            
            }
            if((pass1.value.length>5) && (pass1.value.length<15) && re.test(pass1.value)){
                pass1.style.backgroundColor = goodColor;
                message2.style.color = goodColor;
                message2.innerHTML = "Password requirement met"
            }

            //Compare the values in the password field 
            //and the confirmation field
            if((pass1.value == pass2.value) && (pass2.value.length > 0)){
                //The passwords match. 
                //Set the color to the good color and inform
                //the user that they have entered the correct password 
                pass2.style.backgroundColor = goodColor;
                message.style.color = goodColor;
                message.innerHTML = "Passwords match"
            }
        
        
            else if((pass1.value !== pass2.value) && (pass2.value.length > 0)){
                //The passwords do not match.
                //Set the color to the bad color and
                //notify the user.
                pass2.style.backgroundColor = badColor;
                message.style.color = badColor;
                message.innerHTML = "Passwords Do Not Match!"
            }
        }  
    </script>
</html>