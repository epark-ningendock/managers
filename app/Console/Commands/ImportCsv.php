<?php

namespace App\Console\Commands;

use App\Hospital;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
    protected $signature = 'import:csv {--backup} {--fresh : 既存のデータベースを再構築します。} {--seed : seed 時に指定する} {--a= : ディレクトリを指定} {--b= : ディレクトリを指定}';

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
        if (!$this->checkDefinition('a')) {
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

//        if (!$this->confirm('このまま続けてよろしいですか？')) {
//            $this->info('インポートを完了しました。');
//            return;
//        }
        $this->info('Start import: B');
        $this->import('b');
        $this->info('End import: B');

        $this->info('インポートを完了しました。');
    }

    /**
     * インポート
     */
    private function import($ab)
    {
        $files = [];
        $directory = $this->option($ab);
        if (is_null($directory)) {
            return;
        }
        switch ($ab) {
            case 'a':
                $this->import_a();
                break;
            case'b':
                $this->import_b();
                break;
        }
    }

    /**
     * @param $files
     */
    private function import_a()
    {
        $seed = $this->option('seed');
        $files = array_keys($this->classes);
        foreach ($files as $i => $basename) {
            $realpath = $this->directory . '/' . $basename;
            if (!file_exists($realpath)) {
                $this->warn('Skipped: %s is not found.', $basename);
            }

            // seed => false をスキップする
            if ($seed && !$this->getClass('a', $basename, 'seed')) {
                continue;
            }

            $classname = $this->getClass('a', $basename, 'import');
            $this->line(sprintf("A[ %d / %d ] %s", $i + 1, count($files), $classname));
            (new $classname)->withOutput($this->output)->import($realpath);
        }
    }

    /**
     *
     */
    private function import_b()
    {
        $hospital_nos = [];
        $path = realpath($this->option('b'));
        foreach (glob($path . '/*') as $dir) {
            $arr_dirs = explode(DIRECTORY_SEPARATOR, $dir);
            $hospital_no = end($arr_dirs);
            foreach (glob($path . "/{$hospital_no}/*.csv") as $file) {
                $hospital_nos[$hospital_no][basename($file)] = [
                    'realpath' => $file,
                ];
            }
        }

        $files = array_keys($this->classes_b);
        foreach ($hospital_nos as $hospital_no => $arr) {
            $this->line(sprintf("B[ %s ]", $hospital_no));

            if (!$this->checkHospitalNo($hospital_no)) {
                $this->error(sprintf('%s not found.', $hospital_no));
                Log::error(sprintf('%s not found.', $hospital_no));
                continue;
            }

            foreach ($files as $i => $file) {
                $realpath = $hospital_nos[$hospital_no][$file]['realpath'];
                $import_class = $this->getClass('b', $file, 'import');

                if (filesize($realpath) == 0) {
                    continue;
                }

                $this->line(sprintf("B[ %d / %d ] %s", $i + 1, count($files), $import_class));

                try {
                    (new $import_class($hospital_no, $realpath))->withOutput($this->output)->import($realpath);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }

    /**
     * @param string $type
     * @return bool
     */
    private function checkDefinition(string $type)
    {
        $directory = $this->option($type);
        if (is_null($directory)) {
            return true;
        }
        if (!file_exists($directory)) {
            $this->error('指定されたディレクトリが存在しません!!');
            return true;
        }

        $csv_files = [];
        $this->directory = trim($directory, '/');

        foreach (glob("{$this->directory}/*.csv") as $filename) {
            $basename = basename($filename);
            $classname = $this->getClass($type, $basename, 'model');
            $table = is_null($classname) ? null : (new $classname)->getTable();
            $csv_files[] = [
                $basename,
                $classname ?? '** UNDEFINED OR LinkTable **',
                $table ?? '** UNDEFINED OR LinkTable **'
            ];
        }

        $this->info('CSVファイルをチェックします');
        $this->table(['ファイル名', '対象クラス', 'テーブル名'], $csv_files);

        if (count($csv_files) == 0) {
            return true;
        }

//        if (!$this->confirm('このまま続けてよろしいですか？')) {
//            return false;
//        }

        return true;
    }

    private function checkHospitalNo($hospital_no)
    {
        return is_null(Hospital::withTrashed()->where('old_karada_dog_id', $hospital_no)->get()) === false;
    }
}
