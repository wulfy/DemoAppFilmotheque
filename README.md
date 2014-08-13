DemoAppFilmotheque
==================

bundle basé sur tuto pour SF2, MAJ pour SF2.5 + utilisation listener et tests


config.yml
===
```
 doctrine:
    dbal:
        driver:   "%database_driver%"
        host:     "%database_host%"
        port:     "%database_port%"
        dbname:   "%database_name%"
        user:     "%database_user%"
        password: "%database_password%"
        charset:  UTF8
        # if using pdo_sqlite as your database driver, add the path in parameters.yml
        # e.g. database_path: "%kernel.root_dir%/data/data.db3"
        path:     "%kernel.root_dir%/data/database.sqlite"
```
...
```
#extension de fosuserbundle pour le projet filmotheque
fos_user:
    db_driver:     orm
    firewall_name: main
    use_listener: false
    user_class:   DemoApp\UtilisateurBundle\Entity\Utilisateur
```

parameter.yml
====
```
parameters:
    database_driver: pdo_sqlite
    database_host: 127.0.0.1
    database_port: null
    database_name: filmotheque
    database_user: root
    database_password: null
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    locale: fr
    secret: 16b10f9d2e7885152d41ea6175886563a
    debug_toolbar: true
    debug_redirects: false
    use_assetic_controller: true
```

kernel.php
===
```php
   public function registerBundles()
    {
    $bundles = array(
    ....
			new FOS\UserBundle\FOSUserBundle(),
            new DemoApp\FilmothequeBundle\DemoAppFilmothequeBundle(),
            new DemoApp\UtilisateurBundle\DemoAppUtilisateurBundle(),
            )};
```
composer.json
===
 ```
 "require": {
 ...
		"friendsofsymfony/user-bundle": "1.3.4"
		}
	```
