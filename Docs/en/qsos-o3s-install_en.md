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

The source code is in `Tools/o3s/backend`.

Create an o3s database in MySQL by using the `Tools/o3s/create_db.sql`script.
```
cd /var/www/html/QSOS.git/Tools/o3s
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
        DocumentRoot /var/www/html/Tools/
        DirectoryIndex index.php index.html
        Options Indexes
</VirtualHost>
```

Edit the `dataconf.php` file to connect to the database.

Copy or symlink the `Tools/o3s/formats` directory at the same level of the `backend` directory.

Initialize the two git repositories with the following commands.

In the `master` directory:

    echo '#Master repository' > .conf

    git init

    git add .conf

    git commit -am "Master init"

And also in the `incoming` directory:

    echo '#Incoming repository' > .conf

    git init

    git add .conf

    git commit -am "Incoming init"

Symlink the Git repositories in the `app` directory: `ln -s ../master/` and `ln -s ../incoming/`

Make sur your webserver is owner of the `backend` directory :
* Debian : `chown -R www-data:www-data backend`
* CentOs/RedHat : `chown -R apache:apache backend`

That's it. Your QSOS backend should be accessible online.

You can connect as `root` with the `root` password. Don't forget to change this default password.

## O3S instances

The source code is in `Tools/o3s`.

Point the website to the `app` directory (i.e. http://master.o3s.qsos.org).

Copy or symlink the `Tools/o3s/formats` directory at the same level of the `app` directory.

Edit the `config.php` file to connect to the database and one of the backend's git repositories (i.e. master).

Upload and unzip Freemind 1.0.0 in the `freemind` directory (http://sourceforge.net/projects/freemind/files/freemind-unstable/). And make your webserver owner of it (`chown -R www-data:www-data freemind`).

Symlink the backend in the `app` directory (i.e. `ln -s ../../backend/`).

If you want a second instance of o3s connected to another backend repository (i.e. http://incoming.o3s.qsos.org), you need to deploy o3s twice. It's ugly, we know...

