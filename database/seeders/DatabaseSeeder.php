<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Mosque;
use App\Models\Student;
use App\Models\User;
use App\Services\Group\GroupService;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\Sanctum;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @throws \Exception
     */
    public function run(): void
    {
        $this->call([SqlFilesSeeder::class]);

        Mosque::factory(10)->create()->each(function ($mosque) {

            $mosque->students()->createMany(
                Student::factory(100)->make()->toArray()
            );
            $users = User::factory(10)->create([
                'mosque_id' => $mosque->id,
                'role_id' => $mosque->roles->first()->id,
            ]);
            Sanctum::actingAs($users->first());
            $groups = Group::factory(5)->create([
                'mosque_id' => $mosque->id,
                'user_id' => $mosque->users->first()->id,
            ]);
            $group = $groups->first();
            $group_service = new GroupService;
            $students = $mosque->students()->take(10)->get();
            $students->each(function ($student) use ($group, $group_service) {
                $group_service->addStudentToGroup($student, $group);
            });

        });

    }
}
