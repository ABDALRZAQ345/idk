<?php

namespace App\Services;

use App\Models\Mosque;
use App\Models\Student;
use App\Models\SurahRecitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class SurahRecitationService
{
    public function addRecitation(Student $student, int $surah, $rate = null)
    {

        try {

            DB::transaction(function () use ($student, $surah, $rate) {

                $user = User::findOrfail(Auth::id());
                // check that the student is belongs to the same mosque of the user who want to add recitation to it
                $mosque = $student->mosques()->findOrFail($user->mosque->id);
                /// add new page recitation
                $surah_recitation = $mosque->surah_recitations()->create([
                    'student_id' => $student->id,
                    'surah_id' => $surah,
                    'rate' => $rate,
                ]);
                /// TODO increase student points

            });

            return [
                'message' => 'Recitation added successfully',
                'status' => '200',
            ];
        } catch (Exception $e) {
            return [
                'message' => 'error  Failed to add recitation: '.$e->getMessage(),
                'status' => '500'];
        }

    }

    public function getRecitations(Student $student, Mosque $mosque)
    {
        $mosque = $mosque = $student->mosques()->findOrFail($mosque->id);
        $recitations = SurahRecitation::where('student_id', $student->id)->where('mosque_id', $mosque->id)->get();

        return $recitations;
    }
}
