<?php
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
                    <?php 
                    if(isset($_SESSION['loggedin']))      
                        {
                    ?>
                    <li>
                        <a href="../php/logout.php">Logout</a>
                    </li>
                    <?php
                        }
                    ?>

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
    <br/>
    <br/>
    <?php
    if(isset($_GET['activation']) && isset($_GET['email'])){
        include("mysqli_class.php");
        //include("../../../dbprop.php");   
        $db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $activation=$_GET['activation'];
        $email=$_GET['email'];
        
        $sql = "SELECT when_confirmed,email,activation FROM users WHERE activation= ? AND email=?";
                  
        if (!$stmt = $db->prepare($sql)) {
            echo 'Database prepare error';
            exit;
        }

        $stmt->bind_param('ss',$activation,$email);
                    
        if (!$stmt->execute()) {
            echo 'Database execute error';
            exit;
        }
        
        $stmt->store_result();
        $stmt->bind_result($confirmed_date,$emailadd,$acti);
        $stmt->fetch();
        $stmt->close();
        
        //if activation code and email match up so far so good 
        if((strcmp($emailadd,$email)==0) && (strcmp($acti,$activation)==0)){
            //now check if the user already confirmed or not
            if(!empty($confirmed_date)){//user has already confirmed
                
    ?>
    <br/>
    <br/>
    <br/>
    <p style="font-size: 20px; padding: 0 0 0 30px;">You have already confirmed registration!</p>
    
    <?php
            }
            else{//go ahead and confirm the user
                $now=date("Y-m-d H:i:s");
                $sql = "UPDATE users SET when_confirmed = ? WHERE email = ? AND activation = ?";
            
                if (!$stmt = $db->prepare($sql)) {
                    echo 'Database prepare error';
                    exit;
                }
                $stmt->bind_param('sss',$now,$email,$activation);
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
            
                ?>
    <br/>
    <br/> 
    <br/> 
    <p style="font-size: 20px; padding: 0 0 0 30px;">Thank you for confirming you may now login and enjoy full access!</p>
                    
    <?php
            }
        }
           
        else
        {       
    ?>
    <br/>
    <br/> 
    <br/>
    <p style="font-size: 20px; padding: 0 0 0 30px;">Sorry we could not process your request</p>
    <?php
        }
    }//if email and activation query string are set 
           
    else{
    ?>
        <br/>
        <br/> 
        <br/>
        <p style="font-size: 20px; padding: 0 0 0 30px;">We could not process your request</p>
    <?php
    }
        ?>
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

