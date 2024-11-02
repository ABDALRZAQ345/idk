<?php

namespace App\Services\Recitation;

use App\Models\Mosque;
use App\Models\PageRecitation;
use App\Models\Section;
use App\Models\SectionRecitation;
use App\Models\Student;
use App\Models\Surah;
use App\Models\SurahRecitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class RecitationService
{
    protected $recitationModel;

    protected $foreignKey;

    public function __construct($recitationModel, $foreignKey)
    {

        $this->recitationModel = $recitationModel;
        $this->foreignKey = $foreignKey;
    }

    public function addRecitation(Student $student, array $data): array
    {
        $user = Auth::user();
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        try {
            DB::transaction(function () use ($student, $data, $mosque) {

                // Add the recitation dynamically
                $recitation = $mosque->{$this->foreignKey}()->create(array_merge($data, [
                    'student_id' => $student->id,
                ]));
                $this->increaseStudentPoints($student, $mosque, $data, $recitation);

            });

            return ['message' => 'Recitation added successfully', 'status' => '200'];
        } catch (\Exception $e) {
            return ['message' => 'Error adding recitation: '.$e->getMessage(), 'status' => '500'];
        }
    }

    public function getRecitations(Student $student, Mosque $mosque)
    {
        if (! $student->mosques->contains($mosque->id)) {
            return ['message' => 'Student is not associated with this mosque', 'status' => '404'];
        }

        return $this->recitationModel::where('student_id', $student->id)
            ->where('mosque_id', $mosque->id)
            ->paginate();
    }

    public function deleteRecitation(Student $student, $id): void
    {
        $user = Auth::user();
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        $recitation = $mosque->{$this->foreignKey}()->where('id', $id)->where('student_id', $student->id)->firstOrfail();
        $recitation->points()->delete();
        $recitation->delete();
    }

    //
    public function updateRecitation(Student $student, int $id, array $data): array
    {
        $user = Auth::user();
        $mosque = $student->mosques()->findOrFail($user->mosque->id);

        // Find the existing recitation
        $recitation = $mosque->{$this->foreignKey}()->where('id', $id)->where('student_id', $student->id)->firstOrFail();
        try {

            DB::transaction(function () use ($mosque, $student, $data, $recitation) {

                // Update the recitation with the new data
                $recitation->update($data);
                //updating points
                $recitation->points()->delete();
                $this->increaseStudentPoints($student, $mosque, $data, $recitation);

            });

            return ['message' => 'Recitation updated successfully', 'status' => '200'];
        } catch (\Exception $e) {
            return ['message' => 'Error updating recitation: '.$e->getMessage(), 'status' => '400'];
        }
    }

    public function increaseStudentPoints(Student $student, \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null $mosque, array $data, $recitation): void
    {

        if ($this->recitationModel == PageRecitation::class) {
            $recitation->points()->create([
                'mosque_id' => $mosque->id,
                'student_id' => $student->id,
                'points' => ($data['end_page'] - $data['start_page'] + 1) * $mosque->page_points,
                'reason' => $data['end_page'].' تلاوة قران من الصفحة '.$data['start_page'].' للصفحة ',
            ]);

        } elseif ($this->recitationModel == SectionRecitation::class) {
            $name = Section::findOrFail($data['section_id'])->name;
            $recitation->points()->create([
                'mosque_id' => $mosque->id,
                'student_id' => $student->id,
                'points' => $mosque->section_points,
                'reason' => ' تلاوة قران للجزء '.$name,
            ]);
        } elseif ($this->recitationModel == SurahRecitation::class) {
            $name = Surah::findOrFail($data['surah_id'])->name;
            $recitation->points()->create([
                'mosque_id' => $mosque->id,
                'student_id' => $student->id,
                'points' => $mosque->surah_points()->where('surah_id', $data['surah_id'])->first()->points,
                'reason' => ' تلاوة قران للسورة  '.$name,
            ]);
        }
    }
}
