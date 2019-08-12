<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportCsv extends Command
{
    private $directory;
    private $classes = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {--backup} {--fresh : 既存のデータベースを再構築します。} {--dir= : ディレクトリを指定}';

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
    }

    /**
     * 関連するクラス名を取得する
     * @param $basename
     * @param string $type
     * @return string |null
     */
    private function getClass($basename, $type = 'model'): ?string
    {
        if (!array_key_exists($basename, $this->classes)) {
            return null;
        }
        return $this->classes[$basename][$type];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csv_files = [];

        $backup = $this->option('backup');
        if ($backup) {
            $this->line('既存のデータベースをバックアップします。');
            Artisan::call('db:backup', ['--path' => './storage/app/backup']);
        }

        $directory = $this->option('dir');
        if (!file_exists($directory)) {
            $this->error('指定されたディレクトリが存在しません!!');
        }

        $fresh = $this->option('fresh');
        if ($fresh) {
            if (!$backup) {
                $this->line('既存のデータベースをバックアップします。');
                Artisan::call('db:backup', ['--path' => './storage/app/backup']);
            }

            $this->line('データベースを初期化します。');
            Artisan::call('migrate:refresh');
        }

        $this->directory = trim($directory, '/');
        foreach (glob("{$this->directory}/*.csv") as $filename) {
            $basename = basename($filename);
            $classname = $this->getClass($basename, 'model');
            $table = is_null($classname) ? null : (new $classname)->getTable();
            $csv_files[] = [
                $basename,
                $classname ?? '** UNDEFINED **',
                $table ?? '** UNDEFINED **'
            ];
        }
        $this->info('CSVファイルをチェックします');
        $this->table(['ファイル名', '対象クラス', 'テーブル名'], $csv_files);

        $this->info('インポートを開始します。');

        $this->import('m_pref.csv');
        $this->import('m_item_type.csv');
        $this->import('m_item_category_dai.csv');
        $this->import('m_item_category_chu.csv');
        $this->import('m_item_category_sho.csv');
        $this->import('m_hospital.csv');
        $this->import('m_hospital_category_dai.csv');
        $this->import('m_hospital_category_chu.csv');
        $this->import('m_hospital_category_sho.csv');
        $this->import('m_staff.csv');
        $this->import('m_course_basic.csv');
        $this->import('m_course_detail.csv');
        $this->import('m_course_question.csv');
        $this->import('m_hospital_file.csv');

        $this->info('インポートを完了しました。');
    }

    /**
     * インポート
     * @param string $basename
     */
    private function import(string $basename)
    {
        $classname = $this->getClass($basename, 'import');
        $this->line("{$classname}");
        (new $classname)->withOutput($this->output)->import($this->directory . '/' . $basename);
    }
}
