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
                        <a class="text-info" href="login.php"><strong>Welcome {{uname}}</strong></a>
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

      


<!--      <div class="row">
        
        <div class="col-sm-5">
        <div class="row">
        <label for="tag" class="col-xs-1">Tags</label>
        <select class="form-control">
          <option>Birthday Party
        </select>
        </div>
       </div> 
      <div class="col-sm-3">
      <form class="form-inline"  role="form">
        <div class="form-group">
          <label class="sr-only" for="addtag">Tag</label>
          <input type="text" class="form-control" id="addtag" placeholder="Enter new tag">
        </div>
        <button type="submit" class="btn btn-info">Add</button>
      </form>
      </div>
    </div>
    <br/>
    <br/>-->

    <form id ="tagform" role="form" action="../php/addtag.php" method="post" class="form-horizontal">
      <div class="form-group form-group-sm pull-right">
        <div class="col-xs-10">
            <input type="text"id="newtag" name="newtag" class="form-control"  placeholder="Enter new tag" pattern="[A-Za-z0-9]" >
            <span class="help-block">Multiple tags are accepted seperated by ,</span>  
        </div> 
        <button class="btn btn-info btn-sm" name="addtagbtn" id ="addtagbtn">Add</button>
      </div>
      
      <div class="form-group form-group-sm">     
        <label for="tag" class="col-xs-1">Tags</label>
        <div class="col-xs-4">
        <select class="form-control">
          <option disabled selected>Select the tag</option>
          <option>Birthday Party</option>
        </select>
        <span class="help-block">Select the tag to look up the album(s) associated with it.</span>    
        </div>
      </div> 

   </form> 
  <br/>
  <br/>

    <table class="table table-condensed">
        <tr>
          <th>Album Title</th>
          <th>Total Photos</th>
          <th>Uploaded By</th>
          
        <tr/>

          <tr>
            <td><a href="getAlbum.php?albumid=13">random</a></td>
            <td>40</td>
            <td>Tom Cruise</td>
          </tr>
          <tr>
            <td><a href="getAlbum.php?albumid=14">myalbum</a></td>
            <td>40</td>
            <td>You</td>
          </tr>

      </table>
  </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <!-- script src="../js/bootstrap.min.js"></script -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
  <!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js" /> -->

</body>

</html>
