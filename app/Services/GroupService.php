<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Mosque;
use App\Models\Student;

class GroupService
{
    /**
     * @throws \Exception
     */
    public function addStudentToGroup(Student $student, Group $group): void
    {
        $mosque = $group->mosque;
        // Check if the student is already attached to the mosque with any group
        $student = $mosque->students()->find($student->id);

        if ($student != null) {
            // Update the existing relationship to set the group
            $mosque->students()->updateExistingPivot($student->id, ['group_id' => $group->id]);
        } else {
            throw new \Exception('Student is not associated with the specified mosque.');
        }
    }

    /**
     * @throws \Exception
     */
    public function changeStudentGroup(Student $student, Group $newGroup): void
    {
        $mosque = $newGroup->mosque;

        // Check if the student is already attached to the mosque
        $student = $mosque->students()->find($student->id);

        if ($student) {
            // Update the existing relationship to set the new group
            $mosque->students()->updateExistingPivot($student->id, ['group_id' => $newGroup->id]);
        } else {
            throw new \Exception('Student is not associated with the specified mosque.');
        }
    }

    /**
     * @throws \Exception
     */
    public function removeStudentFromGroup(Mosque $mosque, Student $student): void
    {
        // Check if the student is associated with the mosque
        $student = $mosque->students()->find($student->id);

        if ($student) {
            // Update the existing relationship to set group_id to null
            $mosque->students()->updateExistingPivot($student->id, ['group_id' => null]);
        } else {
            throw new \Exception('Student is not associated with the specified mosque.');
        }
    }
}
