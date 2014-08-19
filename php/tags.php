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

    <title>Tags</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/stylish-portfolio.css" rel="stylesheet">
    <!-- Custom CSS     -->


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
                    <li class="active">
                        <a href="../php/tags.php">Tags</a>
                    </li> 
   
                    <li>
                        <a href="../php/logout.php">Logout</a>
                    </li>
  
                </ul>
                 <ul class="nav navbar-nav  navbar-right">
                    <li> 
                        <a class="text-info" href="login.php"><strong>Welcome <?php if(isset($_SESSION['name'])) echo $_SESSION['name'] ?></strong></a>
                    </li>                
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                </h1>
            </div>
        </div>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Tags</small>
                </h1>
            </div>
        </div>
    
        <form id ="tagform" role="form" class="form-horizontal">
      
            <div class="form-group form-group-sm pull-right">
        
                <div class="col-xs-10">
                    
                    <input type="text"id="newtag" name="newtag" class="form-control" style="width: 240px;" placeholder="Enter new tag" >
                    <span class="help-block" id="messagep" style="width: 300px;">Multiple tags are accepted seperated by ,</span>
                    
                </div> 
                <input type='button' class="btn btn-info btn-sm" id="button" class="btn btn-info" value='Add'/>
            </div>
      
            <div class="form-group form-group-sm">     
                <label for="tag" class="col-xs-1">Tags</label> 
                <div class="col-xs-4">
                    <select class='form-control' id='tags' name='tags'>
                    
                    
                    </select>
                    <span class="help-block">Select the tag to look up the album(s) associated with it.</span>    
                </div>
            </div> 

        </form> 
        <br/>
        <br/>
        <table class="table table-condensed" id="tagtable" name="tagtable">
            <tr>
                <th>Album Title</th>
                <th>Total Photos</th>
                <th>Uploaded By</th>
            <tr/>
        </table>
  
    </div>

    
    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>
    <!-- Bootstrap Core JavaScript -->
    
    <script src="../js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
               $(document).ready(function(){
                   
                   function showTags(){
                       $.ajax({
                        type:"post",
                        url:"process.php",
                        data:"pinfo=showtags",
                        success:function(data){
                             $("#tags").html(data);
                        }
                      }); 
                   }
                   
                   showTags(); 
                   
                   $("#button").click(function(){
                          var newtag=$("#newtag").val();
                          $.ajax({
                              type:"post",
                              url:"process.php",
                              data:"pinfo=addtags&newtag="+newtag,
                              success:function(data){  
                                  $('#newtag').val('');
                                  $('#messagep').html(data);
                                  showTags();
                                 
                              }
                          });
                    });
                   
                   
                   
                   $('#tags').change(function() {       
                       if ($(this).val() !== '') {
                           var tagval=$(this).val();
                           console.log("tagval is "+tagval);
                           $.ajax({
                               type:"post",
                               url:"process.php",
                               data:"pinfo=tagged&val="+tagval,
                               success:function(data){  
                                   $('#tagtable').html(data); 
                              }
                          });
                       } 
                   });
         

               });
       
    </script>
    </body>

</html>
