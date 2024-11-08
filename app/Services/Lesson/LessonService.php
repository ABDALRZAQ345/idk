<?php

namespace App\Services\Lesson;

use App\Exceptions\NotFoundException;
use App\Models\Activity;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonService
{
    /**
     * @throws \Exception
     */
    public function getUserLessons($user)
    {

        $mosque = $user->mosque;
        if ($user->hasPermission('lesson.read') && $mosque) {
            $lessons = $mosque->lessons();
        } elseif ($user->group) {
            $group=$user->group;
            $lessons = $group->lessons();
        } else {
            $lessons = Lesson::where('id', Lesson::max('id') + 1);
        }

        return $lessons;
    }

    /**
     * @throws NotFoundException
     */
    public function gerUserLesson(User $user, $lesson_id)
    {
        $mosque = $user->mosque;
        if ($user->hasPermission('lesson.read') && $user->mosque) {
            $lessons = $mosque->lessons()->with('groups')->with('enrolledStudents')->FindOrFail($lesson_id);
        } elseif ($user->group) {
            $lessons = $user->group->lessons()->with('groups')->with('enrolledStudents')->FindOrFail($lesson_id);
        } else {
            throw new NotFoundException();
        }

        return $lessons;

    }

    public function AssignStudents(Lesson $lesson, $students_id): void
    {
        try {
            DB::beginTransaction();
            $lesson->attends()->delete();
            foreach ($students_id as $studentId) {
                if ($lesson->enrolledStudents()->find($studentId) == null) {
                    $lesson->attends()->create([
                        'student_id' => $studentId,
                    ]);
                }

            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

    }
}
