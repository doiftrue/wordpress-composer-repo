Composer Repository for WordPress core instalation
==================================================

This repository just create `packages.json` file that can be used as yet another composer repository. The repository contains urls for the WordPress core. All source URLs are the official wordpress.org urls. 

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

### Option 1: Now install WP into the root dir:

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

Note: `post-install-cmd` command runs automatically after `composer install` and copy the vendor to current root dir.

### Option 2: Install WP without `wp-content` directory. 

Use `-patch` suffix for defined version:

```json
{
	"require": {
		"wordpress/wordpress": "~6.3.0-patch"
	},
	"scripts": {
		"post-autoload-dump": "mkdir -p ./wp/ && rsync -a --delete ./vendor/wordpress/wordpress/ ./wp/"
	}
}
```


Update
------
To Update (re-generate) repo data (`packages.json` file) run commad:

```sh
$ make generate
```
