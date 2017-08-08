EZ Cookbook

Implemeted using Codeigniter with Bootstrap

----------------------------------------
Install instructions
----------------------------------------
1. Create the needed database schema (I used "cookbook" for the database name) then populate using the file cookbook.sql

2. Copy contents of cookbook directory to your web server root directory keeping directrory structure.

3. Check settings in /application/config/config.php, /application/config/database.php

4 Set permissions for /images/*, /sessions/* and /application/cache to be writable by the webserver.

5. Navigate your web browser to http://your.hostname.com/ (/cookbook/index.php is the target file.).
   See /application/config/routes.php for a mapping of pages