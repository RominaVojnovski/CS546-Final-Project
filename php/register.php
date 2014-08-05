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
        $error_message2="";
        if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password2']) && !empty($_POST['fullname']) && ($_POST['question1'] != '') && ($_POST['question2'] != '') && ($_POST['question3'] != '') && !empty($_POST['answer1']) && !empty($_POST['answer2']) && !empty($_POST['answer3']))
        {
            
            include("email.php");
            include("../../../dbprop.php"); 
            
            
            $proceed_reg=true;
            $goodemail=false;
            
            $p1=trim($_POST['password']);
            $p2=trim($_POST['password2']);
         
            $db = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            
            $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
            
            
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            
            if(!empty($_POST['user_profile'])){
                $profile=filter_var($_POST['user_profile'], FILTER_SANITIZE_STRING);    
            }
        
            
            
            
            //goodemail is boolean to check whether valid email address or not
            $goodemail=filter_var($email, FILTER_VALIDATE_EMAIL);
            
            //check if password already exists in database
            if($goodemail){
                $sql = "SELECT email FROM users WHERE email = ?";
                if (!$stmt = $db->prepare($sql)) {
                    echo 'Database prepare error';
                    exit;
                }
                $stmt->bind_param('s',$email);
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                
                $stmt->store_result();
                $stmt->bind_result($email2);
                $stmt->fetch();
                $stmt->close();
                
                if((strcmp($email,$email2)) == 0){
                    $proceed_reg=false;
                    $error_message2.="You have already registered using this email address! Click <a href='resetpass.php'>Here</a> to reset password.";
                }

            }
            else{
                $proceed_reg=false;
                $error_message2.="Invalid email address! ";
            }
           
            
            
            //passwords do not match dont proceed the registration
            if((strcmp($p1,$p2))!== 0){
                $error_message2.="Passwords do not match! ";
                $proceed_reg=false;
                
            }
            
            
            $qid1=$_POST['question1'];
            $qid2=$_POST['question2'];
            $qid3=$_POST['question3'];
            
            //check all three security questions are different
            
            if(($qid1 == $qid2) || ($qid2 == $qid3) || ($qid1 == $qid3)){
                $proceed_reg=false;
                $error_message2.="Please choose 3 different questions!";   
            }
            
            
            $answer1 = filter_var($_POST['answer1'], FILTER_SANITIZE_STRING);
            $answer2 = filter_var($_POST['answer2'], FILTER_SANITIZE_STRING);
            $answer3 = filter_var($_POST['answer3'], FILTER_SANITIZE_STRING);
            
            //check all three security answers are good input
            if((strlen(trim($answer1)) == 0) || (strlen(trim($answer2)) == 0) || (strlen(trim($answer3)) == 0))
            {
                $proceed_reg=false;
                $error_message2.="Invalid input, please enter valid answers!";
            }
            else{
                $rex='/[^a-z0-9]/';
                if((!(ctype_alnum(trim($answer1)))) || (!(ctype_alnum(trim($answer2)))) || (!(ctype_alnum(trim($answer3))))){
                    $proceed_reg=false;
                    $error_message2.="Invalid input, please use only alphanumeric characters!";        
                }
            }
            
            
     
            //if name length less than 5 chars or greater than 50 chars dont proceed the registration
            if(((strlen($fullname))<5) || ((strlen($fullname))>50)){
                $proceed_reg=false;
                $error_message2.="Invalid name, please enter 5 to 50 characters! ";
            }
            if (preg_match('/[^a-zA-Z ]/', $fullname)){
                $proceed_reg=false;
                $error_message2.="Invalid name, please use only alphabetical characters! ";    
            }
            
            $re= '/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/';
            if((!preg_match($re,$p1)) || (strlen($p1)>15) || (strlen($p1)<6)){
                $proceed_reg=false;
                $error_message2.="Invalid password, please use alphanumeric chars min 6 max 15 chars long! Must contain at least one number and one alphabetical character!";    
            }  
            
            if($proceed_reg){
                
                 //hashed password
                $password = hash('sha256',$p1);
 
                //user has entered data in profile field....
                
                if(!empty($profile)){
                    $sql = "INSERT INTO users (name,password,email,user_profile) VALUES (?,?,?,?)";
                
                    if (!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                    $stmt->bind_param('ssss',$fullname,$password,$email,$profile);     
                
                }   
                else {
                    
                    $sql = "INSERT INTO users (name,password,email) VALUES (?,?,?)";
                    
                    if (!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                    }
                    
                    $stmt->bind_param('sss',$fullname,$password,$email);
            
                }
                
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                //$stmt->close();
                   
            
                $uid=$db->insert_id;
                
                //insert data into user_pwd_recovery table total of 3 times for 3 security question/answer 
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid1,trim($answer1));
                                  
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid2,trim($answer2));
                                  
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        echo 'Database prepare error';
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid3,trim($answer3));
                                  
                if (!$stmt->execute()) {
                    echo 'Database execute error';
                    exit;
                }
                $stmt->close();
                
                
                
                //send verification email
                //swift mail library   
                $mailer = Swift_Mailer::newInstance($transport);

                $message = Swift_Message::newInstance('Test Subject')
                        ->setFrom(array('pixgalleryweb@gmail.com' => 'admin'))
                        ->setTo(array($email))
                        ->setBody('This is a test mail.');
                $result = $mailer->send($message);    
                //redirect to complete php page to inform user to check their email 
                header("Location:complete.php");
                
            }//end proceed reg
                   
        }//end if- post variables are set

    ?>
    <br/>
    <h2 style="padding: 0 0 0 40px;">Register Here</h2>
    <BR/>

    <form role="form" method="post" action="register.php" name="loginform" id="loginform">
        
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="fullname">
            *Full Name:
            </label>
            <input type="text" class="form-control" name="fullname" id="fullname" onkeyup="checkName(); return false;" placeholder="Enter Full Name" required style="width: 400px;"><span id="confirmMessage3" class="confirmMessage"></span>
            <p class="help-block" style="width: 400px;">
                Name must be alphabetical characters only between 5 to 50 characters long
            </p>
        </div>
        
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="email">
            *Email address:
            </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address" required style="width: 400px;">
            <p class="help-block">
            Example: someone@example.com
            </p>
        </div>
 
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="password">
            *Password:
            </label>
            <input type="password" class="form-control" name="password" id="password" onkeyup="checkPass(); return false;" placeholder="Enter Password" required style="width: 400px;"><span id="confirmMessage2" class="confirmMessage"></span>
            <p class="help-block" style="width: 400px;">
                    Min: 6 characters Max: 15 characters (Alphanumeric only, at least one numeric and at least one alphabetic character)
            </p>
        </div>
        
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="password2">
                *Password:
            </label>
            <input type="password" class="form-control" name="password2" id="password2" onkeyup="checkPass(); return false;" placeholder="Re-enter Password" required style="width: 400px;"><span id="confirmMessage" class="confirmMessage"></span>
            <p class="help-block">
                Confirm password
            </p>
        </div>

        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="user_profile">
            User Profile (optional):
            </label>
            <textarea class="form-control" name="user_profile" id="user_profile" placeholder="About you" style="width: 400px;"></textarea>
        </div>
        
        
        <br/>
        <div class="form-group" style="font-size: 28px; padding: 0 0 0 40px;">
            Choose three different questions
        </div>
        
        
        
        
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que1">
            *Question 1:
            </label>
           
                <?php include('mysqli_class.php');
                    $db=new database();
                    $query = "SELECT qid,question FROM pwd_recovery_ques";
                    $res=$db->send_sql($query);
                    echo "<select class='form-control' id='question1' name='question1' required style='width: 300px;'><option value=''>-Choose a question-</option>";
                    while($row = $db->next_row()) {
                        echo "<option value=".$row['qid'].">".$row['question']."</option>"; 
                    }
                    echo "</select>";
                    ?>    
        </div>       
        <div class="form-group" style ="padding: 0 0 0 40px;">
            <label for="sec-ans1">
            Answer:
            </label>
            <input type="text" class="form-control" name="answer1" id="answer1" maxlength="40" onkeyup="checkAnswer(); return false;" placeholder="alphanumeric characters only" required style="width: 300px;">
        </div>
   
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que2">
            *Question 2:
            </label>
                
                <?php
                    $query2 = "SELECT qid,question FROM pwd_recovery_ques";
                    $res2=$db->send_sql($query2);
                    echo "<select class='form-control' id='question2' name='question2' required style='width: 300px;'><option value=''>-Choose a question-</option>";
                    while($row2 = $db->next_row()) {
                        echo "<option value=".$row2['qid'].">".$row2['question']."</option>"; 
                    }
                    echo "</select>";
                    ?>
        </div>
         <div class="form-group" style ="padding: 0 0 0 40px;">
            <label for="sec-ans2">
            Answer:
            </label>
            <input type="text" class="form-control" name="answer2" id="answer2" maxlength="40" onkeyup="checkAnswer(); return false;" placeholder="alphanumeric characters only" required style="width: 300px;">
        </div>
        <div class="form-group" style="padding: 0 0 0 40px;">
            <label for="sec-que3">
            *Question 3:
            </label>
                
                <?php
                    $query3 = "SELECT qid,question FROM pwd_recovery_ques";
                    $res3=$db->send_sql($query3);
                    echo "<select class='form-control' id='question3' name='question3' required style='width: 300px;'><option value=''>-Choose a question-</option>";
                    while($row3 = $db->next_row()) {
                        echo "<option value=".$row3['qid'].">".$row3['question']."</option>"; 
                    }
                    echo "</select>";
                    ?>
        </div>
         
        <div class="form-group" style ="padding: 0 0 0 40px;">
            <label for="sec-ans3">
            Answer:
            </label>
            <input type="text" class="form-control" name="answer3" id="answer3"  maxlength="40" onkeyup="checkAnswer(); return false;" placeholder="alphanumeric characters only" required style="width: 300px;">
        </div>
    

        <div class="form-group" style ="padding: 0 0 0 40px;">
            
                <button type="submit" class="btn btn-info">
                   Register
                </button>
        </div>
        
        <div class="form-group" style ="font-size: 28px;padding: 0 0 0 40px;">
            
                <p>
                    <?php 
                        echo $error_message2; 
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
        
        var goodColor = "#66cc66";
        var badColor = "#ff6666";
        
        function checkName(){
            var fullname = document.getElementById('fullname');
            var message3 = document.getElementById('confirmMessage3');
            re = /^[a-zA-Z ]+$/;
            
            if((!re.test(fullname.value)) || (fullname.value.length>50) || (fullname.value.length<6)){
                fullname.style.backgroundColor = badColor;
                message3.style.color = badColor;
                message3.innerHTML = "Name field requirement not met"
            }
            else if((re.test(fullname.value))&& (fullname.value.length<51) && (fullname.value.length>5)) {
                fullname.style.backgroundColor = goodColor;
                message3.style.color = goodColor;
                message3.innerHTML = "Name field requirement met"

            }
        }
        
        
        
        function checkPass()
        {
        
            //Store the password field objects into variables ...
        
            var pass1 = document.getElementById('password');
        
            var pass2 = document.getElementById('password2');
            //Store the Confimation Message Object ...
            var message = document.getElementById('confirmMessage');
            var message2 = document.getElementById('confirmMessage2');
            //Set the colors we will be using ...
            var goodColor = "#66cc66";
            var badColor = "#ff6666";
            
            //check that password requirement is met
            //re = /(?=.*[a-zA-Z])(?=.*[0-9]).{6,15}/;
            re= /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
            
            //re = /^[a-zA-Z ]+$/;
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

