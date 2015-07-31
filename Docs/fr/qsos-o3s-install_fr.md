# O3S installation

## Pré requis

* un serveur Web
* PHP 5 avec les modules GD, XML avec support de XSL, mysql
* Mysql
* Java 6 (pour Freemind). Fonctionne aussi avec openjdk 1.8.0
* Code source de QSOS : `git clone https://github.com/drakkr/QSOS.git`

### Pré requis pour Centos 7

* Ajouter le dépôt fournissant php 5.5 (la version par défaut fonctionne également)
```
wget http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
yum install -y remi-release-7.rpm
# Éditer le fichier /etc/yum.repos.d/remi.repo pour activer le dépôt remi-php55
```

* Installation et configuration
```
yum install -y httpd php php-gd php-xml php-mysql mariadb-server
systemctl enable httpd
systemctl start httpd
systemctl enable mariadb
systemctl start mariadb
```

* Désactiver le service firewalld si vous le souhaitez
```
systemctl stop firewalld
systemctl disable firewalld
```

* Désactiver selinux si vous le souhaitez
```
vim /etc/sysconfig/selinux
reboot
```

### Pré requis pour Debian

```
apt-get install apache2 php5 php5-gd php5-xsl php5-mysql mysql-server
```

## Installation du backend QSOS

Le code source se situe dans `/var/www/html/Tools/o3s/backend`.

Créer la base de données o3s dans MySQL en utilisant le script `Tools/o3s/create_db.sql`.
```
cd /var/www/html/Tools/o3s
mysql < create_db.sql
```

Faire pointer le site web vers le répertoire `Tools/o3s/backend/app` (i.e. http://backend.qsos.org).

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

Éditer le fichier `Tools/o3s/backend/app/dataconf.php` et renseigner les paramètres d'accès à la base de données.
```
$db_host = "localhost";
$db_user = "root";
$db_pwd = "";
$db_db = "o3s";
```

Initialiser les 2 dépôts avec les commandes suivantes.

Dans le répertoire `Tools/o3s/backend/master` :

```
git init
git commit -a -m "Master init"  --allow-empty
```

Dans le répertoire `Tools/o3s/backend/incoming` :

```
git init
git commit -a -m "Incoming init"  --allow-empty
```


Mettre les droits du serveur web sur le répertoire `backend` :

* Debian : `chown -R www-data:www-data backend`
* CentOs/RedHat : `chown -R apache:apache backend`

Voilà, le backend QSOS devrait être accessible en ligne.

Vous pouvez vous connecter en tant que `root` avec le mot de passe `root`. N'oubliez pas de modifier le mot de passe par défaut.

## Installation d'une instance d'un frontal O3S

Le code source se situe dans `/var/www/html/Tools/o3s`.

Faire pointer le site web vers le répertoire `Tools/o3s/app` (i.e. http://master.o3s.qsos.org).

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

Éditer le fichier `Tools/o3s/app/config.php` pour vous connecter à la base de données et les paramètres des dépôts git (i.e. master).

```
$db_host = "localhost";
$db_user = "root";
$db_pwd = "";
$db_db = "o3s";
```

Télécharger et dézipper Freemind (binary package) dans le répertoire `Tools/o3s/app/freemind` (i.e. http://sourceforge.net/projects/freemind/files/freemind/1.0.1/freemind-bin-max-1.0.1.zip).

Mettre les droits du serveur web sur le répertoire `Tools` :
* Debian : `chown -R www-data:www-data Tools`
* CentOs/RedHat : `chown -R apache:apache Tools`

Créer le lien symbolique du répertoire `backend` dans le répertoire `Tools/o3s/app` : `cd Tools/o3s/app && ln -s ../backend/`

Si vous voulez une deuxième instance d'o3s, et le connecter à un autre dépôt (i.e. http://incoming.o3s.qsos.org), vous devrez déployer uen deuxième fois o3s. C'est pas génial, on le sait...

