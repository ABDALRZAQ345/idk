<?php

namespace App\Jobs;

use App\Models\Point;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteUnnecessaryPoints implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Point::where('created_at', '<=', now()->subYear())->delete();
    }
}
