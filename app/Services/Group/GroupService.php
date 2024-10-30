<?php

namespace App\Services\Group;

use App\Exceptions\FORBIDDEN;
use App\Models\Group;
use App\Models\Mosque;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class GroupService
{
    /**
     * @throws \Exception
     */

    // pass array of students id which you want to add them to group return true if adding success else false
    public function addStudentsToGroup($students, $group): bool
    {
        try {

            DB::beginTransaction();

            foreach ($students as $student) {
                DB::table('mosque_student')
                    ->where('student_id', $student)
                    ->where('mosque_id', $group->mosque->id)
                    ->update(['group_id' => $group->id]);
            }
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function addStudentToGroup(Student $student, Group $group): void
    {
        $user = User::findOrFail(Auth::id());
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        $group = $mosque->groups()->findOrfail($group->id);

        // Check if the student is already attached to the mosque with any group
        $student = $mosque->students()->findOrFail($student->id);

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
        $user = User::findOrFail(Auth::id());
        $mosque = $student->mosques()->findOrFail($user->mosque->id);
        $newGroup = $mosque->groups()->findOrfail($newGroup->id);

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

    public function groups()
    {
        $user = User::find(Auth::id());
        $mosque = $user->mosque;
        if ($user->hasPermission('show_all_groups')) {
            $groups = $mosque->groups;
        } else {
            $groups = $user->group;
        }

        return $groups;

    }

    public function CheckCanAccessGroup(Group $group): void
    {
        /// check that they are belongs to the same mosque
        if (Auth::user()->mosque != $group->mosque) {
            throw new FORBIDDEN('you are not authorized to access this group');
        }

    }
}
