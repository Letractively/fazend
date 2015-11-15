# Configuration of Repository #

Your project structure should be like this (full example in /directory-structure):

```
/projectName
  /branches
  /tags
  /trunk
    /php
      build.properties
      build.xml
      /src
        /application
        /library
        /public
```

Then do this (`vi` on Linux machine, `notepad` on Windows):

```
$ svn propedit svn:externals ProjectName/trunk/php/src/library --editor-cmd=vi
```

In the file opened for edit add these two lines:

```
Zend http://framework.zend.com/svn/framework/standard/trunk/library/Zend
FaZend http://fazend.googlecode.com/svn/trunk/FaZend
```

Save the file and do this:

```
$ svn commit projectName -m "svn:externals property set"
```

From now on the directory `/library` will have two subdirectories: `Zend` and `FaZend`, which will updated automatically from other repositories every time you `svn update` your project. The benefits you get:

  * Your repository is not flooded with "not your" files
  * Your repository is small enough, just your application files
  * You always have the latest version of Zend and FaZend

# Starting a new project #

Key steps for a new project setup:

  1. Create fazend.com account
  1. Create new project in fazend.com
  1. Configure access to SVN
  1. Create repository structure in SVN (as in the example)
  1. Setup Google Analytics and configure it in `app.ini`
  1. Setup Google Webmasters and verify it
  1. configure files in your project: `build.properties`, `app.ini`, `backup.ini`

# Local Configuration #

Add this line to your apache `httpd.conf` file and restart apache:

```
<Directory /_path_to_your_project_/php/src/public>
    Options Indexes FollowSymLinks Includes
    SetEnv APPLICATION_ENV development
    Allow from all
    AllowOverride None
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*) index.php [L]
    RewriteBase /projectName
</Directory>

Alias /projectName "/_path_to_your_project_/php/src/public"
```

Now this link should work: `http://localhost/projectName`.

Review database configuration parameters you will find in `application/app.ini` in section `development`. You should create a local database and user with the mentioned names. Don't create any tables or views, they will be created automatically from SQL files, by FaZend\_Deployer.

Add this line to your local (in the development environment) `php.ini` file:

```
auto_prepend_file="/code/auto_prepend.php"
```

Create file `/code/auto_prepend.php` with the following content:

```
<?php
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', 'development');
}
```

# Remote Configuration #

Create `.htaccess` with the following content:

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
RewriteRule ^.*$ /index.php [NC,L]
```

Don't forget to enable `mod_rewrite` in Apache:

```
<Directory /_path_to_your_project_/public>
    AllowOverride All
    RewriteEngine On
</Directory>
```