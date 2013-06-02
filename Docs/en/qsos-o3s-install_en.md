# O3S installation

## Prerequsites

* PHP 5 with XSL support (Debian : apt-get install php5-xsl)
* Java 6 (for Freemind)
* Get QSOS source code: `git clone https://github.com/drakkr/QSOS.git`

## QSOS backend

The source code is in `Tools/o3s/backend`.

Create an o3s database in MySQL by using the `Tools/o3s/create_db.sql`script.

Point the website to the `app` directory (i.e. http://backend.qsos.org).

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

Make sur your webserver is owner of the `backend` directory : `chown -R www-data:www-data backend`

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

