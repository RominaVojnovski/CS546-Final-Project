<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Upload Album</title>

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
                    <small>Upload Album</small>
                </h1>
            </div>
        </div>
      {% if msg %}
        <div class="row">
            <div class="col-lg-12">
                <h2>
                    <small>
                       <div id="server_msg">{{ msg }}</div>
                    </small>
                </h2>
            </div>
        </div>
      {% endif %}
      
      <div class="row">
        <div class="col-lg-12">
          <h3>
            <small>
              <div id="not_image_file"> </div>
            </small> 
          </h3>
        </div>
      </div>

       <form id ="upalbumform" role="form" action="../php/upalbum.php" method="post" enctype="multipart/form-data" class="form-horizontal">


              <div class="form-group form-group-sm">
                <label for="file_input" class="col-md-2">*Upload Images </label>
                <div class="col-md-8">
                <input name="file_input[]"  id="file_input" multiple="" webkitdirectory="" type="file" accept="image/*">
                <span class="help-block">Only .jpg/.jpeg, .gif and .png images are accepted for upload. Also, 50 image files are accepted to upload in one request and a file size limit is 5mb. </span>                
                </div>      
              </div>

              <div class="form-group  title-group has-feedback form-group-sm">
                  <label for="title" class="col-md-2">*Album Title</label>
                  <div class="col-xs-4">
                    <input type="text"id="title" name="title" class="form-control" pattern="[A-Za-z0-9]" 
                      {% if title %} 
                        value ="{{ title }}"
                      {% else %}
                        value = ""      
                      {% endif %}
                    placeholder="Enter album name" autocomplete="on" required>
                    <span class="glyphicon form-control-feedback" id="feedback_icon"></span>
                   <span id="title_valid_msg"></span> 
                 </div> 
              </div>
              <br/>
            <div class="form-group">
                <div class="col-sm-offset-2 col-md-8">
                    <button class="btn btn-info" name="upload" id ="uploadbtn">Upload</button>
                </div>     
            </div> 

      </form>
    <div id="dialog"><div id="dialogText"></div></div>


  </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <!-- script src="../js/bootstrap.min.js"></script -->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/uploadalbum.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
  <!--<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js" /> -->

</body>

</html>
