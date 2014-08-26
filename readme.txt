Album upload configuration on server

1) Copy dbprop.php in parent directory of documentroot directory

2) create "uploaded_files" folder in parent directory of documentroot folder.
3) chmod uploaded_files to 777
4) change post_max_size directive to 600 mb in php.ini
5) change upload_max_filesize directive to 5 mb in php.ini
6) change max_file_uploads directive to 50 in php.ini

7) create .htaccess file in documentroot with these details

php_value upload_max_filesize 5M
php_value post_max_size 600M
php_value max_execution_time 200
php_value max_input_time 200 
php_value max_file_uploads 50

8) In apache2.conf change <directory> tag of document root to 
-AllowOverride All


9)GD Image Library

- To check GD support is enabled :- create a php file with the contents <?php phpinfo(); ?> on it and run on localhost. If you see a seperate section for GD support with below details in it. It means GD support is enabled otherwise see the its installation section below.

  GD Support 	enabled
  GD Version 	2.1.1-dev
  FreeType Support 	enabled
  FreeType Linkage 	with freetype
  FreeType Version 	2.5.2
  GIF Read Support 	enabled
  GIF Create Support 	enabled
  JPEG Support 	enabled
  libJPEG Version 	8
  PNG Support 	enabled
  libPNG Version 	1.2.50
  WBMP Support 	enabled
  XPM Support 	enabled
  libXpm Version 	30411
  XBM Support 	enabled
  WebP Support 	enabled 
 
GD Installation 

  Linux/Ubuntu user run below command:
  - sudo apt-get install php5-gd

  Windows user:
  - In Windows, you'll include the GD2 DLL php_gd2.dll as an extension in php.ini

For more details on GD installation please check this link http://php.net/manual/en/image.installation.php.



