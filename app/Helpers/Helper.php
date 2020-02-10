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
