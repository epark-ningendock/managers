<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--path= : dumpファイルの出力先}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database dump to sql file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->option('path');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $database = config('database.connections')[config('database.default')];
        $this->dump_file = sprintf('%s/%s-%s.dump',
            '/' . trim(realpath($path), '/'),
            $database['database'],
            date('YmdHis')
        );

        $host = $database['host'];
        $user = $database['username'];
        $password = $database['password'];

        $command = "mysqldump -u {$user} -p{$password} -h {$host} {$database['database']} > {$this->dump_file}";
        exec($command);

        $this->info("VBackupfile: {$this->dump_file}");
    }
}
