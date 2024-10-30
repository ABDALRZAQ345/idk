<?php

namespace App\Services;

use App\Models\Mosque;
use App\Models\PageRecitation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PageRecitationService
{
    public function addRecitation(Student $student, int $start_page, int $end_page, $rate = null)
    {

        try {

            DB::transaction(function () use ($student, $start_page, $end_page, $rate) {

                $user = User::findOrfail(Auth::id());
                // check that the student is belongs to the same mosque of the user who want to add recitation to it
                $mosque = $student->mosques()->findOrFail($user->mosque->id);
                /// add new page recitation
                $page_recitation = $mosque->page_recitations()->create([
                    'student_id' => $student->id,
                    'start_page' => $start_page,
                    'end_page' => $end_page,
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
        $recitations = PageRecitation::where('student_id', $student->id)->where('mosque_id', $mosque->id)->get();

        return $recitations;
    }
}
