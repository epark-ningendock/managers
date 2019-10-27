<?php

namespace App\Imports;

use App\Course;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Row;

class CourseImport extends ImportAbstract
{
    use SoftDeletes;

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'course_no';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Course::class;
    }

    /**
     * @param Row $row
     * @return mixed
     * @throws \Exception
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $model = new Course([
            'hospital_id' => $this->getId('hospitals', $row['hospital_no']),
            'calendar_id' => $row['calender'],
            'code' => $row['code'],
            'name' => $row['name'],
            'web_reception' => $row['web_reception'],
            'is_category' => $row['flg_category'],
            'course_sales_copy' => $row['course_sales_copy'],
            'course_point' => $row['course_point'],
            'course_notice' => $row['course_notice'],
            'course_cancel' => $row['course_cancel'],
            'is_price' => $row['flg_price'],
            'price' => $row['price'],
            'is_price_memo' => $row['flg_price_memo'],
            'price_memo' => $row['price_memo'],
            'regular_price' => $row['price_2'],
            'discounted_price' => $row['price_3'],
//            'tax_class' => $row[''], // @todo
            'display_setting' => $row['display_setting'],
            'pv' => $row['pv'],
            'pvad' => $row['pvad'],
            'order' => $row['order'],
            'cancellation_deadline' => '0',
            'reception_start_date' => null,
            'reception_end_date' => null,
            'pre_account_price' => $row['pre_account_price'],
            'is_local_payment' => $row['flg_local_payment'],
            'is_pre_account' => $row['flg_pre_account'],
            'auto_calc_application' => $row['auto_calc_application'],
            'reception_acceptance_date' => null,
            'status' => $row['status'],
            'created_at' => $row['rgst'],
            'updated_at' => $row['updt'],
            'publish_start_date' => '2019-01-01',
        ]);

        $model->save();
        $this->deleteIf($model, $row, 'status', ['X']);
        $this->setId($model, $row);
    }
}
