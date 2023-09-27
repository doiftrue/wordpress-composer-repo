Composer Repository for WordPress core instalation
==================================================

This repository simply generates a `packages.json` file that can be utilized as additional Composer repository. 

**OFFICIAL WordPress.org files:** This composer repository contains URLs to the WordPress zip archives hosted on the WordPress.org. Examples: 
- `https://downloads.wordpress.org/release/wordpress-6.3.1.zip`
- `https://downloads.wordpress.org/release/wordpress-6.3.1-no-content.zip`


> [!IMPORTANT]  
> This repository packages is designated with `type: wordpress-dropin` (rather than `type: wordpress-core`). This is non-standard way, but it allowes to use the default [composer/installers](https://github.com/composer/installers) package. This package, in turn, allows you to install WordPress core in the desired folder.


Usage
-----
Add repository to your `composer.json` file as follows.

#### Full WP files:

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

#### WP core only (without wp-content - minus ~10MB):

If you need WP build without `wp-content` directory. Append `/no-content` to the base repository URL: 

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

> [!WARNING]
> Do not use `composer/installers` for the full WP files instalation. Instead, utilize a custom command for `post-autoload-dump` event to copy the required files. This is because, during a future update, composer will overwrite all files in your project.

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
        "post-autoload-dump": "rsync -a --exclude={wp-content/,wp-config-sample.php} ./vendor/wordpress/wordpress/* ./"
    }
}
```

Command under `post-autoload-dump` copies the `vendor/wordpress/wordpress` files to current root directory. It requires: `linux` system with `rsync` package installed.

The `post-autoload-dump` command runs automatically after `composer install` or `composer update`.


### Option 2: Install WP core files only

Here we append `/no-content` to the repository URL. This will result in downloading WordPress core files without the `wp-content` folder.

In this scenario, you can utilize the [composer/installers](https://github.com/composer/installers) package to place WordPress core files to the desired folder (is the `wp/` folder in example below).

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


Versioning Info
---------------

WP Versions Schema: `6.3.1` = `MAJOR.MINOR.PATCH`.

Examples:
- `"dev-master"` - the latest development version.
- `"~6.3.0"` - allowes update PATCHs (last number) only. Same as `"6.3.*"`.
- `"^6.3.0"` - allowes update MINORs and PATCHs (all numbers except first). Same as `"^6"`.




Docs
----
https://getcomposer.org/doc/05-repositories.md#hosting-your-own
