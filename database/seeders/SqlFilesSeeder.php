<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('sql files');

        // Get all SQL files from the directory
        $sqlFiles = File::files($path);

        // Execute each SQL file
        foreach ($sqlFiles as $file) {
            $sql = File::get($file->getPathname());
            DB::unprepared($sql);
        }
    }
}
