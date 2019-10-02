<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use GuzzleHttp\Exception\ClientException;

use Log;

/**
 * BaseManagerCommand
 * バッチベースクラス
 *
 * @author footbank.co.jp
 * @copyright 株式会社EPARK人間ドック
 * @package EPARK人間ドック
 * @version 20190731
 */
abstract class BaseManagerCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Log::info("$this->description.処理開始.\n");
            DB::beginTransaction();
            $this->_execute();
            DB::commit();
            Log::info("$this->description.処理終了.\n");
        }
        catch (ClientException $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        } 
        catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Execute method.
     *
     * @return mixed
     */
    abstract public function _execute();
}
