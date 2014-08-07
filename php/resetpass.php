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
            $proceed_reset=false;
            $db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $goodemail=filter_var($email, FILTER_VALIDATE_EMAIL);
            
            //if valid email check if email exists in database
            if($goodemail){
                $sql = "SELECT rec3.uid,rec2.qid,rec2.question,rec1.answer FROM users AS rec3, pwd_recovery_ques AS rec2, user_pwd_recovery AS rec1 WHERE rec1.qid = rec2.qid AND rec1.uid=rec3.uid AND rec3.email = ?";
        
                if(!$stmt = $db->prepare($sql)) {
                    echo 'Database prepare error';
                    exit;
                }
                $stmt->bind_param('s',$email);
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                //uid,question, answer as result of query
                //?
                $stmt->store_result();
                $stmt->bind_result($userid,$queid,$question,$answer);
                $queid=array();
                $ques=array();
                $ans=array();
                
                //should be 3 question answer pairs
                while($stmt->fetch()){
                    $uid=$userid;
                    $qid[]=$queid;
                    $ques[]=$question; 
                    $ans[]=$answer;
                }
                $stmt->close();    
      
            
                if(!(empty($qid)) && !(empty($ques)) && !(empty($ans)) && !empty($uid)){
                    $proceed_reset=true;
                }
                
            }//end if good email
            
            if($proceed_reset){
                         
    
    ?>
    <br/>
    <h2 style="padding: 0 0 0 40px;">Security Questions</h2>
    <br/>

    <form role="form" method="post" action="resetpass.php" name="loginform" id="loginform">
        <input type="hidden" id="qid1" name="qid1" value="<?php echo $qid[0] ?>">
        <input type="hidden" id="qid2" name="qid2" value="<?php echo $qid[1] ?>">
        <input type="hidden" id="qid3" name="qid3" value="<?php echo $qid[2] ?>">
    
   
        
        <input type="hidden" id="uid" name="uid" value="<?php echo $uid ?>">
        
        
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que1">
            <?php echo $ques[0]; ?>
            </label>
            <input type="text" class="form-control" name="answer1" id="answer1" placeholder="Answer" required style="width: 300px;">
        </div>
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que2">
            <?php echo $ques[1]; ?>
            </label>
            <input type="text" class="form-control" name="answer2" id="answer2" placeholder="Answer" required style="width: 300px;">
        </div>
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que3">
            <?php echo $ques[2]; ?>
            </label>
            <input type="text" class="form-control" name="answer3" id="answer3" placeholder="Answer" required style="width: 300px;">
        </div>
        <div class="form-group" style="padding: 0 0 0 40px;">
            <button type="submit" class="btn btn-info">
                Reset
            </button>
        </div>
        <div class="form-group" style ="font-size: 28px;padding: 0 0 0 40px;">
            <p>
                <?php 
                    echo $error_message; 
                ?>
                </p>
        </div>
        
        
    </form>
        
    <?php
                unset($qid);
                unset($ques);
                unset($ans);

            }//end if proceed reset pass
            else{
                ?>
    
                <br/>
                <h2 style="padding: 0 0 0 40px;">Sorry you have not registered with that email address!</h2>
                <?php
    
    
            }
        }//end if post['email']


        else if(!empty($_POST['answer1']) && !empty($_POST['answer2']) && !empty($_POST['answer3']))
        {
            //user answered 3 security questions now validate his answers before proceeding pass reset
            $answer1 = filter_var($_POST['answer1'], FILTER_SANITIZE_STRING);
            $answer2 = filter_var($_POST['answer2'], FILTER_SANITIZE_STRING);
            $answer3 = filter_var($_POST['answer3'], FILTER_SANITIZE_STRING);
            
            
            $ques_id=array();
            
            $ques_id[]=$_POST['qid1'];
            $ques_id[]=$_POST['qid2'];
            $ques_id[]=$_POST['qid3'];
            
            
            $userid= $_POST['uid'];
            $proceed_reset=true;
            
            //check all three security answers are good input
            if((strlen(trim($answer1)) == 0) || (strlen(trim($answer2)) == 0) || (strlen(trim($answer3)) == 0)){
                $proceed_reset=false;
                $error_message.="Invalid input, please enter valid answers!";
            }
            else{
                if((!(ctype_alnum(trim($answer1)))) || (!(ctype_alnum(trim($answer2)))) || (!(ctype_alnum(trim($answer3))))){
                    $proceed_reset=false;
                    $error_message.="Invalid input, please use only alphanumeric characters!";        
                }
            }
            
            if($proceed_reset){
                //check if 3 answers equal whats in database
                
                //put inputted answers into newanswers array
                $newanswers=array();
                $newanswers[]=strtolower(trim($answer1));
                $newanswers[]=strtolower(trim($answer2));
                $newanswers[]=strtolower(trim($answer3));
                
                include("../../../dbprop.php");
                $db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                $i=0;
                foreach($newanswers as $val){
                    $sql = "SELECT answer FROM user_pwd_recovery WHERE uid = ? AND qid = ?";
        
                
                    if(!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                
                    $stmt->bind_param('is',$userid,$ques_id[$i++]);
                
                
                    if (!$stmt->execute()) {
                        echo 'Database execute error';
                        exit;
                    }
                    $stmt->store_result();
                    $stmt->bind_result($ans);
                    $stmt->fetch();
                    $stmt->close();
                    
                    if((strcmp($ans,$val)) !==0 ){
                        $proceed_reset=false;
                    }
                }
                
                //if reached this point then security answers all match up! Reset the users pass and send it in email
                if($proceed_reset){
                     
                    //send temp password-in progress
                    //swift mail library   
                    include("email.php");
                    
                    
                    
                    $sql = "SELECT email FROM users WHERE uid = ?";
        
                
                    if(!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                
                    $stmt->bind_param('i',$userid);
                
                
                    if (!$stmt->execute()) {
                        echo 'Database execute error';
                        exit;
                    }
                    $stmt->store_result();
                    $stmt->bind_result($email);
                    $stmt->fetch();
                    $stmt->close();
                    
                    
                    $length = 10;
                    $temp_pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);


                   
                    //update user in database with the temp password
                    $pass = hash('sha256',$temp_pass);
                    
                    $sql = "UPDATE users SET password = ? WHERE uid = ?";
        
                
                    if(!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                
                    $stmt->bind_param('si',$pass,$userid);
                
                
                    if (!$stmt->execute()) {
                        echo 'Database execute error';
                        exit;
                    }
                    $stmt->close();
                    
                    
                    $mailer = Swift_Mailer::newInstance($transport);

                    $message = Swift_Message::newInstance('Here is your temporary password')
                        ->setFrom(array('pixgalleryweb@gmail.com' => 'Admin'))
                        ->setTo(array($email))
                        ->setBody('You have requested a password reset. Your temporary password is:'.$temp_pass);
                    $result = $mailer->send($message);    
    
                    
    
    
    
    
                    ?>
                    <br/>
                    <h2 style="padding: 0 0 0 40px;">Please check your email for a temporary password!</h2>
                    <?php
                }
                //answers did not match up
                else{
                    ?>
                    <br/>
                    <h2 style="padding: 0 0 0 40px;">Sorry the answers you provided are incorrect.</h2> 
                    <h3 style="padding: 0 0 0 40px;">Still need help?</h3>
                    <h4 style="padding: 0 0 0 40px;">Send us an <a href="mailto:pixgalleryweb@gmail.com?Subject=Pix%20Gallery%20account%20inquiry" target="_top">email.</a></h4>
                    <?php
                }
            
            }
            else{
                ?>
    
                <br/>
                <h2 style="padding: 0 0 0 40px;">Sorry we could not process your request use only alphanumeric characters!</h2>
                <?php
            }
        }//end if POST[answer1]...POST[answer3]
        
        else
        {
            
    
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
                   Find account
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

