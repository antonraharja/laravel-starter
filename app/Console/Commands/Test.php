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
	}
}
