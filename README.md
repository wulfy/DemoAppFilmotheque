DemoAppFilmotheque
==================

bundle basé sur tuto pour SF2, MAJ pour SF2.5 + utilisation listener et tests


config.yml
==================
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

kernel.php
==================
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
================
 ```
 "require": {
 ...
		"friendsofsymfony/user-bundle": "1.3.4"
		}
	```
