<?php
    
    include("dboperation.php");
    //Twig loader
    require_once '../include/Twig-1.15.1/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();
    //Twig use $loader variable to locate the templates.
    $loader = new Twig_Loader_Filesystem(dirname(dirname(__FILE__))."/".'twigtpl');
    //Twig_Environment is used to store the configuration.
    $twig = new Twig_Environment($loader);

    $templatename = "gallery.tpl";
    $dboperation = new dboperation();
    session_start();
    $userid = $_SESSION['uid'];
    $uname = $_SESSION['name'];
    $confirmed_reg_date = $_SESSION['confirmed'];

    if(empty($confirmed_reg_date) && isset($userid) ){
        if($user_res = $dboperation->getUser($userid)){
          $user_arr = $dboperation->next_res_row($user_res);
          $confirmed_reg_date = $user_arr['when_confirmed'];
          $_SESSION['confirmed'] = $confirmed_reg_date;
          //error_log("\nwhen confirmed ".$confirmed_reg_date. " ".$userid, 3, "/var/tmp/my-errors.log");
           
        }
    }

    if(isset($_GET['viewtype']))
      $viewtype = $_GET['viewtype'];
    else
      $viewtype = "";
    
  
    if((!isset($_SESSION['loggedin']) && !isset($userid)) || empty($confirmed_reg_date)){
       header("location:login.php");
    }else{
      
       $alm_res = $dboperation->getUserAlbum($userid);
       $shared_album_res = $dboperation->getSharedAlbum($userid);  
        
       if($alm_res && $shared_album_res){

          //error_log("\n album arr count*** ".$alm_res->num_rows, 3, "/var/tmp/my-errors.log");

          $album_arr = $dboperation->getAlbumArray($alm_res);
          $shared_album_arr = $dboperation->getSharedAlbumArray($shared_album_res); 

          //error_log("\n album arr count ".count($album_arr), 3, "/var/tmp/my-errors.log");
          //error_log("\n shared album arr count ".count($shared_album_arr), 3, "/var/tmp/my-errors.log");
          //If thumnail view
          if(empty($viewtype)){
            $newarr = getHomePagePath($album_arr);
            $newsharedalbumarr = getHomePagePath($shared_album_arr);
         
            echo $twig->render($templatename, array('album_arr'=>$newarr,'shared_album' => $newsharedalbumarr,'uname' => $uname,'viewtype' => 'thumb')); 
          }else{
            echo $twig->render($templatename, array('album_arr'=>$album_arr,'shared_album' => $shared_album_arr,'uname' => $uname,'viewtype' => 'list')); 
          }
          
         

       }elseif($alm_res){
          $album_arr = $dboperation->getAlbumArray($alm_res);
          if(empty($viewtype)){  
            $newarr = getHomePagePath($album_arr);
            echo $twig->render($templatename, array('album_arr'=>$newarr, 'uname' => $uname,'viewtype' => 'thumb' ));   
          }else{
             echo $twig->render($templatename, array('album_arr'=>$album_arr,'uname' => $uname,'viewtype' => 'list')); 
          }
       }elseif($shared_album_res){

          $shared_album_arr = $dboperation->getSharedAlbumArray($shared_album_res); 
          if(empty($viewtype)){   
            $newsharedalbumarr = getHomePagePath($shared_album_arr);
            echo $twig->render($templatename, array('shared_album'=>$newsharedalbumarr, 'uname' => $uname,'viewtype' => 'thumb')); 
          }else{
             echo $twig->render($templatename, array('shared_album'=>$shared_album_arr, 'uname' => $uname,'viewtype' => 'list'));
          }
       } 
       else{
          echo $twig->render($templatename,array('uname' => $uname,'viewtype' => 'thumb'));
       }

    }
    
  function getHomePagePath($album_arr){

      foreach($album_arr as $id => &$otherinfo){
              //error_log("\n album path ".$otherinfo['path'], 3, "/var/tmp/my-errors.log");
              $apath =  $otherinfo['path'];
              $frelpath = readThumbnailImagePath($apath);
              //echo $frelpath."<br/>";
              if(!empty($frelpath))
                $otherinfo['albumpgpath'] = $frelpath;
       }// for each

       $rows = ceil(count($album_arr)/6);
       $newarr = array(); 
       $counter = 0; 
       // Make a 2 dimentions array of list of albums with 4 columns in each row. 
       for($inx=0;$inx<$rows;$inx++){
           $newarr[$inx] = array();
           for($col = 0 ;$col<6;$col++){
              if($counter < count($album_arr)){
                 //error_log("\n row:: ".$inx." col::".$col."counter ::".$counter, 3, "/var/tmp/my-errors.log");
                 array_push($newarr[$inx],$album_arr[$counter]);
                 //$newarr[$inx] = array($col => $album_arr[$counter]);
                 $counter ++;
               }
           }
               //error_log("\n count of singal array  ".count($newarr[$inx]), 3, "/var/tmp/my-errors.log");
        }  
        
        return $newarr;   

 
  }

  function readThumbnailImagePath($apath){
    $thums_path = $apath."/"."thumbs"  ;
    $documentroot = dirname($_SERVER['DOCUMENT_ROOT'])."/".'uploaded_files'."/";
    $frelpath ="";
    if(file_exists($thums_path)){
       if ( $handle = opendir($thums_path)) {
           while ( ($file = readdir($handle)) !== false ) {
              $ext = get_file_extension($file);
              $img_type = getImageType($ext);
              if($img_type!=''){
                //error_log("\n thum path ".$thums_path. " ***file ".$file, 3, "/var/tmp/my-errors.log");
                $frelpath = str_replace($documentroot,'',$thums_path);
                $frelpath = $frelpath."/".$file;
                //error_log("\n album photo rel path ".$frelpath, 3, "/var/tmp/my-errors.log");
                //return $frelpath;
                break;
              }//if
                  
           }//while
          
        }//if
      
    }else{
        //If thumbs folder does not exists in album
        //Make a thumbs dir
        mkdir($thums_path,0777,true);
        
        if ( $handle = opendir($apath)) {
        while ( ($file = readdir($handle)) !== false ) {
            $fpath = $apath."/".$file;  
			      if ( strcmp(filetype($fpath),"file")==0 ) {
                $ext = get_file_extension($file);
                $img_type = getImageType($ext);
               if($img_type!=''){
                  if(empty($frelpath)){
                    $frelpath = str_replace($documentroot,'',$thums_path);
                    $frelpath = $frelpath."/".$file;
                  }  
                  resizeExistingImage($thums_path,$file,$apath,$img_type) ;
                }
  
			      }//if is_dir close
		      }// while close
        } //if close
    }
    return $frelpath;
  }


  function getImageType($ext) {
		  if ( preg_match('/jpg|jpeg/i', $ext) ) {
			    return 'jpg';
		  } else if ( preg_match('/png/i', $ext) ) {
			    return 'png';
		  } else if ( preg_match('/gif/i', $ext) ) {
			    return 'gif';
		  } else {
			    return '';
		  }
	}

  function get_file_extension($file_name)  
  {  
    return substr($file_name,strrpos($file_name,'.'));  
  } 
  function resizeExistingImage($thumb_path,$file,$albumpath,$img_type){
    $max_height = 140 ;
    $max_width = 140;

    $fpath =  $albumpath."/".$file; 
       if ( ! file_exists($thumb_path."/".$file) ) {   
            if ( $img_type == 'jpg' ) {
			         $src_hand = imagecreatefromjpeg($fpath);
		        } else if ( $img_type == 'png' ) {
			         $src_hand = imagecreatefrompng($fpath);
		        } else if ( $img_type == 'gif' ) {
			         $src_hand = imagecreatefromgif($fpath);
		        }
            if($src_hand){
              if ( ($oldW = imagesx($src_hand)) < ($oldH = imagesy($src_hand)) ) 
                 {
                    $newW = $oldW * ($max_width / $oldH);
                    $newH = $max_height;
                 } else {
                    $newW = $max_width;
                    $newH = $oldH * ($max_height / $oldW);
                 }
               // create a black block image.    
               $new_imgres = imagecreatetruecolor($newW, $newH);
               //copy and resizing the original image into thumbnail image.
               imagecopyresampled($new_imgres, $src_hand, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);   
               if ( $img_type == 'jpg' ) {
			            imagejpeg($new_imgres, $thumb_path."/".$file);
		           } else if ( $img_type == 'png' ) {
			            imagepng($new_imgres, $thumb_path."/".$file);
		           } else if ( $img_type == 'gif' ) {
			            imagegif($new_imgres, $thumb_path."/".$file);
		           }
		           imagedestroy($new_imgres);
		           imagedestroy($src_hand);
                                   
           }
       } 
  }


?>
