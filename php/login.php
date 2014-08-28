<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        
        <!--Custom-->
        <script src="../js/snap.svg-min.js"></script>
        <script src="../js/modernizr.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/login.css" />
		<link rel="stylesheet" type="text/css" href="../css/animate-custom.css" />
        <link rel="stylesheet" type="text/css" href="../css/ns-default.css" />
        <link rel="stylesheet" type="text/css" href="../css/ns-style-other.css" />
        
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
                    <?php
                    if(isset($_SESSION['loggedin']))
                    {
                        ?>
                    <li> 
                        <a class="text-info" href="login.php"><strong>Welcome <?php if(isset($_SESSION['name'])) echo $_SESSION['name'] ?></strong></a>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

                <?php
                if(empty($_SESSION['loggedin']))
                {
                    
                    //error handling
                    if(isset($_GET['errorid'])){
                        $eid=$_GET['errorid'];
                        
                        switch($eid){
                            case 1:
                                $errormess="You have already registered please, login or reset your password";
                                break;
                            case 10:
                                $errormess="Invalid login";
                                break;
                            default:
                                $errormess='Registration error!';
                        } 
                    }
                ?>
                <section>
                <div id="container_demo" >
                    <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="loginscript.php" method="post" autocomplete="on"> 
                                <h1>LOG IN</h1>
                                <p style="color:rgba(247, 8, 8, 0.98)">
                                <?php
                                    if(isset($errormess)){
                                        echo $errormess;
                                    }
                                ?>
                                </p>
                                <p> 
                                    <label for="emaillogin" class="uname" data-icon="e" >Email</label>
                                    <input id="emaillogin" maxlength="50"name="emaillogin" required="required" type="email" placeholder="someone@example.com" style="color: #656565" value = "<?php if (isset($_COOKIE['email']))echo $_COOKIE['email']; ?>"/>
                                </p>
                                <p> 
                                    <label for="passwordlogin" class="youpasswd" data-icon="p">Password </label>
                                    <input id="passwordlogin" name="passwordlogin" maxlength="15" style="color: #656565" required="required" type="password" placeholder="eg. X8df90EO" /> 
                                </p>
                                <p class="keeplogin"> 
									<input type="checkbox" name="remember_me" id="remember_me" /> 
									<label for="remember_me">Remember Me</label>
								</p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" /> 
								</p>
                                <p class="change_link">
									<a href="resetpass.php" class="to_register">Reset Password</a>
									<a href="#toregister" class="to_register">Register</a>
								</p>
                            </form>
                        </div>

                        <div id="register" class="animate form">
                            <form  action="loginscript.php" method="post" autocomplete="on"> 
                                <h1> Sign up </h1> 
                                <p> 
                                    <label for="fullname"  data-icon="u" >Name</label>
                                    <input id="fullname" style="color: #656565" name="fullname" required="required" type="text" placeholder="Your name, use only alphabetical characters"  maxlength="50"/> 
                                </p>
                                <p> 
                                    <label for="email" class="youmail" data-icon="e" > Email</label>
                                    <input id="email" style="color: #656565"name="email" required="required" type="email" placeholder="mysupermail@mail.com"  maxlength="50"/> 
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p">Password </label>
                                    <input id="password" style="color: #656565" maxlength="15" name="password" required="required" type="password" placeholder="eg. X8df90EO,please use 6-15 alphanumeric characters" onkeyup="checkPass(); return false;"/>
                                </p>
                                <p> 
                                    <label for="password2" class="youpasswd" data-icon="p">Please confirm your password </label>
                                    <input id="password2" style="color: #656565" name="password2" required="required" type="password" placeholder="eg. X8df90EO" maxlength="15" onkeyup="checkPass(); return false;"/>
                                </p>
                                <p style="color: #656565">
                                    <label for="user_profile" class="uname"> User Profile (optional):
                                    </label>
                                    <textarea name="user_profile" id="user_profile" maxlength="150"placeholder=" About you" style="padding:10px 5px 10px 32px;width: 387px;height:80px;resize:none"></textarea>
                                </p>
                                <P id="warningp" style="display:none; color:#ff6666">
                                    Please choose 3 different questions!
                                </P>
                                <p>
                                    <label for="question1" class="uname"> Security question 1 (required):
                                    </label>
                                    <?php 
                                    include('mysqli_class.php');
                                    $db=new database();
                                    $query = "SELECT qid,question FROM pwd_recovery_ques";
                                    $res=$db->send_sql($query);
                                    echo "<select style='width:102%'class='form-control' id='question1' name='question1' required ><option value=''>-Choose a question-</option>";
                                        while($row = $db->next_row()) {
                                            echo "<option value=".$row['qid'].">".$row['question']."</option>"; 
                                        }
                                    echo "</select>";
                                    ?>
                                </p>
                                <p>
                                    <label for="answer1" class="uname">Answer:</label>
                                    <input id="answer1" style="color: #656565" name="answer1"  maxlength="40" required="required" type="text"/>
                                </p>
                                <p>
                                    <label for="question2" class="uname" > Security question 2 (required):
                                    </label>
                                    <?php 
                                    
                                    $db2=new database();
                                    $query2 = "SELECT qid,question FROM pwd_recovery_ques";
                                    $res2=$db2->send_sql($query2);
                                    echo "<select style='width:102%' class='form-control' id='question2' name='question2' required><option value=''>-Choose a question-</option>";
                                        while($row2 = $db2->next_row()) {
                                            echo "<option value=".$row2['qid'].">".$row2['question']."</option>"; 
                                        }
                                    echo "</select>";
                                    ?>
                                </p>
                                <p>
                                    <label for="answer2" class="uname">Answer:</label>
                                    <input id="answer2" style="color: #656565" name="answer2" maxlength="40" required="required" type="text"/>
                                </p>
                                <p>
                                    <label for="question3" class="uname" > Security question 3 (required):
                                    </label>
                                    <?php 
                                    
                                    $db3=new database();
                                    $query3 = "SELECT qid,question FROM pwd_recovery_ques";
                                    $res3=$db3->send_sql($query3);
                                    echo "<select style='width:102%' class='form-control' id='question3' name='question3' required><option value=''>-Choose a question-</option>";
                                        while($row3 = $db3->next_row()) {
                                            echo "<option value=".$row3['qid'].">".$row3['question']."</option>"; 
                                        }
                                    echo "</select>";
                                    ?>
                                </p>
                                <p>
                                    <label for="answer3" class="uname">Answer:</label>
                                    <input id="answer3" style="color: #656565" name="answer3" maxlength="40" required="required" type="text"/>
                                </p>
                                
                                
                               
                                <p class="signin button"> 
									<input type="submit" value="Sign up" id="registerbtn"  disabled="disabled" /> 
								</p>
                              
                                <p class="change_link">  
									Already a member ?
									<a href="#tologin" class="to_register"> Go and log in </a>
								</p>
                            </form>
                        </div>
						
                    </div>
                </div>        
            </section>
                <?php
                }
                else if(!empty($_SESSION['loggedin']))
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
                            header("Location:error.php");
                            exit;
                        }
                        $stmt->bind_param('is',$uid,$oldpassword);
                    
                        if (!$stmt->execute()) {
                            header("Location:error.php");
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
                                        header("Location:error.php");
                                        exit;
                                    }
                                    $stmt->bind_param('si',$newpassword,$uid);
                                    if (!$stmt->execute()) {
                                        header("Location:error.php");
                                        exit;
                                    }
                                    $stmt->close();
                                    //Password has been changed
                                    $messid=1;
                                    header("Location:login.php?request_num=".$messid);
                                }
                                else{
                                    //"Invalid password, please use alphanumeric chars min 6 max 15 chars long! Must contain at least one number and one alphabetical character!";
                                    $messid=2;
                                    header("Location:login.php?request_num=".$messid);
                                
                                }
                            }
                            else{
                                //New password and confirm password do not match!";
                                $messid=3;
                                header("Location:login.php?request_num=".$messid);
                            }
                        }//end if user put in correct old password
                        //user didnt enter his old password     
                        else{
                        
                            //Sorry we could not process your request please try again!";    
                            $messid=4;
                            header("Location:login.php?request_num=".$messid);
                        }
                    }//end if user is requesting a password change
                    
                    
                    
                    if(isset($_GET['request_num'])){
                        $rid=$_GET['request_num'];
                        switch($rid){
                            case 1:
                                $mess="Password has been changed!";
                                break;
                            case 2:
                                $mess="Sorry please try again!";
                                break;
                            case 3:
                                $mess="New and confirm password do not match!";
                                break;
                            case 4:
                                $mess="You did not enter your correct password!";
                                break;
                        } 
                    }
                    
                    
                    
            ?>   
                
            <div class="container">
            <br/>    
            <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                        <small>Account Information</small>
                        </h1>
                </div>
            </div>
                
                
            <div class="row">
                
                <div class="col-md-6 col-md-offset-3">
                    
                        <p>Hi <?php echo $_SESSION['name'] ?>! Your email is <?php echo $_SESSION['email'] ?></p>
                   
                </div> 
            </div>
                <?php
                if(isset($mess))
                {
                    ?>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <p>
                                <?php echo $mess ?>
                            </p>
                        </div> 
                    </div>
                    
                
                <?php
                }      
                ?>
                
                
                    
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                    <h3>Change password</h3>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-6 col-md-offset-3">
                <form role="form" method="post" action="login.php" name="loginform" id="loginform">
                    <div class="form-group">
                        <label for="oldpassword">Old password</label>
                        <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Enter password" required style="width: 400px">
                    </div>
                    <div class="form-group">
                        <label for="newpassword">New password</label>
                        <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New password" required style="width: 400px"><span id="confirmMessage2" class="confirmMessage"></span><p class="help-block" style="width: 400px">
                    Min: 6 characters Max: 15 characters (Alphanumeric only, at least one numeric and at least one alphabetic character)
                    </p>
                    </div>
                    <div class="form-group">
                        <label for="newpassword2">Confirm new password</label>
                        <input type="password" class="form-control" name="newpassword2" id="newpassword2" placeholder="Confirm new password" required style="width: 400px"><span id="confirmMessage" class="confirmMessage"></span><p class="help-block">
                        Password reset? <a href= 'resetpass.php'>Click Here</a>
                        </p>
                    </div>
                    <div class="form-group">
                        <button type="submit" id="changepass-btn" name="changepass-btn" value="login" class="btn btn-info">Change Password</button>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                       
                            <p>
                            <?php 
                                echo $error_message; 
                            ?>
                            </p>
                        </div>
                    </div>
                </form>
                </div>
                </div>
                
                
                <!--for notification style animated box-->
                <div class="notification-shape shape-box" id="notification-shape" data-path-to="m 0,0 500,0 0,500 -500,0 z">
			         <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 500 500" preserveAspectRatio="none">
				    <path d="m 0,0 500,0 0,500 0,-500 z"/>
			         </svg>
		        </div>
                <button style="display:none" id="notification-trigger" class="progress-button">
						<span class="content"></span>
						<span class="progress"></span>
				</button>
                <!--end notification style animated box-->
        
                </div>
                <?php
                }
                ?>
        
    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/classie.js"></script>
    <script src="../js/notificationFx.js"></script>
    <script>        
   
        
        $('#fullname,#email, #password, #password2, #answer1, #answer2, #answer3, #question1, #question2, #question3,#answer1,#answer2,#answer3').bind('change', function(){
    
            if(allValidated() && checkPass() && checkName() && checkAnswer1() && checkAnswer2()&& checkAnswer3())
            {
                
                $('#registerbtn').removeAttr('disabled');
        
            }
        });
        
       

        function allValidated() {
            var validated= true;
            
            $("label[for=email]").css("color", "rgb(126, 139, 141)");
            $("label[for=password]").css("color", "rgb(126, 139, 141)");
            $("label[for=password2]").css("color", "rgb(126, 139, 141)");
            $("label[for=answer1]").css("color", "rgb(126, 139, 141)");
            $("label[for=answer2]").css("color", "rgb(126, 139, 141)");
            $("label[for=answer3]").css("color", "rgb(126, 139, 141)");
            $("label[for=question1]").css("color", "rgb(126, 139, 141)");
            $("label[for=question2]").css("color", "rgb(126, 139, 141)");
            $("label[for=question3]").css("color", "rgb(126, 139, 141)");
            

            checkAnswer1();
            checkAnswer2();
            checkAnswer3();
            checkName();
            
            if($('#fullname').val() == '')
            {
                validated = false;
                $("label[for=fullname]").css("color", "#ff6666");
            }
            
            if($('#email').val() == '')
            {
                validated = false;
                $("label[for=email]").css("color", "#ff6666");
            }
            if($('#password').val() == '')
            {
                validated  = false;
                $("label[for=password]").css("color", "#ff6666");
            }
            if($('#password2').val() == '')
            {
                validated  = false;
                $("label[for=password2]").css("color", "#ff6666");
            }
            if($('#answer1').val() == '')
            {
                filled = false;
                $("label[for=answer1]").css("color", "#ff6666");
            }
            if($('#answer2').val() == '')
            {
                validated = false;
                $("label[for=answer2]").css("color", "#ff6666");
            }
            if($('#answer3').val() == '')
            {
                validated  = false;
                $("label[for=answer3]").css("color", "#ff6666");
            }
            if($('#question1').val() == '')
            {
                validated  = false;
                $("label[for=question1]").css("color", "#ff6666");
            }
            if($('#question2').val() == '')
            {
               validated  = false;
                $("label[for=question2]").css("color", "#ff6666");
            }
            if($('#question3').val() == '')
            {
                validated  = false;
                $("label[for=question3]").css("color", "#ff6666");
            }
            if(($('#question1').val() == $('#question2').val()) || ($('#question2').val() == $('#question3').val()) || ($('#question1').val() == $('#question3').val()))
            {
                validated  = false;
                $('#registerbtn').attr('disabled','disabled');
                $("#warningp").css('display',''); 
               
            }
            
            return validated;
        }
  
        function checkPass()
        {
                        
            var validated2= true;
            //Store the password field objects into variables ...
        
            var pass1 = document.getElementById('password');
            var pass2 = document.getElementById('password2');
           
            
         
            
 
            //Set the colors we will be using ...
            var goodColor = "#fff";
            var badColor = "#ff6666";
            
            //check that password requirement is met
            //re = /(?=.*[a-zA-Z])(?=.*[0-9]).{6,15}/;
            re= /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
            
            //re = /^[a-zA-Z ]+$/;
            if((pass1.value.length < 6) || (pass1.value.length > 15) || !re.test(pass1.value))             
            {
             
                pass1.style.backgroundColor = badColor;
                validated2= false;
              
            
            }
            if((pass1.value.length>5) && (pass1.value.length<15) && re.test(pass1.value))
            {
           
                pass1.style.backgroundColor = goodColor;
         
            }

            //Compare the values in the password field 
            //and the confirmation field
            if((pass1.value == pass2.value) && (pass2.value.length > 0))
            {
                //The passwords match. 
                //Set the color to the good color
                pass2.style.backgroundColor = goodColor;
        
            }
        
        
            else if((pass1.value !== pass2.value) && (pass2.value.length > 0))
            {
               
                //The passwords do not match.
                //Set the color to the bad color
                pass2.style.backgroundColor = badColor;
                validated2= false;
          
            }
            return validated2;
        }
        
        
        
        function checkName()
        {
            var validated3=true;
            var fullname = document.getElementById('fullname');
            re = /^[a-zA-Z ]+$/;
            
            if((!re.test(fullname.value)) || (fullname.value.length>50) || (fullname.value.length<6)){
                $("label[for=fullname]").css("color", "#ff6666");
                validated3=false;
                
            }
            else if((re.test(fullname.value))&& (fullname.value.length<51) && (fullname.value.length>5)) {
                $("label[for=fullname]").css("color", "rgb(126, 139, 141)");
                validated3=true;
                

            }
            return validated3;
        }
        
        function checkAnswer1()
        {
            var validated4=true;
            var ans1 = document.getElementById('answer1');

            re = /^[a-z0-9]+$/i;
            
            if((!re.test(ans1.value)) || (ans1.value.length>50) || (ans1.value.length<1)){
                $("label[for=answer1]").css("color", "#ff6666");
                validated4=false;
                
            }
            
            else if((re.test(ans1.value))&& (ans1.value.length<51) && (ans1.value.length>1)) {
                $("label[for=answer1]").css("color", "rgb(126, 139, 141)");
                validated4=true;
            }

            return validated4;
        }
        
        
        function checkAnswer2()
        {
            var validated5=true;
            var ans2 = document.getElementById('answer2');

            re = /^[a-z0-9]+$/i;
            
            if((!re.test(ans2.value)) || (ans2.value.length>50) || (ans2.value.length<1)){
                $("label[for=answer2]").css("color", "#ff6666");
                validated5=false;
                
            }
            
            else if((re.test(ans2.value))&& (ans2.value.length<51) && (ans2.value.length>1)) {
                $("label[for=answer2]").css("color", "rgb(126, 139, 141)");
                validated5=true;
            }

            return validated5;
        }
        
        function checkAnswer3()
        {
            var validated6=true;
            var ans3 = document.getElementById('answer3');

            re = /^[a-z0-9]+$/i;
            
            if((!re.test(ans3.value)) || (ans3.value.length>50) || (ans3.value.length<1)){
                $("label[for=answer3]").css("color", "#ff6666");
                validated6=false;
                
            }
            
            else if((re.test(ans3.value))&& (ans3.value.length<51) && (ans3.value.length>1)) {
                $("label[for=answer3]").css("color", "rgb(126, 139, 141)");
                validated6=true;
            }

            return validated6;
        }
        
       
        var sessConfirmed = '<?php if(isset($_SESSION['confirmed'])) { echo $_SESSION['confirmed']; }?>';
        var sessLoggedin = '<?php if(isset($_SESSION['loggedin'])) { echo $_SESSION['loggedin']; }?>';
       
        if((sessConfirmed =='') && (sessLoggedin !='') ){
            alertUser();
        }
        function alertUser() {
            
             var svgshape = document.getElementById( 'notification-shape' ),
					s = Snap( svgshape.querySelector( 'svg' ) ),
					path = s.select( 'path' ),
					pathConfig = {
						from : path.attr( 'd' ),
						to : svgshape.getAttribute( 'data-path-to' )
					},
					
            
            bttn = document.getElementById( 'notification-trigger' );            
                        
					setTimeout( function() {

						path.animate( { 'path' : pathConfig.to }, 300, mina.easeinout );

						
						// create the notification
						var notification = new NotificationFx({
							wrapper : svgshape,
							message : '<p><span class="icon icon-bulb"></span> You have not confirmed registration yet!</p>',
							layout : 'other',
							effect : 'cornerexpand',
							type : 'notice', // notice, warning or error
							onClose : function() {
								setTimeout(function() {
									path.animate( { 'path' : pathConfig.from }, 300, mina.easeinout );
								}, 100 );
							}
						});

						// show the notification
						notification.show();

					}, 1200 );
        } 

        </script>
   
    </body>
</html>