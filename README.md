# Stock platform example sketch

This repo represents various pieces of a bare bones stock analysis platform that I built as a way to learn about stocks and the stock market.

This is not a full and exact copy of the original repo, but rather has several items for reference to give a general idea
of what the overall codebase looked like.

I didn't wish to upload a fully functional version of the project since it requires a database and API keys; and since I don't think anyone
should really ever use it and possibly mistake it for financial advice or something insightful or useful that other platforms don't 
already offer in a full-featured manner. It was simply a learning tool for myself that I used locally on my computer at one point.

The contents of the project that are highlighted here are:

* `/js`
  * Models for object types we use
  * Vue.js stuff
    * Vuex store
    * Vue components
    * Route definitions
* `/php`
  * `src` folder which is in sync with Composer's autoloader and has lots of various classes. These are classes that are generic enough to not have anything to do with WordPress
  * `tests` folder for unit tests (run by PhpUnit via Composer script)
* `/wp-plugin`
  * Main file to define plugin and bootstrap its PHP code
  * `src` folder which is autoloaded by Composer and has classes pertaining to WordPress integration
    * Register CLI commands with WP-CLI
    * Database access methods
    * Hook registration
    * Post type definitions
    * Register REST routes with WordPress REST API

Finally, there are other various files (`.lando.yml`, `composer.json`, etc.) in the root folder to give an indication of the tooling that I used to build the project.
