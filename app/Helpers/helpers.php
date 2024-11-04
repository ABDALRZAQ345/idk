<?php

/**
 * get the minimum element not in the array
 */

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

if (! function_exists('getMex')) {
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

if (! function_exists('EmptyPagination')) {

    function EmptyPagination(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            Collection::make([]), // Empty collection
            0,                    // Total items
            20,                   // Per page
            1,                    // Current page
            ['path' => request()->url(), 'query' => request()->query()] // For consistent pagination links
        );
    }

}
