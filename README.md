Composer Repository for WordPress core instalation
==================================================

This repository just create `packages.json` file that can be used as yet another composer repository. The repository contains urls for the WordPress core. All source URLs are the official wordpress.org urls. 

WARNING: all packages marked with `type: wordpress-dropin` (not `type: wordpress-core`) in order to have the ability to use default [composer/installers](https://github.com/composer/installers) to install the WordPress core to desired folder. 


Usage
-----
Add repository to your composer.json file as follows:

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/doiftrue/wordpress-composer-repo/master"
        }
    ]
}
```

### Option 1: Now install WP into the root dir

Warning: Do not employ `composer/installers` or designate a custom folder for the package. This is because, during a future update, it will definitively overwrite all files in your project. Instead, utilize a custom post-autoload-dump script to copy the required files to the designated directory. 

```json
{
    "require": {
        "wordpress/wordpress": "~6.3.0"
    },
    "scripts": {
        "post-autoload-dump": "rsync -a --exclude 'wp-content/' ./vendor/wordpress/wordpress/* ./"
    }
}
```

Note: the command under `post-autoload-dump` runs automatically after `composer install` or `composer update`. It copies the `vendor/wordpress/wordpress` files to current root directory.


### Option 2: Install WP core files only (without `wp-content` directory) to separate `wp` folder. 

Here we use `-patch` suffix for defined version, this will lead to download WordPress package with core files only (without wp-content directory):

```json
{
    "require": {
        "wordpress/wordpress": "~6.3.0-patch",
        "composer/installers": "*"
    },
    "extra": {
        "installer-paths": {
            "wp/": [ "wordpress/wordpress" ]
        }
    },
    "config": {
        "allow-plugins": {
            "composer/installers": true
        }
    }
}
```


Update
------
To Update (re-generate) repo data (`packages.json` file) run commad:

```sh
$ make generate
```
