ESN CZ app Server and administration
====================================

This is backend for ESN CZ apps - *CZ Erasmus* app
Contains:
- Data and logic
- API access for apps
- Administrative interface


Disclaimer
==========

This application is for non-commercial use.

Requirements
------------

- PHP 5.6 or higher
- MySQL
- Nette Framework
- Doctrine2 ORM

Installation
------------

The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

	composer create-project nette/web-project path/to/install
	cd path/to/install


Make directories `temp/` and `log/` writable.


Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the welcome page.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**


Third party assets
------------------
Bootstrap 4 Material Admin by Bootstrapious (https://bootstrapious.com)