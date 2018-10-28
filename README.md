# DEWP Planet Feed #
* Contributors:      Florian Brinkmann, Caspar Hübinger, Dominik Schilling
* Requires at least: 4.9.8
* Tested up to:      4.9.8
* License:           GPLv3
* License URI:       http://www.gnu.org/licenses/gpl-3.0.html

## Description ##
Generates a custom feed `dewp-planet` for posts. Adds a checkbox to the _Publish_ meta box in order to explicitly add a post to that custom feed.

![Screenshot (GIF)](https://github.com/deworg/dewp-planet-feed/blob/master/screenshot.gif?raw=true)

---

## Changelog

### 0.5.1– 28.10.2018

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
