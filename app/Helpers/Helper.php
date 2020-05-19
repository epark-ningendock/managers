<?php

if (! function_exists('inputSelectBoxSelected')) :

    function inputSelectBoxSelected($field_name, $field_value)
    {
        return (! empty(request($field_name)) && (request($field_name) == $field_value)) ? 'selected="selected"' : '';
    }

endif;


if (! function_exists('columnSorting')) :

    function columnSorting($sorting_name)
    {
        return (request($sorting_name) == 'asc') ? 'desc' : 'asc';
    }

endif;

if (! function_exists('queryForSorting')) :
    function queryForSorting($sorting_name)
    {
        return array_merge(request()->except($sorting_name), [$sorting_name => columnSorting($sorting_name)]);
    }
endif;

if (! function_exists('trimToNull')) :
    function trimToNull($value)
    {
        if (is_null($value)) {
            return null;
        }
        return strlen(trim($value)) == 0 ? null : trim($value);
    }
endif;

if (! function_exists('csvToArray')) :

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            return false;
        }
        $header = null;
        $data   = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (! $header) {
                    $header = $row;
                } else {
                    foreach ($row as $key => $value) {
                        $updateDataRow[$key] = ($value == 'NULL') ? null : $value;
                    }
                    $data[] = array_combine($header, $updateDataRow);
                }
            }
            fclose($handle);
        }

        return $data;
    }

endif;


if ( !function_exists('billingDateFilter') ) :

    function billingDateFilter($yearMonth = 0) {

        $yearMonth = $yearMonth ?? request('billing_month');

        if ( $yearMonth ) {
            $date = Carbon\Carbon::parse( $yearMonth . 28 );
            $date = ( $date->isCurrentMonth() ) ? now() : $date;

        } else {
            $date = now();
        }


        if ( $date->day < 21 ) {


            $startMonthNumber = ( $date->isCurrentMonth() ) ? $date->copy()->subMonth( 2 )->month : $date->copy()->subMonth( 1 )->month;
            $endMonthNumber   = ( $date->isCurrentMonth() ) ? $date->copy()->subMonth( 1 )->month : $date->month;

            $startedDate = $date->copy()->setDate( $date->year, $startMonthNumber, 21 );
            $endedMonth  = $date->copy()->setDate( $date->year, $endMonthNumber, 20 );

        } else {

            $startedDate = $date->copy()->setDate( $date->year, $date->copy()->subMonth( 1 )->month, 21 );
            $endedMonth  = $date->copy()->setDate( $date->year, $date->month, 20 );

        }

        if ($startedDate->month > $date->month) {
            $startedDate->year = $startedDate->year - 1;
        }

        $selectBoxMonths = [
            $startedDate->copy()->subMonth( 2 )->format( 'Y-m' ),
            $startedDate->copy()->subMonth( 1 )->format( 'Y-m' ),
            $startedDate->format( 'Y-m' ),
            $startedDate->copy()->addMonth( 1 )->format( 'Y-m' ),
            $startedDate->copy()->addMonth( 2 )->format( 'Y-m' ),
            $startedDate->copy()->addMonth( 3 )->format( 'Y-m' ),
        ];

        return [
            'startedDate'     => $startedDate->startOfDay(),
            'endedDate'       => $endedMonth->endOfDay(),
            'selectBoxMonths' => $selectBoxMonths,
        ];
    }

endif;

if (! function_exists('getAgeTargetDate')) :
    function getAgeTargetDate($birth, $reservationDate, $ageKisanKbn, $ageKisanDate, $medicalExamSysId)
    {
        $birthday = \Carbon\Carbon::createFromFormat('Ymd', $birth);
        if ($reservationDate) {
            $reservation_date = \Carbon\Carbon::createFromFormat('Y-m-d', $reservationDate);
        } else {
            $reservation_date = \Carbon\Carbon::today()->addDay(6);
        }
        $today = \Carbon\Carbon::today();
        if ($medicalExamSysId == config('constant.medical_exam_sys_id.tak')) {
            // 当日
            if ($ageKisanKbn == 1) {

                return calcAge($birthday, $reservation_date);
                // 受診月末
            } elseif ($ageKisanKbn == 2) {
                return calcAge($birthday, $reservation_date->endOfMonth());
            } elseif ($ageKisanKbn == 3) {
                if ($today->month == 1 || $today->month == 2 || $today->month == 3) {
                    $target_date = $today->subYear(1)->endOfYear();
                    return calcAge($birthday, $target_date);
                } else {
                    return calcAge($birthday, $today->endOfYear());
                }
            } elseif ($ageKisanKbn == 4) {
                $target_date = $today->setDate($today->year, 4, 1);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 5) {
                $target_date = $today->setDate($today->year, 3, 31);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 6) {
                $target_date = $today->endOfYear();
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 7) {
                $target_date = $today->setDate($today->year + 1, 4, 1);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 8) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                if ($today->month >= 4) {
                    if ($target_m <= 3) {
                        $target_y = $today->year;
                    } else {
                        $target_y = $today->year - 1;
                    }
                } else {
                    if ($target_m <= 3) {
                        $target_y = $today->year - 1;
                    } else {
                        $target_y = $today->year - 2;
                    }
                }
                $target_date = $today->setDate($target_y, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 9) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                if ($today->month >= 4) {
                    if ($target_m <= 3) {
                        $target_y = $today->year + 1;
                    } else {
                        $target_y = $today->year;
                    }
                } else {
                    if ($target_m <= 3) {
                        $target_y = $today->year;
                    } else {
                        $target_y = $today->year - 1;
                    }
                }
                $target_date = $today->setDate($target_y, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 10) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                if ($today->month >= 4) {
                    if ($target_m <= 3) {
                        $target_y = $today->year + 2;
                    } else {
                        $target_y = $today->year + 1;
                    }
                } else {
                    if ($target_m <= 3) {
                        $target_y = $today->year + 1;
                    } else {
                        $target_y = $today->year;
                    }
                }
                $target_date = $today->setDate($target_y, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 11) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                $target_date = $today->setDate($today->year - 1, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 12) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                $target_date = $today->setDate($today->year, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 13) {
                $target_m = (int)substr($ageKisanDate, 0, 2);
                $target_d = (int)substr($ageKisanDate, 2, 2);
                $target_date = $today->setDate($today->year + 1, $target_m, $target_d);
                return calcAge($birthday, $target_date);
            }
        } elseif ($medicalExamSysId == config('constant.medical_exam_sys_id.itec')) {

            if (empty($ageKisanDate)) {
                return calcAge($birthday, $reservation_date);
            }

            $targets = explode('/', $ageKisanDate);
            $m = $targets[0];
            $d = $targets[1];
            $y = \Carbon\Carbon::today()->year;

            if ($ageKisanKbn == 1) {
                if ($m < 4) {
                    $y = $y + 1;
                }
                $target_date = \Carbon\Carbon::create($y, $m, $d);
                return calcAge($birthday, $target_date);
            } elseif ($ageKisanKbn == 2) {
                if ($m < 4) {
                    $y = $y + 2;
                } else {
                    $y = $y + 1;
                }
                $target_date = \Carbon\Carbon::create($y, $m, $d);
                return calcAge($birthday, $target_date);
            }
        }
    }

    function calcAge($birth_day, $target_date) {
        $birth_year = (int)date("Y",$birth_day);
        $birth_month = (int)date("m",$birth_day);
        $birth_day = (int)date("d",$birth_day);

        // 現在の年月日を取得
        $now_year = $target_date->year;
        $now_month = $target_date->month;
        $now_day = $target_date->day;

        // 年齢を計算
        $age = $now_year - $birth_year;

        // 「月」「日」で年齢を調整
        if( $birth_month === $now_month ) {

            if( $now_day < $birth_day ) {
                $age--;
            }

        } elseif( $now_month < $birth_month ) {
            $age--;
        }

        return $age;
    }
endif;
