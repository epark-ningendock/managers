<?php

namespace App\Imports;

use App\Hospital;
use App\Reservation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;

class ReservationImport extends ImportBAbstract implements WithEvents
{
    use RegistersEventListeners;

    /**
     * 旧システムのインポート対象テーブルのプライマリーキーを返す
     * Returns the primary key of the import target table of the old system
     * @return string
     */
    public function getOldPrimaryKeyName(): string
    {
        return 'APPOINT_ID';
    }

    /**
     * 新システムの対象クラス名を返す
     * Returns the target class name of the new system
     * @return string
     */
    public function getNewClassName(): string
    {
        return Reservation::class;
    }

    /**
     * @param Row $row
     * @return mixed
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        try {
//            $old_id = sprintf('%s_%s',
//                $this->hospital_no,
//                $this->getValue($row, 'APPOINT_ID')
//            );

//            $reservation_id = $this->getIdForA('reservations', $old_id);

//            $model = Reservation::find($reservation_id);

//            if (is_null($model)) {
//                return;
//            }

            $model = new Reservation([
                'hospital_id' => Hospital::withTrashed()->where('old_karada_dog_id',
                    $this->hospital_no)->get()->first()->id,
                'reservation_date' => $this->getValue($row, 'APPOINT_DATE'),
                'start_time_hour' => floor($this->getValue($row, 'START_TIME') / 100),
                'start_time_min' => fmod($this->getValue($row, 'START_TIME'), 100),
                'end_time_hour' => floor($this->getValue($row, 'END_TIME') / 100),
                'end_time_min' => fmod($this->getValue($row, 'END_TIME'), 100),
                'reservation_status' => 0,
                'time_selected' => $this->getValue($row, 'RESERVATION_METHOD'),
                'is_repeat' => $this->getValue($row, 'VISIT_HISTORY_FG') ?: 0,
                'is_representative' => $this->getValue($row, 'REPRESENTATIVE_FG') ?: 1,
            ]);
            $model->save();
            $this->setId($model, $row);

        } catch (\Throwable $e) {
            Log::error($e->getTraceAsString());
        }

    }
}
