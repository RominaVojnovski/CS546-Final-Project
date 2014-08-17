<?php
  include("dboperation.php");
//Twig loader
  require_once '../include/Twig-1.15.1/lib/Twig/Autoloader.php';
  Twig_Autoloader::register();
  //Twig use $loader variable to locate the templates.
  $loader = new Twig_Loader_Filesystem(dirname(dirname(__FILE__))."/".'twigtpl');
  //Twig_Environment is used to store the configuration.
  $twig = new Twig_Environment($loader);
  $templatename = "albumphotos.tpl";
  session_start();
  
  $dboperation = new dboperation();
  
  //echo $twig->render($templatename); 
  // If session doesnot exist redirect user to login page.
  if((!isset($_SESSION['loggedin']) && !isset($_SESSION['uid'])) || empty($_SESSION['confirmed'])){
          header("location:login.php");
  }
  elseif(isset($_GET['albumid'])){
       $albumid = $_GET['albumid'];
       $userid =  $_SESSION['uid'];
       $uname = $_SESSION['name']; 
       //Check for view type. 
        if(isset($_GET['viewtype']))
          $viewtype = $_GET['viewtype'];
        else
          $viewtype = "";

       //check only authorized user is trying to access the given album.
       if($almres = $dboperation->getAlbumById($albumid,$userid)){

          $album_arr = $dboperation->next_res_row($almres);
          $photores = $dboperation->getAlbumPhotos($albumid);
          $photoarr = $dboperation->getPhotoArr($photores);
          //echo "title my album :::::".$album_arr['title']." path ::".$album_arr['album_path'];
          $title = $album_arr['title'];
          $apath = $album_arr['album_path'];
          $when_posted = $album_arr['when_posted'];
          if(empty($viewtype)){
            $newarr = getPhotoThumnailPath($photoarr,$apath);
            $dboperation->disconnect_db();
        
            echo $twig->render($templatename, array('photo_arr'=>$newarr,'title' => $title,'uname' => $uname,'viewtype' => 'thumb','albumid' =>$albumid,'posted_date'=>$when_posted)); 
          }else{
            $dboperation->disconnect_db();
      
            echo $twig->render($templatename, array('photo_arr'=>$photoarr,'title' => $title,'uname' => $uname,'viewtype' => 'list','albumid' =>$albumid,'posted_date'=>$when_posted)); 
          }
                    

       }
       elseif($almres = $dboperation->getSharedAlbumById($albumid,$userid)) {
           $album_arr = $dboperation->next_res_row($almres); 
           $title =  $album_arr['title'];
           $apath = $album_arr['album_path'];
           $when_posted = $album_arr['when_shared']; 
           $sharedby =  $album_arr['userid'];
           //Get photos based on albumid
           $photores = $dboperation->getAlbumPhotos($albumid);
           $photoarr = $dboperation->getPhotoArr($photores);

           //Get user information who has shared this album 
           if($user_res = $dboperation->getUser($sharedby)){
              $user_arr = $dboperation->next_res_row($user_res); 
              $sharedby = $user_arr['name'];
            }

           if(empty($viewtype)){
            $newarr = getPhotoThumnailPath($photoarr,$apath);
            $dboperation->disconnect_db();
          
            echo $twig->render($templatename, array('photo_arr'=>$newarr,'title' => $title,'uname' => $uname,'viewtype' => 'thumb','albumid' =>$albumid,'posted_date'=>$when_posted,'sharedby'=>$sharedby)); 
          }else{
            $dboperation->disconnect_db();
  
            echo $twig->render($templatename, array('photo_arr'=>$photoarr,'title' => $title,'uname' => $uname,'viewtype' => 'list','albumid' =>$albumid,'posted_date'=>$when_posted,'sharedby'=>$sharedby)); 
          } 
       }
       else{
         $dboperation->disconnect_db(); 
         echo "You are not allowed to access this album"; 
            
       }         
  }

 

  function getPhotoThumnailPath($photoarr,$apath){

    $documentroot = dirname($_SERVER['DOCUMENT_ROOT'])."/".'uploaded_files'."/";
    $thums_path = $apath."/"."thumbs"  ;
    
    foreach($photoarr as &$val){
     // echo "<br/>photo id ".$val['photoid']."photo path ".$val['path'];
      $path = $val['path'];
      $pname = substr($path,strrpos($path,'/')+1);
      //echo "<br/>pname ".$pname;
      
      $pthumpath =   $thums_path."/".$pname;

      if(file_exists($thums_path) && file_exists($pthumpath)){
          $prelpath = str_replace($documentroot,'',$pthumpath);
          $val['relpath']  = $prelpath;
      }//end if
    
    }// for each


       $rows = ceil(count($photoarr)/6);
       $newarr = array(); 
       $counter = 0; 
       // Make a 2 dimentions array of list of albums with 4 columns in each row. 
       for($inx=0;$inx<$rows;$inx++){
           $newarr[$inx] = array();
           for($col = 0 ;$col<6;$col++){
              if($counter < count($photoarr)){
                 //error_log("\n row:: ".$inx." col::".$col."counter ::".$counter, 3, "/var/tmp/my-errors.log");
                 array_push($newarr[$inx],$photoarr[$counter]);
                 $counter ++;
               }
           }
               //error_log("\n count of singal array  ".count($newarr[$inx]), 3, "/var/tmp/my-errors.log");
        }  
        
        return $newarr;   

  }


?> 
