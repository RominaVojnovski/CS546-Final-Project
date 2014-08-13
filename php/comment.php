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

    <title>Pix Gallery Comment</title>

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
                        <a href="#">Tags</a>
                    </li> 
                    <li>
                        <a href="#">Search</a>
                    </li>    
                    <li>
                        <a href="../php/logout.php">Logout</a>
                    </li>

                </ul>
                <ul class="nav navbar-nav  navbar-right">
                    <li> 
                        <a class="text-info" href="login.php"><strong>Welcome <?php echo $_SESSION['name'] ?></strong></a>
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
                <h1 class="page-header">
                    <small>Comment</small>
                </h1>
            </div>
        </div>
        <?php
                
            include("mysqli_class.php");
            //include("../../../dbprop.php");   
                    
            $db1 = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $db=new database();
            $uid=$_SESSION['uid'];

            
            if(isset($_GET['cid'])){
                $commentid=$_GET['cid'];
            }
            //check if query string parameter of photoid is set and not empty
            
            if(!empty($commentid))
            {
                //$sql = "SELECT photo_id,album_id,photo_path FROM album_photos WHERE photo_id = '".$photoid."'";
                $sql= "SELECT u.name,p.photo_id,p.photo_path,c.title,c.comment_text,c.when_posted FROM users u, album_photos p, comments c WHERE u.uid = c.userid AND c.photo_id = p.photo_id AND c.comment_id = '".$commentid."'";
                
                $db->send_sql($sql);
                //if valid commentid proceed
                
                if($row=$db->next_row())
                {
                    $path=$row['photo_path'];
                    $commenter=$row['name'];
                    //$dateposted=$row['when_posted'];
                    $ctitle=$row['title'];
                    $ctext=$row['comment_text'];
                    $cdate=$row['when_posted'];
                    
                   
                    $htmlphoto="<div class= 'row'><div class='col-md-6'><img src='imagedisplay.php?path=".$path."'/></div>";
                    
                    $htmlpostedby="<div class='col-md-6'><br/><h3>".$ctitle."</h3><br/>".$ctext."<br/>comment by: ".$commenter." on: ".$cdate."</div></div>";
                    echo $htmlphoto.$htmlpostedby;
        ?>
        
        <br/>         
        <div class="row">
            <div class="col-md-12" id="responsediv">
                
            
            </div>    
        </div>
        <br/>
        <div class="row">
                    <div class="col-md-12">
                        <form role="form" name="responseform" id="responseform"> 
                            <input type="hidden" id="user" name="user" value="<?php echo $uid?>">
                            <input type="hidden" id="comment" name="comment" value="<?php echo $commentid?>">
                            <div class="form-group">
                                <label>Response</label>
                                <textarea class="form-control" name="response" id="response" style="width: 500px;" required></textarea>
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
        
                    <h3>Invalid request!</h3>
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
                   
                   function displayResponse(){
                
                       var cid=$("#comment").val();
                       $.ajax({
                        type:"post",
                        url:"process.php",
                        data:"pinfo=show2&cid="+cid,
                        success:function(data){
                             $("#responsediv").html(data);
                        }
                      }); 
                   } 
                   displayResponse(); 
                   
                   $("#button").click(function(){
 
                         
                          var response=$("#response").val();
                          var cid=$("#comment").val();
                          var uid=$("#user").val();
                          console.log("uid is"+uid);
                            
                       console.log("cid is"+cid);
                       console.log("response is"+response);
                        
                          $.ajax({
                              type:"post",
                              url:"process.php",
                              data:"response="+response+"&uid="+uid+"&cid="+cid+"&pinfo=add2",
                              success:function(data){
                                  $('#response').val('');   
                                  $("#messagep").html(data);
                                  displayResponse();
                                  
                              }
 
                          });
 
                    });
               });
       
    </script>

   

</body>

</html>
