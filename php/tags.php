<?php


  //Twig loader
  require_once '../include/Twig-1.15.1/lib/Twig/Autoloader.php';
  Twig_Autoloader::register();
  //Twig use $loader variable to locate the templates.
  $loader = new Twig_Loader_Filesystem(dirname(dirname(__FILE__))."/".'twigtpl');
  //Twig_Environment is used to store the configuration.
  $twig = new Twig_Environment($loader);
  $templatename = "tags.tpl";
  session_start();
  $uname = $_SESSION['name'];
  echo $twig->render($templatename,array('uname' => $uname)); 

?>
