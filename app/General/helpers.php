<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

if (!function_exists('isLogin')) {
    function isLogin()
    {
        return auth()->check();
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if (!function_exists('stringLength')) {
    function stringLength($string)
    {
        return Str::length($string);
    }
}

if (!function_exists('sanitizeString')) {
    function sanitizeString($string)
    {
        return preg_replace('/[^a-zA-Z0-9_.]/', '_', $string);
    }
}

if (!function_exists('csvToArray')) {
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }
}

if (!function_exists('updateBatchRaw')) {
    function updateBatchRaw($tableName = "", $multipleData = array())
    {
        if ($tableName && !empty($multipleData)) {
            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";
            $q = "UPDATE " . $tableName . " SET ";
            foreach ($updateColumn as $uColumn) {
                $q .=  $uColumn . " = CASE ";
                foreach ($multipleData as $data) {
                    $q .= "WHEN " . $referenceColumn . " = '" . $data[$referenceColumn] . "' THEN '" . $data[$uColumn] . "' ";
                }
                $q .= "ELSE " . $uColumn . " END, ";
            }
            foreach ($multipleData as $data) {
                $whereIn .= "'" . $data[$referenceColumn] . "', ";
            }
            $q = rtrim($q, ", ") . " WHERE " . $referenceColumn . " IN (" .  rtrim($whereIn, ', ') . ")";
            // Update
            return DB::update(DB::raw($q));
        } else {
            return false;
        }
    }
}

if (!function_exists('hardEncode')) {
    function hardEncode($param)
    {
        return base64_encode(base64_encode(base64_encode(base64_encode($param))));
    }
}

if (!function_exists('hardDecode')) {
    function hardDecode($param)
    {
        return base64_decode(base64_decode(base64_decode(base64_decode($param))));
    }
}
