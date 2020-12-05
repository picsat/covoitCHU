# Slim Framework 3 Skeleton Application

Use this skeleton application to quickly setup and start working on a new Slim Framework 3 application. This application uses the latest Slim 3 with the PHP-View template renderer. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

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
