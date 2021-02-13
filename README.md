# DEWP Planet Feed #
* Contributors:      Florian Brinkmann, Caspar Hübinger, Dominik Schilling, Torsten Landsiedel
* Requires at least: 4.9.8
* Tested up to:      5.6.1
* License:           GPLv3
* License URI:       http://www.gnu.org/licenses/gpl-3.0.html

## Description
Generates a custom feed `dewp-planet` for posts. Adds a checkbox to the _Publish_ meta box in order to explicitly add a post to that custom feed.

![Screenshot (GIF)](https://github.com/deworg/dewp-planet-feed/blob/master/screenshot.gif?raw=true)

---

## Installation
### Via SFTP or backend
1. Download the asset `dewp-planet-feed.zip` from the [latest release](https://github.com/deworg/dewp-planet-feed/releases/latest).
1. Upload the folder `dewp-planet-feed` to the `/wp-content/plugins/` directory, or directly upload the ZIP through the »Plugins« › »Add new« › »Upload plugin« screen in the WordPress backend.
1. Activate the plugin through the »Plugins« menu in WordPress.

### With Composer
If you would like to pull the plugin with Composer (e.g. with [Bedrock](https://roots.io/bedrock/)) you have to compile the JavaScript assets yourself or use the [Composer Asset Compiler](https://github.com/inpsyde/composer-asset-compiler). This plugin is compatible and even provides the pre-compiled assets for each release.

To pull this Plugin with Composer, follow these steps:

1. Add `git@github.com:deworg/dewp-planet-feed.git` as an addional Github repository to your `composer.json`:
```json
    "repositories": [
        {
            "type": "github",
            "url": "git@github.com:deworg/dewp-planet-feed.git"
        }
    ],
```
2. Require the plugin
```bash
$ composer require deworg/dewp-planet-feed
```
3. Compile the assets with `npm install && npm run build:production` in the plugin directory or setup the [Composer Asset Compiler](https://github.com/inpsyde/composer-asset-compiler) with:
```bash
$ composer require inpsyde/composer-assets-compiler
```
4. If you choose the Composer Asset Compiler, run `composer compile-assets` after every plugin update time or activate auto run in your `composer.json`:
```json
    "extra": {
        "composer-asset-compiler": {
            "auto-run": true,
        }
    }
```
5. Done. If you choose auto run, try it with `composer update` or `composer install`

---

## Changelog

### 1.0.0 – 13.02.2021

* Enhancement: Add possibility to install via composer (see *Installation* part of readme for instructions) – thanks @goaround.
* Fix: Make `wp_planet_feed__post_types` work with Gutenberg.

### 0.5.1 – 28.10.2018

* Fix: Make it work with current Gutenberg version.

### 0.5.0 – 18.08.2018

**The plugin requires WordPress 4.9.8 now**

* Added Gutenberg support.

### 0.4
* Fix: changed dependency for admin stylesheet from `edit` to `wp-admin`.

### 0.3
* Enhancement: added help link to Post screen option.
* Enhancement: added About document.
* Architecture: moved code for Post screen option to separate file.
* Architecture: moved inline CSS to separate file.

### 0.2
* Enhancement: lower priority for publishing checkbox.
* Clean-up: Renamed plugin to __DEWP Planet Feed__ and PHP class to `DEWP_Planet_Feed`.

### 0.1
* Initial fork from [original class](https://github.com/ocean90/wpgrafie-theme/blob/master/classes/class-ds-wpgrafie-wp-planet-feed.php).
* Added de/activation hooks + UI candy.
