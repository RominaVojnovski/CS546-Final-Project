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
                    <small>Search Photos</small>
                </h1>
            </div>
        </div>
        <div class="row">
                    <div class="col-md-12">
                        <form role="form" name="commentform" id="commentform"> 
                           
                            <div class="form-group">
                                <label for="title">Search by Title or Tag</label>
                                <input type="text" class="form-control" name="title" id="title" maxlength="40" style="width: 500px;">
                            </div>
                            <div class="radio">
                                <label>
                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" >title
                                </label>
                                <label>
                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" >tag
                                </label>
                            
                            </div>
                          
                            <div class="form-group">
                                 <input type='button' id="button" class="btn btn-info" value='Search'/><span style="padding: 0 0 0 15px" id="messagep"></span>
                            </div>
                        </form>
                    </div>
        </div>
        
       
               
    </div>
    <!-- /.container -->
     <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
               $(document).ready(function(){
                   
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
               });
       
    </script>

   

</body>

</html>
