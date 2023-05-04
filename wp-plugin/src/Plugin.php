<?php

namespace StocksWp;

class Plugin {
	public static function bootstrap() {
		# Run DB updates if necessary
		add_action('init', [Db::class, 'checkVersion']);

		# Register post types
		add_action('init', [PostTypes::class, 'register']);

		# Register REST routes
		add_action('rest_api_init', [Rest::class, 'registerRoutes']);

		# Register CLI commands
		if('cli' === PHP_SAPI) {
			Cli::registerCommands();
		}

		# Various WP hooks
		add_action('pre_get_posts', [Hooks\PreGetPosts::class, 'main']);
	}
}
