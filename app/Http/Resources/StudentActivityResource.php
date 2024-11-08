<?php

namespace App\Http\Resources;

use App\Models\Attend;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentActivityResource extends JsonResource
{
    protected $activity;

    public function __construct($resource, $activity)
    {
        parent::__construct($resource);
        $this->activity = $activity;
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
        if (Attend::where('student_id', $student->id)->where('action_id', $this->activity->id)->where('action_type', 'App\Models\Activity')->exists()) {
            $data['attended'] = true;
        } else {
            $data['attended'] = false;
        }

        return $data;
    }
}
