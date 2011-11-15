QR Code Generator
==================================================

Developed by: Steve Callan  [http://callaninteractive.com](http://callaninteractive.com)


About
--------------------------------------

QR Codes are 2 dimensional barcodes, scannable by cell phones that contain textual data rather than simply numeric.  In the QR Generator App, admins have the ability to create new codes using URL strings.  These scans are tracked and through the admin panel can be reiewed through various analytics tools. The QR Generator was built using [Codeigniter](http://codeigniter.com), [JQuery](http://jquery.com) and [Bootstrap](http://twitter.github.com/bootstrap/) frameworks.  To generate the QR code, the script uses the QR code library written by [MaxLazar](http://eec.ms)


Requirements (Codeigniter requirements)
--------------------------------------

1. PHP 5.1.6 or newer
2. A Database is required for most web application programming. Current supported databases are MySQL (4.1+), 
   MySQLi, MS SQL, Postgres, Oracle, SQLite, and ODBC.


Installation
--------------------------------------

1. Modify database configuration file to point to your server /qr/config/database.php
2. Set your base url in /qr/config/config.php (ex: http://stevecallan.com/)
3. Setup a site owner url in /qr/config/constants.php - this variable is used to redirect if there is no key 
   match found on a code
4. Upload the qr folders to your server
5. Run the installation file by going to http://YOURSITE.com/index.php/install
6. Delete the install file /qr/controllers/install.php


Creating a QR code
--------------------------------------

1. Go to http://YOURSITE.com/index.php/create
2. Locate the Create a New Code button in the upper right hand corner
3. Enter your URL and any notes
4. Click "Build QR Code"


License
--------------------------------------

Copyright (c) 2011 Steve Callan, http://callaninteractive.com/

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.