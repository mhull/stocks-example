<?php
/**
 * Plugin Name: Stocks
 * Plugin URI:
 * Description:
 * Version: 0.0.0
 * Author: ResoundingEchoes
 * Author URI: https://resoundingechoes.net
 */

# Require the main Stocks project files
require_once __DIR__ . '/../_main.php';

# Define constants
define('STOCKS_WP_PLUGIN_DIR', __DIR__);

# Bootstrap the plugin
\StocksWp\Plugin::bootstrap();

function stocks_is_cli() {
	return defined('PHP_SAPI') && PHP_SAPI === 'cli';
}
