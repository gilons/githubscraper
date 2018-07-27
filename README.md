# githubs
This code runs forever getting from github project having topics php and laravel.

It gets the project name , project url,repository url and finaly the the project's ReadME.

From the project readMe it just take somthing that is going to fully dsecribe the project.

The starting point topics can be changed by changing the stating point url at line 222 of the scraping4.php file from https://github.com/topics/laravel to any other topic url so that is should scrap github repository for that topic. 

Befor running it, as prerequisite:

  -One must have a mysql database created named scrapp.
  
  -Change the Mysql database connection password in the file scraper4.php to your password.
  
After Cloning it, to run it just open the terminal and type in the command php page_crapping.php
