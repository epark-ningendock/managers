<?php


namespace App\Imports;


use App\ConvertedIdString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

abstract class ImportBAbstract implements WithProgressBar, OnEachRow, SkipsOnError, WithHeadingRow
{
    use Importable;
    use SkipsErrors;

    protected $hospital_no;

    /**
     * ImportBAbstract constructor.
     * @param $hospital_no
     */
    public function __construct($hospital_no)
    {
        $this->hospital_no = $hospital_no;
    }

    public function getValue(array $row, string $key)
    {
        if (!array_key_exists(strtolower($key), $row)) {
            return null;
        }
        $val = $row[strtolower($key)];
        if ($val === '\N') {
            return null;
        }
        return $val;
    }

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
        $model = ConvertedIdString::where('table_name', $table)
            ->where('old_id', $old_id)
            ->first();
        if (!is_null($model)) {
            return $model->new_id;
        }
        Log::error(sprintf('%s に %d が存在しません。', $table, $old_id));
        return null;
    }

    /**
     * @param Model $model
     * @param array|null $row
     */
    protected function setId(Model $model, array $row = null)
    {
        $table = $model->getTable();
        $old_id = $this->getValue($row, $this->getOldPrimaryKeyName());

        ConvertedIdString::firstOrCreate([
            'table_name' => $table,
            'old_id' => $old_id,
        ], [
            'table_name' => $table,
            'old_id' => $old_id,
            'new_id' => $model->id,
        ]);
    }
}