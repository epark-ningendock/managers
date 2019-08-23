<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportCsv extends Command
{
    private $directory;
    private $classes = [];
    private $classes_b = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {--backup} {--fresh : 既存のデータベースを再構築します。} {--a= : ディレクトリを指定} {--b= : ディレクトリを指定}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '旧システムのデータをインポートします。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->classes = config('import');
        $this->classes_b = config('import_b');
    }

    /**
     * 関連するクラス名を取得する
     * @param $basename
     * @param string $type
     * @return string |null
     */
    private function getClass($ab, $basename, $type = 'model'): ?string
    {
        if ($ab == 'a') {
            if (!array_key_exists($basename, $this->classes)) {
                return null;
            }
            return $this->classes[$basename][$type];
        } else if ($ab == 'b') {
            if (!array_key_exists($basename, $this->classes_b)) {
                return null;
            }
            return $this->classes_b[$basename][$type];
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csv_files = [];

        $directory = $this->option('dir');
        if (!file_exists($directory)) {
            $this->error('指定されたディレクトリが存在しません!!');
        }

        $this->directory = trim($directory, '/');
        foreach (glob("{$this->directory}/*.csv") as $filename) {
            $basename = basename($filename);
            $classname = $this->getClass($basename, 'model');
            $table = is_null($classname) ? null : (new $classname)->getTable();
            $csv_files[] = [
                $basename,
                $classname ?? '** UNDEFINED OR LinkTable **',
                $table ?? '** UNDEFINED OR LinkTable **'
            ];
        }
        $this->info('CSVファイルをチェックします');
        $this->table(['ファイル名', '対象クラス', 'テーブル名'], $csv_files);

        if (!$this->confirm('このまま続けてよろしいですか？')) {
            return;
        }

        $backup = $this->option('backup');
        $fresh = $this->option('fresh');

        if ($fresh) {
            $this->line('既存のデータベースをバックアップします。');
            Artisan::call('db:backup', ['--path' => './storage/app/backup']);

            $this->line('データベースを初期化します。');
            Artisan::call('migrate:fresh');
        } elseif ($backup) {
            $this->line('既存のデータベースをバックアップします。');
            Artisan::call('db:backup', ['--path' => './storage/app/backup']);
        }

        $this->info('インポートを開始します。');

        $this->info('Start import: A');
        $this->import('a');
        $this->info('End import: A');

        if (!$this->confirm('このまま続けてよろしいですか？')) {
            $this->info('インポートを完了しました。');
            return;
        }
        $this->info('Start import: B');
        $this->import('b');
        $this->info('End import: B');

        $this->info('インポートを完了しました。');
    }

    /**
     * インポート(A)
     */
    private function import($ab)
    {
        $files = [];
        switch ($ab) {
            case 'a':
                $files = array_keys($this->classes);
                break;
            case'b':
                $files = array_keys($this->classes_b);
                break;

        }
        foreach ($files as $i => $basename) {
            $realpath = $this->directory . '/' . $basename;
            if (!file_exists($realpath)) {
                $this->warn('Skipped: %s is not found.', $basename);
            }
            $classname = $this->getClass($ab, $basename, 'import');
            $this->line(sprintf("[ %d / %d ] %s", $i + 1, count($files), $classname));
            (new $classname)->withOutput($this->output)->import($realpath);
        }
    }
}
