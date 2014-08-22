<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home</title>

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
                    <li class="active">
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
              

            <!-- <div class="row navbar-right"> -->
               <div class="col-md-1 pull-right" >
                 <a href="home.php{% if viewtype == 'list' %}  {% else %}?viewtype='listview' {% endif %} " class= "btn btn-info" >
                     <span {% if viewtype == 'list' %} 

                              class="glyphicon glyphicon-th"
                            {% else %}
                               class="glyphicon glyphicon-th-list"   
                            {% endif %}></span>
                  </a> 
               </div>
               <div class="col-md-6 pull-left"> 
                 <form id ="searchform" role="form" action="../php/home.php" method="get"> 
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search album by title" id="searchalbum" name="searchalbum"
                    {% if searchstr %}
                       value="{{searchstr}}" 
                    {% endif %}  
                     >
                     <span class="input-group-btn">
                        <button class="btn btn-info" type="button" id="searchbtn">
                          <span class="glyphicon glyphicon-search"></span>
                        </button>
                     </span>
                  </div>
                  <input id="viewtype" name="viewtype" type="hidden" value="{{viewtype}}">
                </form>
              </div>
 
         <!--  </div>  -->
            
    {% if viewtype == 'thumb' %}  
      {% if album_arr %}
        
        <div class="col-md-12">
                <h1 class="page-header">
                    <small>Albums</small>
                </h1>
        </div>
      
      <div class="container-fluid">

        {% for rows,cols in album_arr %}
             <div class="row">
               {% for colindex,ainfo in cols %}

                <div class="col-sm-2 portfolio-item">
                    <a href="getAlbum.php?albumid={{ainfo['id']}}">
                        <img class="img-thumbnail img-responsive" src="getimage.php?path={{ainfo['albumpgpath']}}" alt="">
                    </a><br/>
                    <span class="pull-left">
                      <strong>{{ainfo['title']}}</strong>
                    </span>
                  <span class="pull-right">  <small class="text-right text-muted">{% if ainfo['totalphotos'] == 1 %}
                                                        {{ainfo['totalphotos']}} photo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {% else %} 
                                                        {{ainfo['totalphotos']}} photos&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {% endif %}  
                                            </small>
                  </span>   
                </div>
               {% endfor %}
       </div>       
        {% endfor %}

</div>

      {% endif %}
 

     {% if shared_album %}

            <div class="col-md-12">
                <h1 class="page-header">
                    <small>Shared Albums</small>
                </h1>
            </div>

      <div class="container-fluid">

          {% for rows,cols in shared_album %}
      <div class="row">        
               {% for colindex,sharedalbuminfo in cols %}

                <div class="col-sm-2 portfolio-item">
                    <a href="getAlbum.php?albumid={{sharedalbuminfo['id']}}">
                        <img class="img-thumbnail img-responsive" src="getimage.php?path={{sharedalbuminfo['albumpgpath']}}" alt="">
                    </a><br/>
                    <span class="pull-left"><strong>{{sharedalbuminfo['title']}}</strong></span>
                    
                     <span class="pull-right"><small class="text-right text-muted">{% if sharedalbuminfo['totalphotos'] == 1 %}
                                                        {{sharedalbuminfo['totalphotos']}} photo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {% else %} 
                                                        {{sharedalbuminfo['totalphotos']}} photos&nbsp;&nbsp;&nbsp;&nbsp;
                                                    {% endif %}  
                    </small>    </span>
                </div>
               {% endfor %}
        </div>      
        {% endfor %}

</div>

     {% endif %} 

{% endif %}

{% if viewtype=='list' %}
  {% if album_arr %}
          <!-- Page Heading -->
  <!-- <div class="row">-->
      <div class="col-md-12">
         <h1>
            <small>Albums</small>
          </h1>
       </div>
<!--   </div>-->
 
 
      <div class="container-fluid">
      <table class="table table-condensed">
        <tr>
          <th>Album Title</th>
          <th>Total Photos</th>
          <th>Uploaded Date</th>
          
        <tr/>
    
        {% for key,arr in album_arr %}
            <tr>
              <td><a href="getAlbum.php?albumid={{arr['id']}}">{{arr['title']}}</a></td>
              <td>{{arr['totalphotos']}}</td>
              <td>{{arr['date'] }}</td>
            </tr>  
        {% endfor %}
      </table>
      </div>
   {% endif %} 

  {% if shared_album %}

     <!--   <div class="row"> -->
            <div class="col-md-12">
                <h1>
                    <small>Shared Albums</small>
                </h1>
            </div>
       <!-- </div> -->
    <div class="container-fluid">
      <table class="table table-condensed">
        <tr>
          <th>Album Title</th>
          <th>Total Photos</th>
          <th>Shared Date</th>
          
        <tr/>
        {% for key,arr in shared_album %}
          <tr>
            <td><a href="getAlbum.php?albumid={{arr['id']}}">{{arr['title']}}</a></td>
            <td>{{arr['totalphotos']}}</td>
            <td>{{arr['date'] }}</td>
          </tr>
        {% endfor %}
      </table>
   {% endif %} 
</div>

{% endif %}

     
    
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery-1.11.0.js"></script>
     <script src="../js/gallery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>
