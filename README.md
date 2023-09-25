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

### Option 1: Install full WP into the root dir

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


### Option 2: Install WP core files only (without `wp-content` directory) to `wp` dir 

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


Versioning notes
----------------

This repository contains only last versions of each WP branch (minor updates). For example, if you specified exact version like this: `"wordpress/wordpress": "6.2.1"`, when `6.2.2` will be released `6.2.2` will be downloaded, but not `6.2.1`. 

WP Versions Schema: `6.3.1` = `MAJOR.MINOR.PATCH`.

Examples:
- `"dev-master"` - the latest development version.
- `"~6.3.0"` - allowes update PATCH number only. Same as `"6.3.*"`.
- `"^6.3.0"` - allowes update MINOR and PATCH. Same as `"^6"`.

Examples for `-patch` suffix (WP build without wp-content directory):
- `"dev-master"` - there is no "no-content" build for "dev-master" version. 
- `"~6.3.0-patch"` - allowes update PATCH number only. Same as `"6.3.*-patch"`.
- `"^6.3.0-patch"` - allowes update MINOR and PATCH. Same as `"^6-patch"`.
