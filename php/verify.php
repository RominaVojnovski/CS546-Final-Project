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
        
    <p style="font-size: 30px; padding: 0 0 0 30px;">You have already confirmed registration!</p>
    
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
                    
    <p style="font-size: 30px; padding: 0 0 0 30px;">Thank you for confirming you may now login and enjoy full access!</p>
                    
    <?php
            }
        }
           
        else
        {       
    ?>
    <p style="font-size: 30px; padding: 0 0 0 30px;">Sorry we could not process your request</p>
    <?php
        }
    }//if email and activation query string are set 
           
    else{
    ?>
        <p style="font-size: 30px; padding: 0 0 0 30px;">We could not process your request</p>
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

