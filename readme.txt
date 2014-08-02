Album upload configuration on server

1) create "uploaded_files" folder outside documentroot.
2) chmod uploaded_files to 777
3) change post_max_size directive to 600 mb in php.ini
4) change upload_max_filesize directive to 5 mb in php.ini

5) create .htaccess file in documentroot with these details

php_value upload_max_filesize 5M
php_value post_max_size 600M
php_value max_execution_time 200
php_value max_input_time 200 
php_value max_file_uploads 50

6) change in <directory> tag of document root - apache2.conf 
-AllowOverride All


