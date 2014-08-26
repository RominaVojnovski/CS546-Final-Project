<?php
session_start();
if(empty($_SESSION['confirmed'])){
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pix Gallery Photo</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/4-col-portfolio.css" rel="stylesheet">

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
                    <li>
                        <a href="../php/logout.php">Logout</a>
                    </li>

                </ul>
                <ul class="nav navbar-nav  navbar-right">
                    <li> 
                        <a class="text-info" href="login.php"><strong>Welcome <?php if(isset($_SESSION['name'])){ echo $_SESSION['name']; } ?></strong></a>
                    </li>                
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">


        <!-- Page Heading -->
                
        <div class="col-md-1 navbar-right">
                
        </div>
     
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header">
                    <small><a class= "text-info" href="getAlbum.php?albumid=<?php if(isset($_GET['albumid'])) { echo $_GET['albumid']; }?>">&larr;Back</a></small>
                </h2>
            </div>
        </div>
        <?php
                
            include("mysqli_class.php");
            //include("../../../dbprop.php");   
                    
            $db1 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $db=new database();
            if(isset($_SESSION['uid'])){
                $uid=$_SESSION['uid'];
            }
            if(isset($_GET['photoid'])){
                $photoid=$_GET['photoid'];
            }
            if(isset($_GET['albumid']))  {
                $albumid=$_GET['albumid'];
            }
            //check if query string parameter of photoid is set and not empty
            
            if(!empty($photoid) && !empty($albumid))
            {
                if(isset($_SESSION['photoarr'])){
                  $photoarr = $_SESSION['photoarr'];
                } 
                //$sql = "SELECT photo_id,album_id,photo_path FROM album_photos WHERE photo_id = '".$photoid."'";
                $sql= "SELECT u.name,p.photo_id,p.album_id,p.photo_path,a.when_posted FROM users u, album a, album_photos p WHERE u.uid = a.userid AND a.album_id = p.album_id AND p.photo_id = '".$photoid."'";
                
                $db->send_sql($sql);
                //if valid photoid proceed
                
                if($row=$db->next_row())
                {
                    $path=$row['photo_path'];
                    $poster=$row['name'];
                    $dateposted=$row['when_posted'];
                    
                    
                   
                    $htmlphoto="<br/><div class= 'row'><div class='col-md-8 col-md-offset-2'><img src='imagedisplay.php?path=".$path."' class='img-responsive center-block'/></div></div>";
                    
                   $htmlpostedby="<br/><div class= 'row'><div style='text-align:center' class='col-md-8 col-md-offset-2'>From: ".$poster." On: ".$dateposted."</div></div>";
                    
                    echo $htmlphoto.$htmlpostedby;

                   
 
          $counter = 0;  
          if(!empty($photoarr)){
            $photoids ="";
            foreach($photoarr as $val){
                 if(empty($photoids)) 
                  $photoids = $val['photoid'] ;
                 else     
                  $photoids= $photoids."#".$val['photoid'] ;
            }
            echo "<input type='hidden' id='photoids' name='photoids' value='".$photoids."'>";
          
        ?>

        <div class="row">
          <div class='col-md-8 col-md-offset-2'>
          <ul class="pager">
            <li id="ppager_parent"><a class="btn-sm" id="ppager" href="#">&larr;Previous</a></li>
            <li id="npager_parent"><a class="btn-sm" id="npager" href="#">Next&rarr;</a></li>
          </ul> 
          </div>
        </div>
        <?php
          }
        ?>
    
        <br/>         
        <div class="row">
            <div class='col-md-8 col-md-offset-2' id="commentdiv">
               
            
            </div>    
        </div>
        <br/>
        <div class="row">
            <div style="text-align:center;" class='col-md-8 col-md-offset-2'>
            <h3>Leave a comment</h3>        
            </div>
        </div>
        
        
        <div class="row">
                    <div style="text-align:center;" class='col-md-8 col-md-offset-2'>
                        <form style="display:inline-block;"role="form" name="commentform" id="commentform"> 
                            <input type="hidden" id="user" name="user" value="<?php if(isset($uid)){ echo $uid; }?>">
                            <input type="hidden" id="photo" name="photo" value="<?php if(isset($photoid)){ echo $photoid; } ?>">
							<input type="hidden" id="albumid" name="albumid" value="<?php if(isset($albumid)){ echo $albumid; } ?>">		                            
								<div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" maxlength="40" style="width: 500px;">
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class="form-control" name="comment" id="comment" style="width: 500px; height:120px" required></textarea>
                            </div>
                            <div class="form-group">
                                 <input type='button' id="button" class="btn btn-info" value='Submit'/><span style="padding: 0 0 0 15px" id="messagep"></span>
                            </div>
                        </form>
                    </div>
        </div>
                
                
        <?php        
                }

                else{
                 //display warning no valid photoid
        ?>
        
                    <h3>The photo you requested is invalid</h3>
        <?php           
                }
            
            
            }
            //photoid query string parameter not set display warning
            else{
                
        ?>
        
                 <h3>Invalid request</h3>
        <?php        
            }
           
        ?> 
       
               
    </div>
    <!-- /.container -->
     <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
               $(document).ready(function(){
                   var photoid = $("#photo").val();
                   var albumid = $("#albumid").val(); 
                   var counter = -1; 
                   var photoids = ""; 
                   function displayComment(){
                
                       var pid=$("#photo").val();
                       $.ajax({
                        type:"post",
                        url:"process.php",
                        data:"pinfo=show&pid="+pid,
                        success:function(data){
                             $("#commentdiv").html(data);
                        }
                      }); 
                   } 
                   displayComment(); 
                   
                   $("#button").click(function(){
 
                          var title=$("#title").val();
                          var comment=$("#comment").val();
                          var pid=$("#photo").val();
                          var uid=$("#user").val();
                        
                        
                          $.ajax({
                              type:"post",
                              url:"process.php",
                              data:"title="+title+"&comment="+comment+"&uid="+uid+"&pid="+pid+"&pinfo=add",
                              success:function(data){
                                  $('#title').val('');
                                  $('#comment').val('');   
                                  $("#messagep").html(data);
                                  displayComment();
                                  
                              }
 
                          });
 
                    });

                  console.log("photoids :::"+$("#photoids").val());
                  function setPhotoids(){
                    var photo_ids = $("#photoids").val();
                    photoids = photo_ids.split("#");
                    console.log("photoids arr length :::"+photoids.length +"photo id ::"+photoid);
                    counter = photoids.indexOf(photoid);
                    console.log("counter ::"+counter);
                    if(counter<=0){
                      $("#ppager_parent").addClass("disabled");
                      $("#ppager").attr("href","#");    
                    }
                    if(counter>=(photoids.length-1)){
                      $("#npager_parent").addClass("disabled");
                      $("#npager").attr("href","#");    

                    }

                  }
                  setPhotoids();

                  $("#ppager").click(function(event){

                        if(counter<=0){
                           $(this).parent().addClass("disabled");
                           $(this).attr("href","#");  
                        }else{
                            counter--;    
                            $(this).attr("href","photo.php?photoid="+photoids[counter]+"&albumid="+albumid); 
                            
                        }

                  });
                
                  $("#npager").click(function(event){

                            if(counter>=photoids.length-1){
                               $(this).parent().addClass("disabled");
                               $(this).attr("href","#");  
                            }else{
                                counter++;    
                                $(this).attr("href","photo.php?photoid="+photoids[counter]+"&albumid="+albumid); 
                                
                            }

                    });
                  
               });
       
    </script>

   

</body>

</html>
