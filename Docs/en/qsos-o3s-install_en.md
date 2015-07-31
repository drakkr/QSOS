# O3S installation

## Prerequisites

* Web server (Apache)
* PHP 5 with modules GD, XML with XSL support, mysql
* Mysql
* Java 6 (for Freemind)
* Get QSOS source code: `git clone https://github.com/drakkr/QSOS.git`

### Centos7 prerequisites

* Add repository for php 5.5 if you want
```
wget http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
yum install -y remi-release-7.rpm
# Edit /etc/yum.repos.d/remi.repo to enable remi-php55 repository
```

* Install and configuration
```
yum install -y httpd php php-gd php-xml php-mysql mariadb-server
systemctl enable httpd
systemctl start httpd
systemctl enable mariadb
systemctl start mariadb
```

* Disable firewalld if you want 
```
systemctl stop firewalld
systemctl disable firewalld
```

* Disable selinux if you want
```
vim /etc/sysconfig/selinux
reboot
```

### Debian prerequisites

```
apt-get install apache2 php5 php5-gd php5-xsl php5-mysql mysql-server
```

## QSOS backend

The source code is in `/var/www/html/Tools/o3s/backend`.

Create an o3s database in MySQL by using the `Tools/o3s/create_db.sql` script.
```
cd /var/www/html/Tools/o3s
mysql < create_db.sql
```

Point the website to the `app` directory (i.e. http://backend.qsos.org).

```
#/etc/httpd/conf.d/qsos.conf
<VirtualHost *:80>
        ServerName backend.qsos.org
        ErrorLog logs/error_log
        TransferLog logs/access_log
        CustomLog logs/access_log combined
        LogLevel warn
        DocumentRoot /var/www/html/Tools/o3s/backend/app
        DirectoryIndex index.php index.html
        Options Indexes
</VirtualHost>
```

Edit the `Tools/o3s/backend/app/dataconf.php` file to connect to the database.
```
$db_host = "localhost";
$db_user = "root";
$db_pwd = "";
$db_db = "o3s";
```

Initialize the two git repositories with the following commands.

In the `Tools/o3s/backend/master` directory:

```
git init
git commit -a -m "Master init"  --allow-empty
```

And also in the `Tools/o3s/backend/incoming` directory:

```
git init
git commit -a -m "Incoming init"  --allow-empty
```


Make sur your webserver is owner of the `backend` directory :
* Debian : `chown -R www-data:www-data backend`
* CentOs/RedHat : `chown -R apache:apache backend`

That's it. Your QSOS backend should be accessible online.

You can connect as `root` with the `root` password. Don't forget to change this default password.

## O3S instances

The source code is in `/var/www/html/Tools/o3s`.

Point the website to the `app` directory (i.e. http://master.o3s.qsos.org).

```
#/etc/httpd/conf.d/qsos.conf
<VirtualHost *:80>
        ServerName master.o3s.qsos.org
        ErrorLog logs/error_log
        TransferLog logs/access_log
        CustomLog logs/access_log combined
        LogLevel warn
        DocumentRoot /var/www/html/Tools/o3s/app
        DirectoryIndex index.php index.html
        Options Indexes
</VirtualHost>
```

Edit the `Tools/o3s/app/config.php` file to connect to the database and one of the backend's git repositories (i.e. master).

```
$db_host = "localhost";
$db_user = "root";
$db_pwd = "";
$db_db = "o3s";
```

Upload and unzip Freemind (binary package) in the `Tools/o3s/app/freemind` directory (i.e. http://sourceforge.net/projects/freemind/files/freemind/1.0.1/freemind-bin-max-1.0.1.zip). 

Make your webserver owner of `Tools` :
* Debian : `chown -R www-data:www-data Tools`
* CentOs/RedHat : `chown -R apache:apache Tools`

Symlink the backend in the `Tools/o3s/app` directory : `cd Tools/o3s/app && ln -s ../backend/`

If you want a second instance of o3s connected to another backend repository (i.e. http://incoming.o3s.qsos.org), you need to deploy o3s twice. It's ugly, we know...

