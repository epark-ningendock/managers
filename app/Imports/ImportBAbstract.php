<?php


namespace App\Imports;


use App\ConvertedIdString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

abstract class ImportBAbstract implements WithProgressBar, OnEachRow, SkipsOnError
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

    /**
     * @param string $name
     * @return int
     */
    protected function getColIndex(string $name): int
    {
        return array_search($name, $this->getColumns());
    }

    /**
     * @param array $row
     * @param string|null $name
     * @return mixed|null
     */
    protected function getValue(array $row, string $name = null)
    {
        if (is_null($name)) {
            return null;
        }
        $value = $row[$this->getColIndex($name)];
        if ($value == '\N') {
            return null;
        }
        return $value;
    }

    /**
     * @return array
     */
    abstract public function getColumns(): array;

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
        if ($model) {
            return $model->new_id;
        }
        Log::warning(sprintf('%s に %d が存在しません。', $table, $old_id));
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