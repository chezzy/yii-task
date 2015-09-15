A Task Management Application

Requirements

PHP 5.3+
Yii's Requiremnts
A Webserver
Installation

Have a web capable server (Apache, Nginx). See here and here for how to do that.
DigitalOcean has a pretty comprehensive guide for setting up things if you aren't familiar with the process.

Download/Clone this project to your web directory, and setup your server so that it can be accessed.

Open up a Terminal window to the current working directory.

Make sure the following directories are writable by your webserver process

protected/runtime
assets/
protected/data/
Install the database

php protected/yiic.php migrate up
Once the database has been installed you can use the application.