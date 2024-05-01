<?php

namespace App\customClass;

class myNumber {
    public static function str2Float($strNum) {
        //$string = "20.000,10";
        $string = $strNum;
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        $number = (float) $string;
        return $number;
    }

    public static function float2Str($floatNum) {
        // Ubah float menjadi string dengan format yang tepat
        $string = number_format($floatNum, 2, ',', '.');
        return $string;
    }
}
