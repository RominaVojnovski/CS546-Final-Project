<?php
function curPageDIR() {
            $isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
            $port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
            $port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
            $url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
            //take out the last part after /
            $pos = strrpos($url, '/');
            
            $url2=substr($url,0,$pos+1);
            return $url2;
}

//LOGIN
if(!empty($_POST['emaillogin']) && !empty($_POST['passwordlogin']))                
{
                    
    include("../../../dbprop.php");   
    $db2 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                    
    $email = $_POST['emaillogin'];
    $password = trim(hash('sha256',$_POST['passwordlogin']));
    //$remember_me=$_POST['remember_me'];
     
    $sql = "SELECT uid,name,email,when_confirmed FROM users WHERE email = ? AND password =?";
                  
    if (!$stmt = $db2->prepare($sql)) {
        header("Location:error.php");
        exit;
    }
    $stmt->bind_param('ss',$email,$password);
                    
    if (!$stmt->execute()) {
        header("Location:error.php");
        exit;
    }
                
    $stmt->store_result();
    $stmt->bind_result($uid,$name,$email,$confdate);
    $stmt->fetch();
    $stmt->close();
                 
               
    if((!empty($uid)) && (!empty($name)) && (!empty($email)))
    {
                        
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['loggedin'] = 1;
        $_SESSION['uid'] = $uid;
        $_SESSION['confirmed']= $confdate;
                                    
        if(isset($_POST['remember_me']))
        {                        
            /* Set cookie to last 1 year */
            setcookie('email', $_POST['emaillogin'], time()+60*60*24*365);
        } 
        else
        {
            if(isset($_COOKIE['email'])) 
            {
		          setcookie('email',"", time() - 100);
	                       
            }
        }
        //Redirect user to home/gallery page
        
        header("location:home.php");
    } 
    else
    {
        $error_id=10;
        header("location:login.php?errorid=".$error_id); 
    }
    
}

//REGISTER
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
                    header("Location:error.php");
                    exit;
                }
                $stmt->bind_param('s',$email);
                if (!$stmt->execute()) {
                    header("Location:error.php");
                    exit;
                }
                
                $stmt->store_result();
                $stmt->bind_result($email2);
                $stmt->fetch();
                $stmt->close();
                
                if((strcmp($email,$email2)) == 0){
                    $proceed_reg=false;
                    $error_id=1;
                    
                }

            }
            else{
                $proceed_reg=false;
                $error_id=2;//invalid email address
            }
           
            
            
            //passwords do not match dont proceed the registration
            if((strcmp($p1,$p2))!== 0){
                $proceed_reg=false;
                $error_id=3;//passwords don't match
                
            }
            
            
            $qid1=$_POST['question1'];
            $qid2=$_POST['question2'];
            $qid3=$_POST['question3'];
            
            //check all three security questions are different
            
            if(($qid1 == $qid2) || ($qid2 == $qid3) || ($qid1 == $qid3)){
                $proceed_reg=false;
                $error_id=4;//didnt choose 3 different questions
            }
            
            
            $answer1 = strtolower(trim(filter_var($_POST['answer1'], FILTER_SANITIZE_STRING)));
            $answer2 = strtolower(trim(filter_var($_POST['answer2'], FILTER_SANITIZE_STRING)));
            $answer3 = strtolower(trim(filter_var($_POST['answer3'], FILTER_SANITIZE_STRING)));
            
            //check all three security answers are good input
            if((strlen($answer1) == 0) || (strlen($answer2) == 0) || (strlen($answer3) == 0))
            {
                $proceed_reg=false;
                $error_id=5;//invalid answers were input
             
            }
            else{
                if((!(ctype_alnum($answer1))) || (!(ctype_alnum($answer2))) || (!(ctype_alnum($answer3)))){
                    $proceed_reg=false;       
                    $error_id=6;//answers need to be only alphanumeric
             
                }
            }
            
            
     
            //if name length less than 5 chars or greater than 50 chars dont proceed the registration
            if(((strlen($fullname))<5) || ((strlen($fullname))>50)){
                $proceed_reg=false;
                $error_id=7;//invalid name (needs to be between 5-50)
            }
            if (preg_match('/[^a-zA-Z ]/', $fullname)){
                $proceed_reg=false;  
                $error_id=8;//name needs to be only alphabetical
            }
            
            $re= '/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/';
            if((!preg_match($re,$p1)) || (strlen($p1)>15) || (strlen($p1)<6)){
                $proceed_reg=false;
                $error_id=9;
                //"Invalid password, please use alphanumeric chars min 6 max 15 chars long! Must contain at least one number and one alphabetical character!";    
            }  
            
            if($proceed_reg){
                
                 //hashed password
                $password = hash('sha256',$p1);
 
                //create activation code for user
                $activation = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
                
                
                //user has entered data in profile field....
                if(!empty($profile)){
                    $sql = "INSERT INTO users (name,password,email,user_profile,activation) VALUES (?,?,?,?,?)";
                
                    if (!$stmt = $db->prepare($sql)) {
                        header("Location:error.php");
                        exit;
                    }
                    $stmt->bind_param('sssss',$fullname,$password,$email,$profile,$activation);     
                
                }   
                else {
                    
                    $sql = "INSERT INTO users (name,password,email,activation) VALUES (?,?,?,?)";
                    
                    if (!$stmt = $db->prepare($sql)) {
                        header("Location:error.php");
                        exit;
                    }
                    
                    $stmt->bind_param('ssss',$fullname,$password,$email,$activation);
            
                }
                
                if (!$stmt->execute()) {
                    header("Location:error.php");
                    exit;
                }
                //$stmt->close();
                   
            
                $uid=$db->insert_id;
                
                //insert data into user_pwd_recovery table total of 3 times for 3 security question/answer 
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        header("Location:error.php");
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid1,$answer1);
                                  
                if (!$stmt->execute()) {
                    header("Location:error.php");
                    exit;
                }
                
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        header("Location:error.php");
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid2,$answer2);
                                  
                if (!$stmt->execute()) {
                    header("Location:error.php");
                    exit;
                }
                $sql = "INSERT INTO user_pwd_recovery (uid,qid,answer) VALUES (?,?,?)";
                if (!$stmt = $db->prepare($sql)) {
                        header("Location:error.php");
                        exit;
                }
                
                $stmt->bind_param('iis',$uid,$qid3,$answer3);
                                  
                if (!$stmt->execute()) {
                    header("Location:error.php");
                    exit;
                }
                $stmt->close();
                
                
                $link=curPageDIR().'verify.php?activation='.$activation.'&email='.$email;
                //send verification email
                //swift mail library   
                $mailer = Swift_Mailer::newInstance($transport);

                $message = Swift_Message::newInstance('Pix Gallery Registration')
                        ->setFrom(array('pixgalleryweb@gmail.com' => 'admin'))
                        ->setTo(array($email))
                        ->setBody('Thanks for signing up. Please click on the link to confirm your registration: '.$link);
                $result = $mailer->send($message);    
                //redirect to complete php page to inform user to check their email 
                header("Location:complete.php");
                
            }//end proceed reg
            else{
                header("Location:login.php?errorid=".$error_id);
            
            
            }
                   
}//end if- post variables are set


?>