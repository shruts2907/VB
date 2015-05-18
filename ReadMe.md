How to launch
========================
run cron.bat for windows 
which will run cron.php
the task scheduler which will run index.php for every 5 minutes

Requirements:
======================================
Facebook Task
Using PHP and MySQL, complete the following tasks:
1. Use the Facebook API to retrieve a list of photos from the Vanderbilt University Facebook
page and insert information on the photos into a MySQL table. The table must contain the
photo id, name, image source URL, created time, and number of likes. Do not use the
Facebook API SDK, as the photo data is available without authentication using this simple
JSON endpoint:
http://graph.facebook.com/vanderbilt/photos/uploaded

2. The code you write for this task should be written in a way that it can be run periodically to
update existing photos and add new ones.
3. Using the data in the MySQL table created in task #1, create an interface that displays a
list of the photos and allows the user to click through to view details on each photo. You
are free to design the interface however you like, as long as all of the required fields of
data in task #1 are displayed in either the list or detailed view.

Stack used:
========================================
PHP 5+, MySQL, phpMyAdmin, JQuery, JavaScript, FontAwersome, CSS3.

Functionality:
=================================================
cron.bat
-------------------------------------------------------
	runs the cron.php the schedulre which runs cron job every 5 minutes

 cron.php
 ---------------------------------------------------
	runs cron job every 5 minutes calling index.php every 5 minutes

 index.php
 -----------------------------------------------------------------------
    . main php file that runs the code and has access to all php file in the folder

    . calls only once Database.class.php that handles mysql data request to database server

    . calls only once fetchJson.php which access Vanderbilt facebook JSON page 

    .  Database Class instance and access to its function

    .  query select, insert, update depending upon the if else ladder

    . result is store in $info array and displayed on browser using HTML5 and JavaScript and PHP.

    . doSomething is a javascript funtion that creates popup for clicked image for additional details.

fetchJson.php
---------------------------------------------------------------------------
    . fetches json from url and decodes json into object array

Database.class.php
---------------------------------------------------------------------------------------- 
    . Connects the databse using information from config.inc.php file

    . connects through mysqli

    . function doSelect checks if the object fetched is to be inserted or updated

    . function doInsert perform sql query either UPDATE or INSERT depending upon the doSelect function if else result

    . function display SELECTS all information form the table and return the result set back to index.php

config.inc.php
---------------------------------------------------------------------------------
    . Store Database username, password, db name, to configure with the database

index.css
---------------------------------------------------------------------
    . adds style to index.php result set obtained from the backend
