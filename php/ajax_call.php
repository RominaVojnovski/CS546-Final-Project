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
}elseif(isset($_POST['action']) && $_POST['action'] == "get_user_info"){

  //error_log("get_user_info ajax call\n", 3, "/var/tmp/my-errors.log");
  if(isset($_POST['searchstr'])){
    $str = trim($_POST['searchstr']);
    
    if($user_res = $dboperation->getUserByName($str)){

     // error_log("\nstr".$str, 3,"/var/tmp/my-errors.log");

      $user_arr = $dboperation->getUserArr($user_res);
      $result = "<div class='col-md-12'><ul class='dropdown-menu col-md-12 search-suggestions' >";
      foreach($user_arr as $k=>$val){
        $result.= "<li class=''><a class='clickablelinkhere' id='".$k."' href='javascript:void(0);'>".htmlspecialchars($val)."</a></li>";
      }     
      $result.="</ul></div>";
      echo $result;

    }else{
      echo "false";
    }

  }
}elseif(isset($_POST['action']) && $_POST['action'] == "sharealbum"){
  //error_log("sharealbum action\n", 3, "/var/tmp/my-errors.log");
  if(isset($_POST['albumid']) && isset($_POST['uids'])){
    $albumid = $_POST['albumid'];
    $uids = $_POST['uids'];
    $result = false;
    //error_log("albumid ".$albumid." uids".$uids, 3, "/var/tmp/my-errors.log");

    $uid_arr = explode("#", $uids);
    $dboperation->startTransaction();
    foreach($uid_arr as $uid){
      if(!empty($uid)){
        if($dboperation->isAlbumAlreadyShared($albumid,$uid))
          $result = true;
        else{
          if($dboperation->insertSharedAlbums($albumid,$uid))
            $result = true;
          else
            $result = false;
        }
      }

    }
    $dboperation->endTransaction();

    echo $result;

  }
}  

$dboperation->disconnect_db();

?>
