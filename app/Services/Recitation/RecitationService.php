<?php

namespace App\Services\Recitation;

use App\Models\Mosque;
use App\Models\Student;
use App\Models\User;
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

    public function addRecitation(Student $student, array $data)
    {
        $user = User::findOrFail(Auth::id());
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        try {
            DB::transaction(function () use ($student, $data, $mosque) {

                // Add the recitation dynamically
                $mosque->{$this->foreignKey}()->create(array_merge($data, [
                    'student_id' => $student->id,
                ]));

                // TODO: Increase student points
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
            ->get();
    }

    public function deleteRecitation(Student $student, $id)
    {
        $user = User::findOrFail(Auth::id());
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        $recitation = $mosque->{$this->foreignKey}()->where('id', $id)->where('student_id', $student->id)->firstOrfail();
        //TODO deleted added points for that recitation
        $recitation->delete();
    }

    //
    public function updateRecitation(Student $student, int $id, array $data)
    {
        $user = User::findOrFail(Auth::id());
        $mosque = $student->mosques()->findOrFail($user->mosque->id);

        // Find the existing recitation
        $recitation = $mosque->{$this->foreignKey}()->where('id', $id)->where('student_id', $student->id)->firstOrFail();
        try {

            DB::transaction(function () use ($data, $recitation) {

                // Update the recitation with the new data
                $recitation->update($data);

                // TODO: Adjust points if the recitation details affect points (if needed)
            });

            return ['message' => 'Recitation updated successfully', 'status' => '200'];
        } catch (\Exception $e) {
            return ['message' => 'Error updating recitation: '.$e->getMessage(), 'status' => '400'];
        }
    }
}
