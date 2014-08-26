<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{title}} Album</title>

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
                        <a class="text-info" href="login.php"><strong>Welcome {{uname}}</strong></a>
                    </li>                
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container-fluid">


        <!-- /.row -->


        <!-- Page Heading -->
              <div class="col-md-1 pull-right" >
                  <a href="getAlbum.php?albumid={{albumid}}{% if viewtype == 'list' %}  {% else %}&viewtype='listview' {% endif %} " class= "btn btn-info" >
                     <span {% if viewtype == 'list' %} 

                              class="glyphicon glyphicon-th"
                            {% else %}
                               class="glyphicon glyphicon-th-list"   
                            {% endif %}></span>
                  </a> 
             </div> 
              <!--<div class="row navbar-right">-->
               <div class="col-md-6 pull-left"> 

                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" placeholder="Search for people to share" id="sharepeople" name="sharepeople">
                  
                   <span class="input-group-btn">
                      <button class="btn btn-info" type="button" id="sharebtn">
                        <span class="glyphicon glyphicon-share-alt"></span>
                      </button>
                   </span>
                   
                </div>
                <span class="help-block" id="sharetip">Add multiple user names seperated by ,</span>
                <div id="searchhelp"></div> 
              </div>
              
          <!--  </div>  -->


        
        <!--<div class="row">-->
          <div class="col-md-12">
            <h2>
              <small>
                <div id="querymsg"></div>
              </small> 
            </h2>
          </div>
      <!--  </div> -->
               
      {% if viewtype == 'thumb' %}  
        {% if photo_arr %}      
              
        <div class="col-md-12">
                <h3 class="page-header"> {{title|capitalize}}
                    <small>{% if sharedby %}
                          &nbsp;Shared by: {{sharedby}},&nbsp;date: {{posted_date}}
                          {% else %}
                              Uploaded on {{posted_date}}  
                        
                        {% endif %}</small>
                    
                </h3>

        </div>
      

    
      <div class="container-fluid">

        {% for rows,cols in photo_arr %}
             <div class="row">
               {% for colindex,pinfo in cols %}

                <div class="col-sm-2 portfolio-item">
                    <a href="photo.php?photoid={{pinfo['photoid']}}&albumid={{albumid}}">
                        <img class="img-thumbnail" src="getimage.php?path={{pinfo['relpath']}}" alt="">
                    </a>
                </div>
               {% endfor %}
            </div>       
        {% endfor %}



    
  
      </div>


      {% endif %}

      {% endif %}
      

    {% if viewtype=='list' %}
         {% if photo_arr %}
          <!-- Page Heading -->
      <div class="col-md-12">
         <h3>{{title|capitalize}}

            <small>{% if sharedby %}
                      &nbsp;Shared by: {{sharedby}},&nbsp;date:   {{posted_date}}
                   {% else %}
                      Uploaded on {{posted_date}}  
                        
                   {% endif %}

            </small>
          </h3>
       </div>

     <div class="container-fluid">
      <table class="table table-condensed">
          <tr>
            <th>Name</th>
            <th>Type</th>
            {% if sharedby %}
              <th>Shared Date</th>
            {% else %}
              <th>Uploaded Date</th>
            {% endif %}
          <tr/>
        {% for key,arr in photo_arr %}
            <tr>
              <td><a href="photo.php?photoid={{arr['photoid']}}&albumid={{albumid}}">IMG_{{key+1}}.JPG</a></td>
              <td>Image</td>
              <td>{{posted_date}}</td>
            </tr>  
        {% endfor %}
      </table>
  </div>
   {% endif %} 

 
{% endif %}
<input type="hidden" id="albumid" name="albumid" value="{{albumid}}"/>

     
    
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>
    <script src="../js/albumphotos.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>

