<?php

namespace App\Console\Commands;

use Base\Registry\SimpleRegistry;
use Illuminate\Console\Command;

class Test extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'app:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Simple tests';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		// dump((new SimpleRegistry)->all()->toNestedArray());
		// dump((new SimpleRegistry)->get('themes')->toNestedArray());
		// dump((new SimpleRegistry)->getGroup('themes'));
		// dump((new SimpleRegistry)->getGroup(['themes']));
		dump((new SimpleRegistry)->getGroup(['themes', 'timezones']));

		$IPs = [
			'192.168.0.1',
			'192.168.0.1/24',
			'192.168.0.1-192.168.0.5',
			'exampleText',
			'example.com'
		];
		foreach ( $IPs as $IP ) {
			echo 'IP: ' . $IP . ' -> ' . dump(isIP($IP)) . PHP_EOL;
		}
	}
}