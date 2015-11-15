# FaZend\_Backup #

Add the following lines to your `application/config/app.ini` (don't forget to define `backup.password` in your `build.properties` to let `phing` replace it before actual deployment):

```
resources.fz_backup.execute = true
resources.fz_backup.policies.1.name = "dump_Mysql"
resources.fz_backup.policies.2.name = "compress_Gzip"
resources.fz_backup.policies.3.name = "encrypt_Openssl"
resources.fz_backup.policies.3.options.password = "pass:${backup.password}"
resources.fz_backup.policies.4.name = "rename"
resources.fz_backup.policies.5.name = "save_Ftp"
```

With the lines above you configure a collection of backup policies, to be executed one by one once you start your application from a command line:

```
$ php index.php FzBackup 2>&1
```

Try it and you see what happens.

# Configuration #

You can configure the component:

```
; how often backup cycle should run (hours)
resources.fz_backup.period = 3
```

# Policies #

Every policy has its own configuration parameters, specified via `options` attribute in config file, for example:

```
resources.fz_backup.policies.5.name = "save_Ftp"
resources.fz_backup.policies.5.options.host = "ftp.example.com"
resources.fz_backup.policies.5.options.port = 21
resources.fz_backup.policies.5.options.username = "john-doe"
resources.fz_backup.policies.5.options.password = "secret"
; for how many hours should we keep old archives on the server
resources.fz_backup.policies.5.options.age = 24
```

The following policies are provided by FaZend out of the box:

  * `archive_Tar`: archives a collection of files into a `tar` archive
  * `compress_Gzip`: compresses the file using gzip server-side command line utility
  * `dump_Mysql`: dumps MySQL database content into a file
  * `encrypt_Openssl`: encrypts the file using OpenSSL algorithm
  * `save_Ftp`: transfers the file by FTP
  * `rename`: renames the file according to the algorithm configured

Policies are executed one by one, according to the order they are specified in. When the process is started a dedicated temporary directory is created. This directory is available to all policies and they use it by default. You don't need to configure them specially, if you are working in this directory. For example, calling `compress_Gzip` without any parameters means that it has to compress the file located in the default directory. If the file is absent, or if there are many files - you get an exception and the whole backup process is cancelled.