<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new \App\Jobs\DeleteUnnecessaryPoints);
})->monthly();

Schedule::call(function () {
   dispatch(new \App\Jobs\FinishActivities());
})->hourly();
