<?php

namespace App\Imports;

use App\ConvertedId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;
use Throwable;

abstract class ImportAbstract implements WithProgressBar, WithHeadingRow, OnEachRow, SkipsOnError
{
    use Importable;
    use SkipsErrors;

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    abstract public function getOldPrimaryKeyName(): string;

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    abstract public function getNewClassName(): string;

    /**
     * @param Row $row
     * @return mixed
     */
    abstract public function onRow(Row $row);

    /**
     * 新しいIDを返す
     * Returns a new ID
     * @param $table
     * @param $old_id
     * @return mixed
     */
    protected function getId($table, $old_id)
    {
        $model = ConvertedId::where('table_name', $table)
            ->where('old_id', $old_id)
            ->first();
        if ($model) {
            return $model->new_id;
        }
        Log::warning(sprintf('%s に %d が存在しません。', $table, $old_id));
        return null;
    }


    /**
     * @param Model $model
     * @param array $row
     * @param string $column
     * @param array $values
     * @throws \Exception
     */
    protected function deleteIf(Model $model, array $row, string $column, array $values)
    {
        if (in_array($row[$column], $values)) {
            $model->delete();
        }
    }

    /**
     * 新しいIDを設定する
     * Set a new ID
     * @param Model $model
     * @param array $row
     */
    protected function setId(Model $model, array $row)
    {
        $table = $model->getTable();
        $old_id = $row[$this->getOldPrimaryKeyName()];

        ConvertedId::firstOrCreate([
            'table_name' => $table,
            'old_id' => $old_id,
        ], [
            'table_name' => $table,
            'old_id' => $old_id,
            'new_id' => $model->id,
        ]);
    }

    /**
     * @param Throwable $e
     */
    public function onError(Throwable $e)
    {
        Log::error($e->getMessage());
    }
}