<?php
include("dboperation.php");
session_start();
//$_SESSION['userid']=1; 
// Get the userid from session.
$userid = $_SESSION['uid'];
//error_log("no problem ajax call".$userid."\n", 3, $error_logfile);
$dboperation = new dboperation();
if(isset($_POST['action']) && $_POST['action'] == "check_album_exists"){
	if(isset($_POST['title'])){
		$title = trim($_POST['title']);	
		if($alm_res = $dboperation->getAlbumObj($title,$userid)){
      //error_log($title ." ajax call\n", 3, $error_logfile);
			echo $title;
		}else{
      //error_log("FALSE ajax call\n", 3, $error_logfile);
			echo "false";
		}	
	}
}

$dboperation->disconnect_db();

?>
