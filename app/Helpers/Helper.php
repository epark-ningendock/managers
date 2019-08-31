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
