<?php

namespace StocksWp;

class Cli {
	public static function registerCommands() {
		# wp stocks sync-company-information
		\WP_CLI::add_command('stocks sync-company-information', [Cli\SyncCompanyInformationCommand::class, 'execute']);
		# wp stocks sync-cpi
		\WP_CLI::add_command('stocks sync-cpi', [Cli\SyncCpiCommand::class, 'execute']);

		# wp stocks sync-stocks
		\WP_CLI::add_command('stocks sync-stocks', [Cli\SyncStocksCommand::class, 'execute']);

		# wp stocks sync-stock-price
		\WP_CLI::add_command('stocks sync-stock-price', [Cli\SyncStockPriceCommand::class, 'execute']);

		# wp stocks sync-stock-price-velocity
		\WP_CLI::add_command('stocks sync-stock-price-velocity', [Cli\SyncStockPriceVelocityCommand::class, 'execute']);

		# wp stocks sync-stock-price-velocity-sma
		\WP_CLI::add_command('stocks sync-stock-price-velocity-sma', [Cli\SyncStockPriceVelocitySmaCommand::class, 'execute']);

		# wp stocks sync-stock-price-acceleration
		\WP_CLI::add_command('stocks sync-stock-price-acceleration', [Cli\SyncStockPriceAccelerationCommand::class, 'execute']);

		# wp stocks sync-stock-price-sma
		\WP_CLI::add_command('stocks sync-stock-price-sma', [Cli\SyncStockPriceSmaCommand::class, 'execute']);

		# wp stocks sync-stock-sma-velocity
		\WP_CLI::add_command('stocks sync-stock-sma-velocity', [Cli\SyncStockSmaVelocityCommand::class, 'execute']);
	}
}
