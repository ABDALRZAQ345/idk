<?php

if (!function_exists('getMex')) {
    function getMex($numbers): int
    {
        // Mex mean first element not in the array
        $numbers = array_unique($numbers);
        sort($numbers);

        $mex = 1;
        foreach ($numbers as $number) {
            if ($number == $mex) {
                $mex++;
            } else {
                break;
            }
        }

        return $mex; // Returns the first unassigned  number
    }
}


