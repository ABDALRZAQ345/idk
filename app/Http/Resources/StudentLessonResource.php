<?php

namespace App\Http\Resources;

use App\Models\Attend;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentLessonResource extends JsonResource
{
    protected $lesson;

    public function __construct($resource, $lesson)
    {
        parent::__construct($resource);
        $this->lesson = $lesson;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $student = Student::find($data['id']);
        if (Attend::where('student_id', $student->id)->where('action_id', $this->lesson->id)->where('action_type', 'App\Models\Lesson')->exists()) {
            $data['attended'] = true;
        } else {
            $data['attended'] = false;
        }

        return $data;
    }
}
