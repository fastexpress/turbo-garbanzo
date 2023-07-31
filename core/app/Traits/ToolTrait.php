<?php

namespace App\Traits;

trait ToolTrait
{
    static function maskToNumber($number)
    {
        $chars = str_split($number);
        $number = "";
        foreach ($chars as $char) {
            if ($char == "Q")
                continue;
            else
                $number .= $char;
        }
        return floatval($number);
    }
}
