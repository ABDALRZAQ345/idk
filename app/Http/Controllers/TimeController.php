<?php

namespace App\Http\Controllers;

class TimeController extends Controller
{
    //
    public function time(): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'time' => now(),
            'formated' => now()->format('Y-m-d H:i:s'),
        ]);

    }
}
