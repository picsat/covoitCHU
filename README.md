# Covoit CHU Calendar App basée sur Slim Framework 3 Skeleton Application

Cette application fournit un outil simple et rapide aux membres du personnel du CHU habitant dans la région Clermontaise de l'Hérault permettant :

* la continuité d'un service devenu obsolète (arrêt prévu décembre 2020)
* l'inscription par invitation ds membre uniquement

L'application a été réalisée dans le but d'apréhender plusieurs notions :

* Slim Framework 3
* L'autentification via le skeleton modifié slim-born de @HavenShen (https://github.com/HavenShen/slim-born)
* ORM Elloquent
* Structure MVC
* Utilisation de CSRF sur les formulaires
* Utilisation de Twig, twig-view
* Utilisation de Bootstrap 4

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

    php composer.phar create-project slim/slim-skeleton [my-app-name]

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writeable.

To run the application in development, you can run these commands

	cd [my-app-name]
	php composer.phar start

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

         cd [my-app-name]
	 docker-compose up -d
After that, open `http://0.0.0.0:8080` in your browser.

Run this command in the application directory to run the test suite

	php composer.phar test

That's it! Now go build something cool.


### Run it:

1. `$ cd my-app`
2. `$ php -S 0.0.0.0:8888 -t public public/index.php`
3. Browse to http://localhost:8888


## Key directories

* `app`: Application code
* `app/src`: All class files within the `App` namespace
* `app/views`: Twig template files
* `cache/twig`: Twig's Autocreated cache files
* `log`: Log files
* `public`: Webserver root
* `vendor`: Composer dependencies

## Key files

* `public/index.php`: Entry point to application
* `app/settings.php`: Configuration
* `app/dependencies.php`: Services for Pimple
* `app/middleware.php`: Application middleware
* `app/routes.php`: All application routes are here
* `app/src/Controlles/HomeAction.php`: Action class for the home page
* `app/views/home.twig`: Twig template file for the home page
