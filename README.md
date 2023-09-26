Composer Repository for WordPress core instalation
==================================================

This repository simply generates a `packages.json` file that can be utilized as another Composer repository. It includes URLs pointing to the WordPress core zip archives hosted on the official WordPress.org site.



Usage
-----
Add repository to your `composer.json` file as follows:

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/doiftrue/wordpress-composer-repo/main/repo"
        }
    ]
}
```

If you need WP core files only without `wp-content` directory. Append : 

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/doiftrue/wordpress-composer-repo/main/repo/no-content"
        }
    ]
}
```


### Option 1: Install Full WP into the root dir

**WARNING:** Do not use `composer/installers` for the full WP files instalation. This is because, during a future update, composer will overwrite all files in your project. Instead, utilize a custom `post-autoload-dump` script to copy the required files to the designated directory. 

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/doiftrue/wordpress-composer-repo/main/repo"
        }
    ],
    "require": {
        "wordpress/wordpress": "~6.3.0"
    },
    "scripts": {
        "post-autoload-dump": "rsync -a --exclude 'wp-content/' ./vendor/wordpress/wordpress/* ./"
    }
}
```

The `post-autoload-dump` command runs automatically after `composer install` or `composer update`. It copies the `vendor/wordpress/wordpress` files to current root directory.


### Option 2: Install WP core files only to `wp` folder 

Here, we append /no-content to the repository URL. This will result in downloading WordPress core files without the wp-content folder.

In this scenario, you can utilize the "composer/installers" package to place WordPress to the desired folder (is the `wp/` folder in example below).

```json
{
    "repositories": [
        {
            "type": "composer",
            "url": "https://raw.githubusercontent.com/doiftrue/wordpress-composer-repo/main/repo/no-content"
        }
    ],
    "require": {
        "wordpress/wordpress": "~6.3.0",
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


Versioning
----------

WP Versions Schema: `6.3.1` = `MAJOR.MINOR.PATCH`.

Examples:
- `"dev-master"` - the latest development version.
- `"~6.3.0"` - allowes update PATCHs only. Same as `"6.3.*"`.
- `"^6.3.0"` - allowes update MINORs and PATCHs. Same as `"^6"`.



Notes
-----

- **WARNING:** The package is designated with `type: wordpress-dropin` (rather than `type: wordpress-core`) to enable the utilization of the default [composer/installers](https://github.com/composer/installers) package. This allows for the installation of the WordPress core into the desired folder.



Docs
----
https://getcomposer.org/doc/05-repositories.md#hosting-your-own
