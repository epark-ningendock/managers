<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\ResponseCache\Facades\ResponseCache;

class CacheClear extends Command{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cacheclear {--tag= : tagName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear Response Cache';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$tag = $this->option('tag');

		ResponseCache::clear([$tag]);
	}
}
